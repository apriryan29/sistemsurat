<?php
require_once 'include/config.php';

$sql = "SELECT * FROM tb_sekolah LIMIT 1";
$result = $config->query($sql);
$sekolah = $result->fetch_assoc();
?>

<style>
        .kop-surat {
            margin-bottom: 20px;
        }
        .page-break {
        page-break-before: always;
        }

    </style>

<!-- Kop Surat siap cetak -->
<div class="container" style="font-family: 'Times New Roman';">
    <table style="width: 100%; border-collapse: collapse; margin-top: 18px;">
        <tr>
            <td style="width: 15%; text-align: right; vertical-align: center;">
                <img src="aset/smk.png" alt="Logo SMK" style="max-width: 100%; height: auto;">
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
