<?php

require_once 'include/config.php';
require_once 'include/functions.php';

$sql = "SELECT * FROM tb_sekolah LIMIT 1"; // Adjust query as needed
$result = $config->query($sql);
$sekolah = $result->fetch_assoc()

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Arsip - SMK</title>
    
    <!-- menampilkan logo pada judul aplikasi -->
    <?php
      echo '<link rel="shortcut icon" href="aset/smk.png">';
    ?>

    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="css/simplebar.css">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="css/feather.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="css/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
  </head>
<body>
    

<div class="container">
        <div class="kop-surat row align-items-center">
            <div class="col-md-2 text-center">
                <img src="aset/smk.png" alt="Logo SMK">
            </div>
            <div class="col-md-8 text-center">
                <h5><?php echo strtoupper($sekolah['majelis']); ?></h5>
                <h3 style="line-height: 0.8;"><?php echo strtoupper($sekolah['yayasan']); ?></h3>
                <h1 style="line-height: 0.8;"><?php echo strtoupper($sekolah['nama_sekolah']); ?></h1>
                <p style="line-height: 0.5;"><?php echo strtoupper($sekolah['kelompok']); ?></p>
                <p style="line-height: 0.1; font-style: italic;"> Alamat:
                    <?php echo $sekolah['alamat']; ?> 
                        <span style="margin-left: 10px;">Telp. <?php echo($sekolah['telepon']); ?></span>
                        <span style="margin-left: 10px;">Kode Pos <?php echo($sekolah['kode_pos']); ?></span>
                        <span style="margin-left: 10px;"><?php echo($sekolah['kecamatan']); ?></span>
                </p>
                <p style="line-height: 0.5;">
                    Email : <?php echo $sekolah['email']; ?> 
                    <span style="margin-left: 16px;">Web: <?php echo($sekolah['web']); ?></span>
                </p>
            </div>
        </div>
        <hr style="border: 3px solid black; margin: 5px 0;">
        <hr style="border: 1px solid black; margin: 0.8px 0;">
</div>

</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>