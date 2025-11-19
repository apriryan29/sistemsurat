<?php
    include'kopsurat.php';

$nomor_surat = $_POST['nomor_surat'];
$isi = $_POST['isi'];
$lahir = $_POST['lahir'];
$nama = $_POST['nama'];
$nis = $_POST['nis'];
$sekolah = $_POST['sekolah'];
$ortu = $_POST['ortu'];
$tanggal = $_POST['tanggal'];

?>

<div style="font-family: 'Times New Roman'; color: black;">
    <div style="text-align: center; margin-top: 4rem;">
        <table style="width: 100%;">
            <tr>
                <td style="font-weight: bold; font-size: 24px; text-align: center;"><u>SURAT KETERANGAN</u></td>
            </tr>
            <tr>
                <td style="font-size: 20px;">NO: <?php echo $nomor_surat ?></td>
            </tr>
        </table>

        <table style="width: 93%; font-size: 22px; text-align:justify; margin:2rem;">
            <tr>
                <td colspan="3" style="padding-top: 3rem;">Yang bertanda tangan di bawah ini Kepala SMK Muhammadiirah Sampang Kabupaten Cilacap Propinsi
                Jawa Tengah, menerangkan bahwa :</td>
            </tr>
            <tr>
                <td style="padding-top: 2rem; width: 40%;">Nama</td>
                <td style="padding-top: 2rem;">:</td>
                <td style="padding-top: 2rem;"><?php echo $nama ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal lahir</td>
                <td>:</td>
                <td><?php echo $lahir ?></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td><?php echo $nis ?></td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td>:</td>
                <td><?php echo $ortu ?></td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                <td>:</td>
                <td><?php echo $sekolah ?></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 1rem;"><?php echo $isi ?></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 1rem;">Demikian surat keterangan ini kami buat dengan sebenar - benarnya. untuk dapat dipergunakan
                sebagaimana mestinya.</td>
            </tr>
        </table>
    </div>
    <!-- Tabel Tanda Tangan -->
    <table style="font-size: 22px; width: 95%;">
        <tr>
            <td style="padding-top: 3rem; padding-left: 40rem;">Sampang, 
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
            <td style="padding-left: 40rem;">Kepala Sekolah</td>
        </tr>
        <tr>
            <td style="padding-top: 8rem; padding-left: 40rem;">Budi Martanto, S.S <br>NIP. -</td>
        </tr>
    </table>
</div>