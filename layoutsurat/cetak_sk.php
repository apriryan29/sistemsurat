<?php 
include 'kopsurat.php';
include '../include/config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID surat tidak ditemukan");
}
$id = intval($_GET['id']);
$query = "
SELECT
    k.nomor_surat,
    k.kode_surat,
    k.tujuan,
    k.tanggal,
    k.ttd,
    p.tentang,
    p.pembuka,
    p.memperhatikan,
    p.menimbang,
    p.mengingat,
    p.penutup,
    p.menetapkan_2,
    p.menetapkan_3,
    p.menetapkan_4,
    k.status_verifikasi,
    s.isi,
    s.tembusan
FROM tb_keluar k
JOIN tb_perihal p ON k.id_perihal = p.id_perihal
LEFT JOIN tb_sk s ON k.id_keluar = s.id_keluar
WHERE k.id_keluar = $id LIMIT 1
";
$result = $config->query($query);
if ($result->num_rows == 0){
    die("Surat Keputusan belum tersedia");
}
$data = $result->fetch_assoc();

$id_kepala = 1;
$qKepala = $config->query("SELECT * FROM tb_kepala WHERE id_kepala = '$id_kepala'");
$kepala = $qKepala->fetch_assoc();

?>

<div style="font-family: 'Times New Roman'; color: black;">
    <div style="text-align: center; margin-top: 2rem;">
        <p style="font-weight: bold; font-size: 24px;"><u>SURAT KEPUTUSAN KEPALA SEKOLAH</u> <br>Nomor : 
            <?= htmlspecialchars($data['nomor_surat']); ?>
            /III.4.AU /<?= htmlspecialchars($data['kode_surat']); ?>
            /<?= date('Y', strtotime($data['tanggal'])) ?></p>
        <p style="font-weight: bold; font-size: 24px;">Tentang <br><?= htmlspecialchars($data['tentang']); ?></p>
    </div>

    <div style="text-align: justify;  padding-left:2rem; padding-right: 3rem; font-size: 22px;">
        <div style="padding-top: 1rem;"><?= htmlspecialchars($data['pembuka']); ?></div>
        <table>
            <tr style="vertical-align: top;">
                <td style="padding-right: 5rem;">MEMPERHATIKAN</td>
                <td style="padding-right: 2rem;">:</td>
                <td><?= htmlspecialchars($data['memperhatikan']); ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>MENIMBANG</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['menimbang']); ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>MENGINGAT</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['mengingat']); ?></td>
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
                <td><?= htmlspecialchars($data['isi']); ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Kedua</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['menetapkan_2']); ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Ketiga</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['menetapkan_3']); ?></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Keempat</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['menetapkan_4']); ?></td>
            </tr>
        </table>
        <div style="padding-top: 1rem;"><?= htmlspecialchars($data['penutup']); ?></div>


        <!-- TANDA TANGAN -->
        <?php
            // Jika TTD ada (apa saja), padding = 0, jika tidak ada = 8rem
            $paddingTTD = ($data['ttd'] == 'Tanda Tangan Saja' || $data['ttd'] == 'Tanda Tangan dan Cap') 
                ? '0' 
                : '8rem';
        ?>
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
                <td style="padding-top:<?= $paddingTTD ?>; position:relative;">
                    <!-- TANDA TANGAN -->
                    <?php if($data['ttd'] == 'Tanda Tangan Saja' || $data['ttd'] == 'Tanda Tangan dan Cap'): ?>
                        <?php if(!empty($kepala['ttd'])): ?>
                            <img src="../<?= $kepala['ttd']; ?>" width="330"><br>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- CAP SEKOLAH -->
                    <?php if($data['ttd'] == 'Tanda Tangan dan Cap'): ?>
                        <?php if(!empty($kepala['ttd_cap'])): ?>
                            <img src="../<?= $kepala['ttd_cap']; ?>" width="340"
                                style="position:absolute; margin-top:-150px; margin-left:-100px;">
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="3" style="padding-left: 35rem;"><!-- NAMA & NBM -->
                    <?= htmlspecialchars($kepala['nama_kepala']); ?><br>
                    NBM. <?= htmlspecialchars($kepala['nbm_kepala']); ?>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="padding-top: 1rem;">Tembusan kepada Yth :</td>
            </tr>
            <tr>
                <td colspan="5">1. <?= htmlspecialchars($data['tembusan']); ?></td>
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