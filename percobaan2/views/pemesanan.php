<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

// Establi sh database connection with error handling
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set character set to ensure proper character handling
mysqli_set_charset($conn, "utf8mb4");

// Inisialisasi variabel
$error = "";
$success = "";

// Handle delete order if requested
if (isset($_GET['hapus'])) {
    $hapus_id = mysqli_real_escape_string($conn, $_GET['hapus']);

    // Start transaction for safe deletion
    mysqli_begin_transaction($conn);

    try {
        // First, restore product stock from the deleted order
        $query_restore_stock = "
            UPDATE produk p
            JOIN detail_pesanan dp ON dp.produk_id = p.id
            SET p.stok = p.stok + dp.jumlah
            WHERE dp.pesanan_id = '$hapus_id'
        ";
        mysqli_query($conn, $query_restore_stock);

        // Delete detail pesanan first (due to foreign key constraint)
        $query_hapus_detail = "DELETE FROM detail_pesanan WHERE pesanan_id = '$hapus_id'";
        mysqli_query($conn, $query_hapus_detail);

        // Then delete pesanan
        $query_hapus_pesanan = "DELETE FROM pesanan WHERE id = '$hapus_id'";
        mysqli_query($conn, $query_hapus_pesanan);

        // Commit transaction
        mysqli_commit($conn);

        $success = "Pesanan berhasil dihapus!";
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $error = "Gagal menghapus pesanan: " . $e->getMessage();
    }
}

// Mengambil data anggota untuk dropdown
$query_anggota = "SELECT a.id, p.nama 
                  FROM anggota a 
                  JOIN pegawai p ON a.pegawai_id = p.id 
                  WHERE a.status_aktif = 1";
$result_anggota = mysqli_query($conn, $query_anggota);

// Mengambil data produk untuk dropdown
$query_produk = "SELECT p.id, p.nama, p.harga, p.stok, jp.nama AS jenis_produk_nama 
                 FROM produk p 
                 JOIN jenis_produk jp ON p.jenis_produk_id = jp.id
                 WHERE p.stok > 0";
$result_produk = mysqli_query($conn, $query_produk);
$all_produk = [];
while ($produk = mysqli_fetch_assoc($result_produk)) {
    $all_produk[] = $produk;
}

