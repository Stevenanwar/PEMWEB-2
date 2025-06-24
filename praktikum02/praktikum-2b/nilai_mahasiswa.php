<?php
$proses = $_POST['simpan'];
$nama_lengkap = $_POST['nama_lengkap'];
$mata_kuliah = $_POST['mata_kuliah'];
$nilai_uts = $_POST['nilai_uts'];
$nilai_uas = $_POST['nilai_uas'];
$nilai_tugas = $_POST['nilai_tugas'];

// Menghitung nilai akhir (rata-rata dari UTS, UAS, dan Tugas)
$nilai_akhir = ($nilai_uts + $nilai_uas + $nilai_tugas) / 3;

// Menentukan Grade berdasarkan nilai akhir
if ($nilai_akhir >= 0 && $nilai_akhir <= 35) {
    $grade = "E";
} elseif ($nilai_akhir >= 36 && $nilai_akhir <= 55) {
    $grade = "D";
} elseif ($nilai_akhir >= 56 && $nilai_akhir <= 69) {
    $grade = "C";
} elseif ($nilai_akhir >= 70 && $nilai_akhir <= 84) {
    $grade = "B";
} elseif ($nilai_akhir >= 85 && $nilai_akhir <= 100) {
    $grade = "A";
} else {
    $grade = "I";
}

// Menentukan Predikat berdasarkan Grade
switch ($grade) {
    case "E":
        $predikat = "Sangat Kurang";
        break;
    case "D":
        $predikat = "Kurang";
        break;
    case "C":
        $predikat = "Cukup";
        break;
    case "B":
        $predikat = "Memuaskan";
        break;
    case "A":
        $predikat = "Sangat Memuaskan";
        break;
    default:
        $predikat = "Tidak Ada";
        break;
}

// Menentukan Status Kelulusan
$status = ($nilai_akhir >= 56) ? "Lulus" : "Tidak Lulus";

// Menampilkan hasil
echo 'Proses : Simpan';
echo '<br/>Nama : '.$nama_lengkap;
echo '<br/>Mata Kuliah : '.$mata_kuliah;
echo '<br/>Nilai UTS : '.$nilai_uts;
echo '<br/>Nilai UAS : '.$nilai_uas;
echo '<br/>Nilai Tugas Praktikum : '.$nilai_tugas;
echo '<br/>Nilai Akhir : '.number_format($nilai_akhir, 2, ',', '');
echo '<br/>Status : '.$status;
echo '<br/>Grade : '.$grade;
echo '<br/>Predikat : '.$predikat;
?>