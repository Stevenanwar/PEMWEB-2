<?php
// Konfigurasi koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

// Membuat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Memeriksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Inisialisasi variabel
$id = "";
$nip = "";
$nama = "";
$jenis_kelamin = "";
$jabatan = "";
$pesan = "";
$error = "";

// Proses form jika method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    
    // Validasi input
    if (empty($nip) || empty($nama) || empty($jenis_kelamin) || empty($jabatan)) {
        $error = "Semua field harus diisi!";
    } else {
        // Cek apakah ada id yang dikirim (untuk update)
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = mysqli_real_escape_string($koneksi, $_POST['id']);
            
            // Query untuk update data
            $query = "UPDATE pegawai SET nip='$nip', nama='$nama', jenis_kelamin='$jenis_kelamin', jabatan='$jabatan' WHERE id=$id";
            
            if (mysqli_query($koneksi, $query)) {
                $pesan = "Data pegawai berhasil diperbarui";
                // Reset form
                $id = "";
                $nip = "";
                $nama = "";
                $jenis_kelamin = "";
                $jabatan = "";
            } else {
                $error = "Error: " . $query . "<br>" . mysqli_error($koneksi);
            }
        } else {
            // Query untuk insert data baru
            $query = "INSERT INTO pegawai (nip, nama, jenis_kelamin, jabatan) VALUES ('$nip', '$nama', '$jenis_kelamin', '$jabatan')";
            
            if (mysqli_query($koneksi, $query)) {
                $pesan = "Data pegawai berhasil disimpan";
                // Reset form
                $nip = "";
                $nama = "";
                $jenis_kelamin = "";
                $jabatan = "";
            } else {
                $error = "Error: " . $query . "<br>" . mysqli_error($koneksi);
            }
        }
    }
}

// Proses untuk edit data
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $query = "SELECT * FROM pegawai WHERE id=$id";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $nip = $row['nip'];
        $nama = $row['nama'];
        $jenis_kelamin = $row['jenis_kelamin'];
        $jabatan = $row['jabatan'];
    }
}

// Proses untuk hapus data
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query = "DELETE FROM pegawai WHERE id=$id";
    
    if (mysqli_query($koneksi, $query)) {
        $pesan = "Data pegawai berhasil dihapus";
    } else {
        $error = "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Pegawai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-reset {
            background-color: #f44336;
            color: white;
        }
        .btn-kembali {
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 5px;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .edit {
            background-color: #2196F3;
            color: white;
        }
        .delete {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Form Data Pegawai</h1>
        
        <?php if (!empty($pesan)): ?>
            <div class="message success"><?php echo $pesan; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="form-group">
                <label for="nip">NIP:</label>
                <input type="text" id="nip" name="nip" value="<?php echo $nip; ?>" maxlength="10" required>
            </div>
            
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" maxlength="45" required>
            </div>
            
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">- Pilih Jenis Kelamin -</option>
                    <option value="L" <?php if ($jenis_kelamin == "L") echo "selected"; ?>>Laki-laki</option>
                    <option value="P" <?php if ($jenis_kelamin == "P") echo "selected"; ?>>Perempuan</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="jabatan">Jabatan:</label>
                <input type="text" id="jabatan" name="jabatan" value="<?php echo $jabatan; ?>" maxlength="45" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" class="btn btn-reset">Reset</button>
                <a href="dashboard.php" class="btn btn-kembali">Kembali</a>
            </div>
        </form>
        
        <h2>Daftar Pegawai</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk menampilkan data pegawai
                $query = "SELECT * FROM pegawai ORDER BY id DESC";
                $result = mysqli_query($koneksi, $query);
                $no = 1;
                
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['nip'] . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . ($row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') . "</td>";
                        echo "<td>" . $row['jabatan'] . "</td>";
                        echo "<td>
                            <a href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?edit=" . $row['id'] . "' class='action-btn edit'>Edit</a>
                            <a href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?hapus=" . $row['id'] . "' class='action-btn delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>