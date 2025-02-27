<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi IT CLUB DATA SCIENCE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php
require_once "data-form-register.php";
?>
<body style="font-size: 18px;">
<form method="POST" action="hasil-form-regis.php" class="container mt-5">
<fieldset class="border border-dark p-3 rounded" style="background-color: skyblue;">
        <legend class="float-none w-auto px-3 fw-bold h3" >Form Registrasin IT CLUB DATA SCIENCE</legend>
  <div class="form-group row">
    <label for="nim" class="col-4 col-form-label">NIM</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-card"></i>
          </div>
        </div> 
        <input id="nim" name="nim" placeholder="0110124174" type="text" required="required" class="form-control" required="required" pattern ="[0-9]{10}"> 
        <div class="input-group-append">
          <div class="input-group-text">
            <i class="fa fa-adn"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="Nama_lengkap" class="col-4 col-form-label">Nama Lengkap</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-book"></i>
          </div>
        </div> 
        <input id="nama_lengkap" name="nama_lengkap" placeholder="*Fathan Anwar" type="text" required="required" class="form-control"required="required" minlenght="3" maxlenght="50">
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4">Jenis Kelamin</label> 
    <div class="col-8">
      <div class="custom-control custom-radio custom-control-inline">
        <input name="jenis_kelamin" id="jenis_kelamin_0" type="radio" required="required" class="custom-control-input" value="Laki-laki" checked> 
        <label for="jenis_kelamin_0" class="custom-control-label">Laki-laki</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input name="jenis_kelamin" id="jenis_kelamin_1" type="radio" required="required" class="custom-control-input" value="Perempuan"> 
        <label for="jenis_kelamin_1" class="custom-control-label">Perempuan</label>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="program_studi" class="col-4 col-form-label">Program Studi</label> 
    <div class="col-8">
      <select id="program_studi" name="program_studi" class="custom-select" required="required">
        <?php
        foreach($ar_prodi as $kode => $nama){
            echo"<option value='$kode'>$nama</option>";
        }
        ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4">Skill Web &amp; programing</label> 
    <div class="col-8">
      <?php
      foreach($ar_skill as $nama => $nilai ){
        echo "<div class='custom-control custom-checkbox custom-control-inline'>";
        echo "<input name='skills[]' id='skills_$nama'type='checkbox' class='custom-control-input' value='$nama'>";
        echo "<label for='skills_$nama' class='custom-control-label'>$nama</label>";
        echo "</div>";
      }
   ?>
    </div>
  </div>
  <div class="form-group row">
    <label for="domisili" class="col-4 col-form-label">Domsili</label> 
    <div class="col-8">
      <select id="domisili" name="domisili" class="custom-select" required="required">
        <?php
        foreach($ar_domisili as $nama){
        echo "<option value='$nama'>$nama</option>";    

        }  
        ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="mail" class="col-4 col-form-label">Email</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-gg-circle"></i>
          </div>
        </div> 
        <input id="mail" name="mail" placeholder="*uunggede12@gmail.com" type="email" class="form-control" required="required">
      </div>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</fieldset>
</form> 
</body>
</html>