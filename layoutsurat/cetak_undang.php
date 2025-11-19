<?php
    include 'kopsurat.php';
    include '../include/config.php';

    $nomor_surat = $_POST['nomor_surat'];
    $lamp = $_POST['lampiran'];
    $tujuan = $_POST['tujuan'];
    $jam= $_POST['jam'];
    $tempat= $_POST['tempat'];

    if (isset($_POST['tentang'])) {
        $tentangId = $_POST['tentang'];

        // Ambil data tentang berdasarkan ID
        $sql_tentang = "SELECT * FROM tb_perihal WHERE id_perihal = $tentangId";
        $result_tentang = $config->query($sql_tentang);
        $tentangData = $result_tentang->fetch_assoc();
    }
?>

<div style="font-family: 'Times New Roman'; color: black; margin-right: 2rem; margin-left: 1rem;">
<!-- TABEL TANGGAL DAN NOMOR SURAT -->
    <table style="font-size: 22px; width: 100%; ">
        <tr>
            <td style="width: 7%;">Nomor</td>
            <td>:</td>
            <td><?php echo $nomor_surat; ?></td>
            <td style="text-align: end;">Sampang, 
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Ambil tanggal dari input form
                        $tanggalInput = $_POST['tanggal'];

                        // Mengubah string tanggal menjadi format yang diinginkan
                        $tanggal = new DateTime($tanggalInput);
                        
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

                        // Ambil tanggal dan bulan
                        $tanggal1 = $tanggal->format('j'); // Hari
                        $bulan = $bulanIndo[$tanggal->format('n')]; // Bulan
                        $tahun = $tanggal->format('Y'); // Tahun

                        // Format tanggal dalam bahasa Indonesia
                        $tanggalFormat = "$tanggal1 $bulan $tahun";

                        echo $tanggalFormat;
                    }
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
            <td colspan="2"><?php echo $tentangData['judul']; ?></td>
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

    <table style="font-size: 22px; width: 90%; margin-left:5rem; text-align: justify;">
        <tr>
            <td colspan="3" style="padding-top: 3rem;">Bismillahirohmanirrohim <br> Assalamualaikum wr. wb.</td>
        </tr>
        <!-- Pembuka -->
        <tr>
            <td colspan="3" style="padding-top: 1rem; text-indent: 3rem;"><?php echo $tentangData['pembuka']; ?>
            </td>
        </tr>
        <!-- Isi Pembuka -->
        <tr>
            <td colspan="3" style="padding-top: 1rem; text-indent: 3rem;"><?php echo $tentangData['isi']; ?>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 5rem; padding-top: 1rem; width: 35%;">Hari / Tanggal</td>
            <td style="padding-top: 1rem;">:</td>
            <td style="padding-top: 1rem;"> 
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Ambil tanggal dari input form
                        $tanggalInput = $_POST['tanggal1'];

                        // Mengubah string tanggal menjadi format yang diinginkan
                        $tanggal = new DateTime($tanggalInput);

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

                        // Array untuk nama hari dalam bahasa Indonesia
                        $hariIndo = [
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu',
                        ];

                        // Ambil hari, tanggal, dan bulan
                        $hari = $hariIndo[$tanggal->format('l')]; // Hari
                        $tanggal1 = $tanggal->format('j'); // Hari
                        $bulan = $bulanIndo[$tanggal->format('n')]; // Bulan
                        $tahun = $tanggal->format('Y'); // Tahun

                        // Format tanggal dalam bahasa Indonesia
                        $tanggalFormat1 = "$hari, $tanggal1 $bulan $tahun";

                        echo $tanggalFormat1;
                    }
                    ?>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 5rem;">Waktu</td>
            <td>:</td>
            <td><?php echo $jam; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 5rem;">Tempat</td>
            <td>:</td>
            <td><?php echo $tempat; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 5rem;">Acara</td>
            <td>:</td>
            <td><?php echo $tentangData['tentang']; ?></td>
        </tr>
        <!-- Isi Penutup -->
        <tr>
            <td colspan="3" style="padding-top: 1rem; text-indent: 3rem;"><?php echo $tentangData['penutup']; ?>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 1rem;">Jazakumullohu khoiron katsiron <br> Wassalamu'alaikum wr. wb.</td>
        </tr>
    </table>

    
    <!-- Tabel Tanda Tangan -->
    <table style="font-size: 22px; width: 100%;">
        <tr>
            <td style="padding-top: 3rem; padding-left: 45rem;">Kepala Sekolah</td>
        </tr>
        <tr>
            <td style="padding-top: 8rem; padding-left: 45rem;">Budi Martanto, S.S <br>NBM. 1084 462</td>
        </tr>
    </table>
</div>