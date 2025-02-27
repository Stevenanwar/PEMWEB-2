<?php
require_once "data-form-register.php";

$nim =$_POST['nim'];

$nama = $_POST['nama_lengkap'];

$jk = $_POST['jenis_kelamin'];

$prodi = $_POST['program_studi'];

$skill = $_POST['skills'];

$domisili = $_POST['domisili'];

$email = $_POST['mail'];
$skor_skill = skor_skill($skill, $ar_skill);
$kategori_skill= kategori_skill($skor_skill);

function skor_skill($skill_pilihan, $ar_skill){
    $skor = 0;
    foreach ($skill_pilihan as $skill){
        if(isset($ar_skill[$skill])){
            $skor = $skor + $ar_skill[$skill];
        }
    }
    return $skor;
}
function kategori_skill($skor_skill = 0){
    switch ($skor_skill){
        case 0:
            return "TIdak Ada";
            break;
        case $skor_skill <= 40:
            return "Kurang";
            break;
        case $skor_skill <= 60:
            return "Cukup";
            break;
        case $skor_skill <= 100:
            return "Baik";
            break;
        case $skor_skill <= 150:
            return "Sangat Baik";
            break;
        default:
            return "Tidak Terkategori";
            break;
}
}



?>