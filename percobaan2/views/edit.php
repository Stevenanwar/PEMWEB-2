<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Inisialisasi variabel
$id = "";
$status_aktif = "";
$pegawai_id = "";
$kartu_diskon_id = "";
$alert = "";

// Ambil data anggota berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM anggota WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $status_aktif = $row['status_aktif'];
        $pegawai_id = $row['pegawai_id'];
        $kartu_diskon_id = $row['kartu_diskon_id'];
    } else {
        die("Data anggota tidak ditemukan.");
    }
}

// Proses update data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;
    $pegawai_id = $_POST['pegawai_id'];
    $kartu_diskon_id = !empty($_POST['kartu_diskon_id']) ? $_POST['kartu_diskon_id'] : null;

    if (empty($pegawai_id)) {
        $alert = '<div class="alert alert-danger">Pegawai harus dipilih!</div>';
    } else {
        $query = "UPDATE anggota SET 
                    status_aktif = '$status_aktif',
                    pegawai_id = '$pegawai_id',
                    kartu_diskon_id = " . ($kartu_diskon_id ? "'$kartu_diskon_id'" : "NULL") . " 
                  WHERE id = '$id'";

        if (mysqli_query($koneksi, $query)) {
            $alert = '<div class="alert alert-success">Data anggota berhasil diperbarui!</div>';
        } else {
            $alert = '<div class="alert alert-danger">Error: ' . mysqli_error($koneksi) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Anggota</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Edit Data Anggota</h3>
    <?php echo $alert; ?>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label for="pegawai_id" class="form-label">Pegawai</label>
            <select class="form-select" name="pegawai_id" id="pegawai_id" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php
                $query_pegawai = "SELECT * FROM pegawai ORDER BY nama ASC";
                $result_pegawai = mysqli_query($koneksi, $query_pegawai);
                while ($row_pegawai = mysqli_fetch_assoc($result_pegawai)) {
                    $selected = ($pegawai_id == $row_pegawai['id']) ? 'selected' : '';
                    echo "<option value='" . $row_pegawai['id'] . "' $selected>" . $row_pegawai['nama'] . " (" . $row_pegawai['nip'] . ")</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="kartu_diskon_id" class="form-label">Kartu Diskon</label>
            <select class="form-select" name="kartu_diskon_id" id="kartu_diskon_id">
                <option value="">-- Pilih Kartu Diskon --</option>
                <?php
                $query_kartu = "SELECT * FROM kartu_diskon ORDER BY nama ASC";
                $result_kartu = mysqli_query($koneksi, $query_kartu);
                while ($row_kartu = mysqli_fetch_assoc($result_kartu)) {
                    $selected = ($kartu_diskon_id == $row_kartu['id']) ? 'selected' : '';
                    echo "<option value='" . $row_kartu['id'] . "' $selected>" . $row_kartu['nama'] . " (" . $row_kartu['persen_diskon'] . "%)</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="status_aktif" name="status_aktif" <?php echo ($status_aktif == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="status_aktif">Status Aktif</label>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Perbarui Data</button>
        <a href="anggota.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
