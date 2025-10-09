<?php 
include 'kopsurat.php';
include '../include/config.php';

$nomor_surat = $_POST['nomor_surat'];
$berangkat = $_POST['berangkat'];
$pulang = $_POST['pulang'];
// Fungsi untuk format tanggal Indonesia
function formatTanggal($tanggal) {
    $date = new DateTime($tanggal);
    return $date->format('j ') . bulanIndo($date->format('n')) . $date->format(' Y');
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

function hitungLamaPerjalanan($berangkat, $pulang) {
    $date1 = new DateTime($berangkat);
    $date2 = new DateTime($pulang);
    $diff = $date1->diff($date2);
    return $diff->days; // Mengembalikan selisih dalam hari
}
?>


<div style="font-family: 'Times New Roman'; color: black;  margin-right: 2rem; margin-left: 1rem;">
    <div style="text-align: center; margin-top: 3rem;">
        <p style="font-weight: bold; font-size: 24px;"><u>SURAT PERINTAH PERJALANAN DINAS</u> <br>Nomor : <?php echo $nomor_surat; ?></p>
    </div>

    
    <table style="font-size: 22px; width: 100%; border: 1px solid black; text-align: start; margin-top : 3rem;">
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">01</td>
            <td style="border: 1px solid black; padding: 1rem;">Pejabat yang berwenang memberi perintah</td>
            <td style="border: 1px solid black; padding: 1rem;"><?php $pejabat = $_POST['pejabat']; echo $pejabat; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">02</td>
            <td style="border: 1px solid black; padding: 1rem;">Nama Pegawai yang diperintah</td>
            <td style="border: 1px solid black; padding: 1rem;"><?php $pegawai = $_POST['pegawai']; echo $pegawai; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">03</td>
            <td style="border: 1px solid black; padding: 1rem;">a. Pangkat Golongan <br>b. Jabatan <br>c. Gaji Pokok <br>d. Tingkat menurut peraturan perjalanan dinas</td>
            <td style="border: 1px solid black; padding: 1rem;">a. - <br>b. <?php $jabatan = $_POST['jabatan']; echo $jabatan; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">04</td>
            <td style="border: 1px solid black; padding: 1rem;">Maksud mengadakan perjalanan Dinas</td>
            <td style="border: 1px solid black; padding: 1rem;"><?php $tentang = $_POST['tentang']; echo $tentang; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">05</td>
            <td style="border: 1px solid black; padding: 1rem;">Alat angkut yang digunakan</td>
            <td style="border: 1px solid black; padding: 1rem;"><?php $kendaraan = $_POST['kendaraan']; echo $kendaraan; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">06</td>
            <td style="border: 1px solid black; padding: 1rem;">a. Tempat Berangkat <br>b. Tempat Tujuan</td>
            <td style="border: 1px solid black; padding: 1rem;">a. SMK Muhammadiyah Sampang <br>b. <?php $tempat = $_POST['tempat']; echo $tempat; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">07</td>
            <td style="border: 1px solid black; padding: 1rem;">a. Lama perjalanan dinas <br>b. Tanggal berangkat <br>c. Tanggal harus kembali</td>
            <td style="border: 1px solid black; padding: 1rem;">
                a. <?php $lamaPerjalanan = hitungLamaPerjalanan($berangkat, $pulang); echo $lamaPerjalanan+1; ?> hari <br>
                b. <?php echo formatTanggal($_POST['berangkat']); ?><br>
                c. <?php echo formatTanggal($_POST['pulang']); ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">08</td>
            <td style="border: 1px solid black; padding: 1rem;">Dengan Membawa Pengikut</td>
            <td style="border: 1px solid black; padding: 1rem;"><?php $pengikut = $_POST['pengikut']; echo $pengikut; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">09</td>
            <td style="border: 1px solid black; padding: 1rem;">Pembebanan Anggaran <br>a. Instansi <br>b. Mata anggaran</td>
            <td style="border: 1px solid black; padding: 1rem;">a. SMK Muhammadiyah Sampang <br>b. Dana Sekolah</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">10</td>
            <td style="border: 1px solid black; padding: 1rem;">Keterangan Lain</td>
            <td style="border: 1px solid black; padding: 1rem;"><?php $ket = $_POST['keterangan']; echo $ket; ?></td>
        </tr>
    </table>

    <table style="font-size: 22px; margin-left: 5rem; margin-right: 3rem; margin-top:2rem;">
        <tr>
            <td></td>
            <td style="padding-right: 0;">Dikeluarkan di</td>
            <td>:</td>
            <td>Sampang</td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-right: 0;">Pada Tanggal</td>
            <td>:</td>
            <td><?php echo formatTanggal($_POST['tanggal']); ?></td>
        </tr>
        <tr>
            <td style="padding-right: 21rem;">Pemegang SPPD</td>
            <td colspan="2">Kepala Tata Usaha</td>
        </tr>
            <td style="padding-top: 8rem;"><?php $pegawai = $_POST['pegawai']; echo $pegawai; ?></td>
            <td colspan="2" style="padding-top: 8rem;">(..............................)</td> <!-- perlu penyesuaian nama pejabat -->
        </tr>
    </table>

    <div class="page-break">
        <p style="font-size: 22px; margin-top: 12%">Telah diperiksa dan benar-benar dilaksanakan</p>
        <table style="font-size: 22px; width: 100%; border: 1px solid black;">
            <tr>
                <td  style="width: 50%; border-right: 1px solid black; padding: 1rem;">
                    <table>
                        <tr>
                            <td>Tiba di</td>
                            <td>:</td>
                            <td>SMK YPE Sampang</td>
                        </tr>
                        <tr>
                            <td>Pada Tanggal</td>
                            <td>:</td>
                            <td><?php echo formatTanggal($_POST['berangkat']); ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; padding: 1rem;">
                    <table>
                        <tr>
                            <td>Berangkat dari</td>
                            <td>:</td>
                            <td>SMK Muhammadiyah Sampang</td>
                        </tr>
                        <tr>
                            <td>Ke</td>
                            <td>:</td>
                            <td>SMK YPE Sampang</td>
                        </tr>
                        <tr>
                            <td>Pada Tanggal</td>
                            <td>:</td>
                            <td><?php echo formatTanggal($_POST['berangkat']); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: center; padding-top: 5rem; border-right: 1px solid black;">Pejabat yang memeriksa</td>
                <td style="text-align: center; padding-top: 5rem;">Pejabat yang memeriksa</td>
            </tr>
            <tr>
                <td style="text-align: center; padding-top: 10rem; padding-bottom: 3rem; border-right: 1px solid black;">(....................................)</td>
                <td style="text-align: center; padding-top: 10rem; padding-bottom: 3rem;">(....................................)</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100%; border: 1px solid black; padding: 2rem;">
                    <table>
                        <tr>
                            <td style="padding-right: 5rem;">Tiba kembali di</td>
                            <td style="padding-right: 1rem;">:</td>
                            <td style="padding-right: 20rem;">SMK Muhammadiyah Sampang</td>
                        </tr><tr>
                            <td colspan="3">(Tempat Kedudukan)</td>
                        </tr>
                        <tr>
                            <td>Pada Tanggal</td>
                            <td>:</td>
                            <td><?php echo formatTanggal($_POST['pulang']); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding-top: 3rem;">Pejabat yang berwenang/ <br>Pejabat lainnya yang ditunjuk</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; padding-top: 3rem;">Kepala Tata Usaha</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center;">SMK Muhammadiyah Sampang</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; padding-top: 10rem; color:black;"><strong>SAMINGAN</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center;">NBM. 669 491</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</div>
