<?php 
include 'kopsurat.php';
include '../include/config.php';

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
        <p style="font-size: 22px;">Nomor : <?php $nomor = $_POST['nomor_surat']; echo $nomor; ?></p>
    </div>


    <div style="text-align: justify; margin-top: 4rem;">
        <p style="font-size: 22px;">Yang bertanda tangan dibawah ini Kepala SMK Muhammadiyah Sampang
            Kabupaten Cilacap memberi tugas sepenuhnya kepada :</p>
    </div>
    <table style="font-size: 22px; width: 100%; margin-left: 5rem;">
        <tr>
            <td style="padding-right: 4.5rem;">Nama</td>
            <td>:</td>
            <td><?php $pejabat = $_POST['petugas']; echo $pejabat; ?></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td><?php $jabatan = $_POST['jabatan']; echo $jabatan; ?></td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>:</td>
            <td>SMK Muhammadiyah Sampang</td>
        </tr>
    </table>
    <div style="text-align: justify; margin-top: 2rem;">
        <p style="font-size: 22px;">Untuk <?php $keperluan = $_POST['keperluan']; echo $keperluan; ?> pada :</p>
    </div>
    <table style="font-size: 22px; width: 100%; margin-left: 5rem;">
        <tr>
            <td>Hari / Tanggal</td>
            <td>:</td>
            <td><?php echo formatTanggal($_POST['tanggal']); ?></td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td><?php $waktu = $_POST['waktu']; echo $waktu; ?></td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td><?php $tempat = $_POST['tempat']; echo $tempat; ?></td>
        </tr>
    </table>
    <div style="text-align: justify; margin-top: 2rem; margin-bottom: 5rem;">
        <p style="font-size: 22px;">Demikian surat tugas ini kami buat, agar dilaksanakan dan dipergunakan sebagaimana mestinya.</p>
    </div>
    <table>
        <tr>
            <td style="font-size: 22px; text-align: left; padding-right: 5rem;">Yang Diberi Tugas</td>
            <td></td>
            <td style="font-size: 22px; text-align: left; margin-right: 3rem;">Kepala Sekolah</td>
        </tr>
        <tr>
            <td style="font-size: 22px; text-align: left; margin-right: 3rem; padding-top: 6rem;"><?php $pejabat = $_POST['petugas']; echo $pejabat; ?></td>
            <td></td>
            <td style="font-size: 22px; text-align: left; margin-right: 3rem; padding-top: 6rem;">Budi .....MIASUYAUDsna, S.Sos</td>
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