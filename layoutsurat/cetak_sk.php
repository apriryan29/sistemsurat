<?php 
include 'kopsurat.php';
include '../include/config.php';

$nomor_surat = $_POST['nomor_surat'];
$isi = $_POST['isi'];

if (isset($_POST['tentang'])) {
    $tentangId = $_POST['tentang'];

    // Ambil data tentang berdasarkan ID
    $sql_tentang = "SELECT * FROM tb_perihal WHERE id_perihal = $tentangId";
    $result_tentang = $config->query($sql_tentang);
    $tentangData = $result_tentang->fetch_assoc();
}
?>

<div style="font-family: 'Times New Roman'; color: black;">
    <div style="text-align: center; margin-top: 2rem;">
        <p style="font-weight: bold; font-size: 24px;"><u>SURAT KEPUTUSAN KEPALA SEKOLAH</u> <br>Nomor : <?php echo $nomor_surat; ?></p>
        <p style="font-weight: bold; font-size: 24px;">Tentang <br><?php echo $tentangData['tentang']; ?></p>
    </div>

    <div style="text-align: justify;  padding-left:2rem; padding-right: 3rem; font-size: 22px;">
        <div style="padding-top: 1rem;"><?php echo $tentangData['pembuka']; ?></div>
        <table>
            <tr style="vertical-align: top;">
                <td style="padding-right: 5rem;">MEMPERHATIKAN</td>
                <td style="padding-right: 2rem;">:</td>
                <td><?php echo $tentangData['memperhatikan']; ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>MENIMBANG</td>
                <td>:</td>
                <td><?php echo $tentangData['menimbang']; ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>MENGINGAT</td>
                <td>:</td>
                <td><?php echo $tentangData['mengingat']; ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; padding-top: 2rem;">MEMUTUSKAN</td>
            </tr>
            <tr style="vertical-align: top;">
                <td style="padding-right: 6rem;">MENETAPKAN</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Pertama</td>
                <td style="padding-right: 2rem;">:</td>
                <td><?php echo $isi; ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Kedua</td>
                <td>:</td>
                <td><?php echo $tentangData['menetapkan_2']; ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Ketiga</td>
                <td>:</td>
                <td><?php echo $tentangData['menetapkan_3']; ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Keempat</td>
                <td>:</td>
                <td><?php echo $tentangData['menetapkan_4']; ?></td>
            </tr>
        </table>
        <div style="padding-top: 1rem;"><?php echo $tentangData['penutup']; ?></div>

        <table class="no-break" style="margin-top: 2rem;">
            <tr>
                <td></td>
                <td></td>
                <td style="padding-left: 35rem;">Ditetapkan di</td>
                <td style="padding-left: 1rem; padding-right: 1rem;">:</td>
                <td>Sampang</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="padding-left: 35rem;">Pada Tanggal</td>
                <td style="padding-left: 1rem; padding-right: 1rem;">:</td>
                <td>
                    <?php
                    // Array untuk nama bulan dalam bahasa Indonesia
                    $bulanIndo = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember'
                    ];

                    // Ambil tanggal saat ini
                    $tanggal = date('j'); // Hari
                    $bulan = $bulanIndo[date('n')]; // Bulan
                    $tahun = date('Y'); // Tahun

                    // Format tanggal dalam bahasa Indonesia
                    $tanggalFormat = "$tanggal $bulan $tahun";

                    echo $tanggalFormat;
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="3" style="padding-left: 35rem; padding-top: 1rem;">Kepala Sekolah</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="3" style="padding-left: 35rem; padding-top: 7rem;">Budi M...hjadayugdahda...., S.Sos</td>
            </tr>
            <tr>
                <td colspan="5" style="padding-top: 1rem;">Tembusan kepada Yth :</td>
            </tr>
            <tr>
                <td colspan="5">1. .......................</td>
            </tr>
        </table>
    </div>
</div>

<style>
    @media print {
        .no-break {
            page-break-inside: avoid; /* Mencegah pemisahan tabel */
            width: 100%; /* Memastikan tabel menggunakan lebar penuh */
        }
        .no-break tr {
            page-break-inside: avoid; /* Mencegah pemisahan baris */
            page-break-after: auto;
        }
    }
</style>