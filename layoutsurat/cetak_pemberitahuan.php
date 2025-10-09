<?php 
include 'kopsurat.php';
include '../include/config.php';

$nomor_surat = $_POST['nomor_surat'];
$isi = $_POST['isi'];
$lamp = $_POST['lampiran'];
$tujuan = $_POST['tujuan'];

if (isset($_POST['tentang'])) {
    $tentangId = $_POST['tentang'];

    // Ambil data tentang berdasarkan ID
    $sql_tentang = "SELECT * FROM tb_perihal WHERE id_perihal = $tentangId";
    $result_tentang = $config->query($sql_tentang);
    $tentangData = $result_tentang->fetch_assoc();
}
?>


<div style="font-family: 'Times New Roman'; color: black; margin-right: 2rem; margin-left: 1rem;">
    <table style="font-size: 22px; width: 100%; ">
        <tr>
            <td style="width: 7%;">Nomor</td>
            <td>:</td>
            <td><?php echo $nomor_surat; ?></td>
            <td style="text-align: end;">Sampang,
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
            <td>Lamp</td>
            <td>:</td>
            <td colspan="2"><?php echo $lamp; ?></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td colspan="2"><?php echo $tentangData['tentang']; ?></td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top: 2rem; padding-left: 5rem;">Kepada Yth.</td>
        </tr>
        <tr>
            <td colspan="4" style="padding-left: 5rem;"><?php echo $tujuan; ?></td>
        </tr>
        <tr>
            <td colspan="4" style="padding-left: 5rem;">Di - Tempat</td>
        </tr>
    </table>

<!-- ISI SURAT -->
    <table style="font-size: 22px; width: 90%; margin-left:5rem; text-align: justify;">
        <tr>
            <td style="padding-top: 3rem;">Bismillahirohmanirrohim <br> Assalamualaikum wr. wb.</td>
        </tr>
        <!-- Pembuka -->
        <tr>
            <td style="padding-top: 1rem; text-indent: 3rem;"><?php echo $tentangData['pembuka']; ?>
            </td>
        </tr>
        <!-- Isi Pembuka -->
        <tr>
            <td style="padding-top: 1rem; text-indent: 3rem;"><?php echo $tentangData['isi']; ?>
            </td>
        </tr>
        <!-- ISI SURAT -->
        <tr>
            <td style="padding-top: 1rem;"><?php echo $isi; ?>
            </td>
        </tr>
        <!-- Isi Penutup -->
        <tr>
            <td style="padding-top: 1rem; text-indent: 3rem;"><?php echo $tentangData['penutup']; ?></td>
        </tr>
        <tr>
            <td style="padding-top: 1rem;">Jazakumullohu khoiron katsiron <br> Wassalamu'alaikum wr. wb.</td>
        </tr>
    </table>

    <!-- Tabel Tanda Tangan -->
    <table style="font-size: 22px; width: 100%;" class="no-break">
        <tr>
            <td style="padding-top: 3rem; padding-left: 45rem;">Kepala Sekolah</td>
        </tr>
        <tr>
            <td style="padding-top: 8rem; padding-left: 45rem;">Budi Martanto, S.S <br>NBM. 1084 462</td>
        </tr>
    </table>
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