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
    p.isi AS isi_perihal,
    p.penutup,
    k.status_verifikasi,
    s.keperluan,
    s.waktu,
    s.petugas,
    s.jabatan,
    s.keterangan
FROM tb_keluar k
JOIN tb_perihal p ON k.id_perihal = p.id_perihal
LEFT JOIN tb_tugas s ON k.id_keluar = s.id_keluar
WHERE k.id_keluar = $id LIMIT 1
";

$result = $config->query($query);
if ($result->num_rows == 0){
    die("Surat Tugas Individu belum tersedia");
}
$data = $result->fetch_assoc();

// Fungsi untuk format tanggal Indonesia
function formatTanggal($tanggal) {
    $date = new DateTime($tanggal);
    return hariIndo ($date->format('l')) . $date->format('j ') . bulanIndo($date->format('n')) . $date->format(' Y');
}

// Fungsi untuk mendapatkan nama bulan dalam bahasa Indonesia
function bulanIndo($bulan) {
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
    return $bulanIndo[$bulan];
}
    
    function hariIndo($hari){
        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'

        ];
}

//ambil data kepala sekolah
$id_kepala = 1;
$qKepala = $config->query("SELECT * FROM tb_kepala WHERE id_kepala = '$id_kepala'");
$kepala = $qKepala->fetch_assoc();
?>

<div style="font-family: 'Times New Roman'; color: black;  margin-left:3rem;  margin-right:2rem;">
    <div style="text-align: right; margin-top: 1rem;">
        <p style=" font-size: 22px;">Sampang, 
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
        </p>
    </div>
    <div  style="text-align: center; margin-top: 4rem;">
        <h3 style="color: black;"><u>SURAT TUGAS</u></h3>
        <p style="font-size: 22px;">Nomor : 
            <?= htmlspecialchars($data['nomor_surat']); ?>
            /III.4.AU /<?= htmlspecialchars($data['kode_surat']); ?>
            /<?= date('Y', strtotime($data['tanggal'])) ?>
        </p>
    </div>


    <div style="text-align: justify; margin-top: 4rem;">
        <p style="font-size: 22px;">Yang bertanda tangan dibawah ini Kepala SMK Muhammadiyah Sampang
            Kabupaten Cilacap memberi tugas sepenuhnya kepada :</p>
    </div>
    <table style="font-size: 22px; width: 100%; margin-left: 5rem;">
        <tr>
            <td style="padding-right: 4.5rem;">Nama</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['petugas']); ?></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['jabatan']); ?></td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>:</td>
            <td>SMK Muhammadiyah Sampang</td>
        </tr>
    </table>
    <div style="text-align: justify; margin-top: 2rem;">
        <p style="font-size: 22px;">Untuk <?= htmlspecialchars($data['keperluan']); ?> pada :</p>
    </div>
    <table style="font-size: 22px; width: 100%; margin-left: 5rem;">
        <tr>
            <td>Hari / Tanggal</td>
            <td>:</td>
            <td><?php echo formatTanggal($data['tanggal']); ?></td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['waktu']); ?></td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['tujuan']); ?></td>
        </tr>
    </table>
    <div style="text-align: justify; margin-top: 2rem; margin-bottom: 5rem;">
        <p style="font-size: 22px;">Demikian surat tugas ini kami buat, agar dilaksanakan dan dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- TANDA TANGAN -->
    <?php
        // Jika TTD ada (apa saja), padding = 0, jika tidak ada = 8rem
        $paddingTTD = ($data['ttd'] == 'Tanda Tangan Saja' || $data['ttd'] == 'Tanda Tangan dan Cap') 
            ? '0' 
            : '8rem';
    ?>
    <table>
        <tr>
            <td style="font-size: 22px; text-align: left; padding-right: 5rem;">Yang Diberi Tugas</td>
            <td></td>
            <td style="font-size: 22px; text-align: left; margin-right: rem;">Kepala Sekolah</td>
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
            <td style="font-size: 22px; text-align: left; margin-right: 3rem;"><?= htmlspecialchars($data['petugas']); ?></td>
            <td></td>
            <td style="font-size: 22px; text-align: left; margin-right: 3rem;">
                <!-- NAMA & NBM -->
                <?= htmlspecialchars($kepala['nama_kepala']); ?><br>
                NBM. <?= htmlspecialchars($kepala['nbm_kepala']); ?>

            </td>
        </tr>
        <tr>
            <td></td>
            <td style="font-size: 22px; text-align: center; padding-top:4rem;">Cap dan Tanda Tangan Instansi Terkait</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-size: 22px; text-align: center; padding-top:8rem;">(.............................)</td>
            <td></td>
        </tr>
    </table>
</div>