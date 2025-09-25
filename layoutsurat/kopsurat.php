<?php
require_once '../include/config.php';
require_once '../include/functions.php';

$sql = "SELECT * FROM tb_sekolah LIMIT 1"; // Adjust query as needed
$result = $config->query($sql);
$sekolah = $result->fetch_assoc();
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
    <link rel="shortcut icon" href="../aset/smk.png">

    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="../css/simplebar.css">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:wght@100;400&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="../css/feather.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="../css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="../css/app-dark.css" id="darkTheme" disabled>

</head>
<body>
<style>
        @media print {
            @page {
                size: A4;
                margin: 20mm; /* Atur margin sesuai kebutuhan */
            }
            body {
                margin: 0; /* Hapus margin default */
            }
        }

        .kop-surat {
            margin-bottom: 20px; /* Margin bawah untuk kop surat */
        }
    </style>

<div class="container">
    <table style="width: 100%; border-collapse: collapse; margin-top: 18px;">
        <tr>
            <td style="width: 15%; text-align: right; vertical-align: center;">
                <img src="../aset/smk.png" alt="Logo SMK" style="max-width: 100%; height: auto;">
            </td>
            <td style="width: 85%; text-align: center; vertical-align: top;">
                <h5><?php echo strtoupper($sekolah['majelis']); ?></h5>
                <h3 style="line-height: 0.5;"><?php echo strtoupper($sekolah['yayasan']); ?></h3>
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
            </td>
        </tr>
    </table>
    <hr style="border: 3px solid black; margin: 5px 0;">
    <hr style="border: 1px solid black; margin: 0.8px 0;">
</div>

<script language=javascript>
    function printWindow() {
        bV = parseInt(navigator.appVersion);
        if (bV >= 4) window.print();
    }
    printWindow();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>