// Proses form pemesanan
if (isset($_POST['submit_pemesanan'])) {
    // Sanitize input
    $anggota_id = mysqli_real_escape_string($conn, $_POST['anggota_id']);
    $produk_id = mysqli_real_escape_string($conn, $_POST['produk_id']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tanggal = date('Y-m-d');

    // Validasi input
    if (empty($anggota_id) || empty($produk_id) || empty($jumlah)) {
        $error = "Silakan lengkapi semua field!";
    } elseif ($jumlah <= 0) {
        $error = "Jumlah pesanan harus lebih dari 0!";
    } else {
        // Cek stok produk dengan query prepared statement
        $stmt_stok = mysqli_prepare($conn, "SELECT stok FROM produk WHERE id = ?");
        mysqli_stmt_bind_param($stmt_stok, "i", $produk_id);
        mysqli_stmt_execute($stmt_stok);
        $result_stok = mysqli_stmt_get_result($stmt_stok);
        $data_stok = mysqli_fetch_assoc($result_stok);

        if ($jumlah > $data_stok['stok']) {
            $error = "Stok tidak mencukupi! Tersedia hanya {$data_stok['stok']} item.";
        } else {
            // Start transaction for order processing
            mysqli_begin_transaction($conn);

            try {
                // Cek apakah anggota memiliki kartu diskon
                $diskon = 0;
                $stmt_diskon = mysqli_prepare($conn, "
                    SELECT kd.persen_diskon 
                    FROM kartu_diskon kd 
                    JOIN anggota a ON kd.id = a.kartu_diskon_id 
                    WHERE a.id = ?
                ");
                mysqli_stmt_bind_param($stmt_diskon, "i", $anggota_id);
                mysqli_stmt_execute($stmt_diskon);
                $result_diskon = mysqli_stmt_get_result($stmt_diskon);

                if (mysqli_num_rows($result_diskon) > 0) {
                    $data_diskon = mysqli_fetch_assoc($result_diskon);
                    $diskon = $data_diskon['persen_diskon'];
                }

                // Insert pesanan
                $stmt_pesanan = mysqli_prepare($conn, "
                    INSERT INTO pesanan (anggota_id, tanggal, diskon, status_bayar) 
                    VALUES (?, ?, ?, 0)
                ");
                mysqli_stmt_bind_param($stmt_pesanan, "isd", $anggota_id, $tanggal, $diskon);
                mysqli_stmt_execute($stmt_pesanan);
                $pesanan_id = mysqli_insert_id($conn);

                // Insert detail pesanan
                $stmt_detail = mysqli_prepare($conn, "
                    INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah) 
                    VALUES (?, ?, ?)
                ");
                mysqli_stmt_bind_param($stmt_detail, "iii", $pesanan_id, $produk_id, $jumlah);
                mysqli_stmt_execute($stmt_detail);

                // Update stok produk
                $stmt_update_stok = mysqli_prepare($conn, "
                    UPDATE produk SET stok = stok - ? WHERE id = ?
                ");
                mysqli_stmt_bind_param($stmt_update_stok, "ii", $jumlah, $produk_id);
                mysqli_stmt_execute($stmt_update_stok);

                // Commit transaction
                mysqli_commit($conn);

                $success = "Pemesanan berhasil dibuat! ID Pesanan: " . $pesanan_id;
            } catch (Exception $e) {
                // Rollback transaction on error
                mysqli_rollback($conn);
                $error = "Gagal membuat pesanan: " . $e->getMessage();
            }
        }
    }
}

// Mengambil semua data pemesanan untuk tabel
$query_pemesanan = "
    SELECT p.id, p.tanggal, p.status_bayar, p2.nama as anggota_nama, 
           GROUP_CONCAT(pr.nama SEPARATOR ', ') as produk_nama, 
           SUM(dp.jumlah) as total_item,
           SUM(pr.harga * dp.jumlah) as total_harga,
           p.diskon
    FROM pesanan p
    JOIN anggota a ON p.anggota_id = a.id
    JOIN pegawai p2 ON a.pegawai_id = p2.id
    JOIN detail_pesanan dp ON p.id = dp.pesanan_id
    JOIN produk pr ON dp.produk_id = pr.id
    GROUP BY p.id
    ORDER BY p.tanggal DESC
";
$result_pemesanan = mysqli_query($conn, $query_pemesanan);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pemesanan - Koperasi Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4 text-center">Manajemen Pemesanan Koperasi</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Form Pemesanan Baru -->
        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Form Pemesanan Baru</h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="anggota_id" class="form-label">Pilih Anggota:</label>
                            <select class="form-select" id="anggota_id" name="anggota_id" required>
                                <option value="">-- Pilih Anggota --</option>
                                <?php while ($anggota = mysqli_fetch_assoc($result_anggota)): ?>
                                    <option value="<?php echo $anggota['id']; ?>">
                                        <?php echo htmlspecialchars($anggota['id'] . " - " . $anggota['nama']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="produk_id" class="form-label">Pilih Produk:</label>
                            <select class="form-select" id="produk_id" name="produk_id" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($all_produk as $produk): ?>
                                    <option value="<?php echo $produk['id']; ?>"
                                        data-stok="<?php echo $produk['stok']; ?>"
                                        data-harga="<?php echo $produk['harga']; ?>">
                                        <?php
                                        echo htmlspecialchars($produk['nama'] . " - Rp " .
                                            number_format($produk['harga'], 0, ',', '.') .
                                            " (Stok: " . $produk['stok'] . ")");
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah:</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                        <small class="text-muted" id="stok-info"></small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="submit_pemesanan">Buat Pemesanan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Pemesanan -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0">Daftar Pemesanan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Anggota</th>
                                <th>Produk</th>
                                <th>Jumlah Item</th>
                                <th>Total Harga</th>
                                <th>Diskon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result_pemesanan) > 0):
                                while ($pesanan = mysqli_fetch_assoc($result_pemesanan)):
                                    // Hitung total setelah diskon
                                    $harga_sebelum_diskon = $pesanan['total_harga'];
                                    $nilai_diskon = ($harga_sebelum_diskon * $pesanan['diskon']) / 100;
                                    $harga_setelah_diskon = $harga_sebelum_diskon - $nilai_diskon;
                            ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pesanan['id']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($pesanan['tanggal'])); ?></td>
                                        <td><?php echo htmlspecialchars($pesanan['anggota_nama']); ?></td>
                                        <td><?php echo htmlspecialchars($pesanan['produk_nama']); ?></td>
                                        <td><?php echo htmlspecialchars($pesanan['total_item']); ?></td>
                                        <td>
                                            <?php if ($pesanan['diskon'] > 0): ?>
                                                <del class="text-muted">Rp <?php echo number_format($harga_sebelum_diskon, 0, ',', '.'); ?></del><br>
                                                <strong>Rp <?php echo number_format($harga_setelah_diskon, 0, ',', '.'); ?></strong>
                                            <?php else: ?>
                                                <strong>Rp <?php echo number_format($harga_sebelum_diskon, 0, ',', '.'); ?></strong>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                            endif;
                            ?>