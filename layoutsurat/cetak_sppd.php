<?php 
include 'kopsurat.php';
include '../include/config.php';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID surat tidak ditemukan.");
}

$id = intval($_GET['id']);

$query = "
SELECT 
    k.nomor_surat,
    k.kode_surat,
    k.tanggal,
    k.tujuan,
    s.berangkat,
    s.pulang,
    s.pejabat,
    s.petugas,
    s.jabatan,
    s.pengikut,
    s.kendaraan,
    s.keterangan,
    s.isi,
    k.ttd,
    k.status_verifikasi
FROM tb_keluar k
JOIN tb_sppd s ON k.id_keluar = s.id_keluar
WHERE k.id_keluar = $id
LIMIT 1
";

$result = $config->query($query);
if ($result->num_rows == 0) {
    die("Surat SPPD belum tersedia.");
}  
$data = $result->fetch_assoc();


// Fungsi ubah tanggal ke format Indonesia
function formatTanggal($tanggal) {
    if (empty($tanggal)) return '-';

    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    if (!$date) return $tanggal;

    return $date->format('j ') . bulanIndo((int)$date->format('n')) . $date->format(' Y');
}

// Nama bulan Indonesia
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

    return $bulanIndo[$bulan] ?? '';
}


function hitungLamaPerjalanan($berangkat, $pulang) {
    $date1 = new DateTime($berangkat);
    $date2 = new DateTime($pulang);
    $diff = $date1->diff($date2);
    return $diff->days; // Mengembalikan selisih dalam hari
}

// Ambil data Kepala Sekolah
$qKepala = $config->query("SELECT * FROM tb_kepala LIMIT 1");
$kepala = $qKepala->fetch_assoc();
$namaKepala = $kepala['nama_kepala'] ?? '-';
$nbmKepala = $kepala['nbm_kepala'] ?? '-';

?>


<div style="font-family: 'Times New Roman'; color: black;  margin-right: 2rem; margin-left: 1rem;">
    <div style="text-align: center; margin-top: 3rem;">
        <p style="font-weight: bold; font-size: 24px;"><u>SURAT PERINTAH PERJALANAN DINAS</u> <br>Nomor : 
        <?= htmlspecialchars($data['nomor_surat']) ; ?>
        / III.4.AU/ <?= htmlspecialchars($data['kode_surat']) ; ?>
        /<?= date('Y', strtotime($data['tanggal'])) ?>
        </p>
    </div>

    
    <table style="font-size: 22px; width: 100%; border: 1px solid black; text-align: start; margin-top : 3rem;">
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">01</td>
            <td style="border: 1px solid black; padding: 1rem;">Pejabat yang berwenang memberi perintah</td>
            <td style="border: 1px solid black; padding: 1rem;">        <?= htmlspecialchars($data['pejabat']) ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">02</td>
            <td style="border: 1px solid black; padding: 1rem;">Nama Pegawai yang diperintah</td>
            <td style="border: 1px solid black; padding: 1rem;"><?= htmlspecialchars($data['petugas']) ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">03</td>
            <td style="border: 1px solid black; padding: 1rem;">a. Pangkat Golongan <br>b. Jabatan <br>c. Gaji Pokok <br>d. Tingkat menurut peraturan perjalanan dinas</td>
            <td style="border: 1px solid black; padding: 1rem;">a. - <br>b. <?= htmlspecialchars($data['jabatan']) ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">04</td>
            <td style="border: 1px solid black; padding: 1rem;">Maksud mengadakan perjalanan Dinas</td>
            <td style="border: 1px solid black; padding: 1rem;"><?= htmlspecialchars($data['isi']) ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">05</td>
            <td style="border: 1px solid black; padding: 1rem;">Alat angkut yang digunakan</td>
            <td style="border: 1px solid black; padding: 1rem;"><?= htmlspecialchars($data['kendaraan']) ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">06</td>
            <td style="border: 1px solid black; padding: 1rem;">a. Tempat Berangkat <br>b. Tempat Tujuan</td>
            <td style="border: 1px solid black; padding: 1rem;">a. SMK Muhammadiyah Sampang <br>b. <?= htmlspecialchars($data['tujuan']) ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">07</td>
            <td style="border: 1px solid black; padding: 1rem;">a. Lama perjalanan dinas <br>b. Tanggal berangkat <br>c. Tanggal harus kembali</td>
            <td style="border: 1px solid black; padding: 1rem;">
                a. <?php $lamaPerjalanan = hitungLamaPerjalanan($data['berangkat'], $data['pulang']); echo $lamaPerjalanan+1; ?> hari <br>
                b. <?= htmlspecialchars($data['berangkat']) ; ?><br>
                c. <?= htmlspecialchars($data['pulang']) ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">08</td>
            <td style="border: 1px solid black; padding: 1rem;">Dengan Membawa Pengikut</td>
            <td style="border: 1px solid black; padding: 1rem;"><?= $data['pengikut'] ; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">09</td>
            <td style="border: 1px solid black; padding: 1rem;">Pembebanan Anggaran <br>a. Instansi <br>b. Mata anggaran</td>
            <td style="border: 1px solid black; padding: 1rem;">a. SMK Muhammadiyah Sampang <br>b. Dana Sekolah</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 1rem;">10</td>
            <td style="border: 1px solid black; padding: 1rem;">Keterangan Lain</td>
            <td style="border: 1px solid black; padding: 1rem;"><?= htmlspecialchars($data['keterangan']) ; ?></td>
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
            <td><?= formatTanggal($data['tanggal']); ?></td>
        </tr>
        <tr>
            <td style="padding-right: 21rem;">Pemegang SPPD</td>
            <td colspan="2"><?= htmlspecialchars($data['pejabat']) ; ?></td>
        </tr>
            <td style="padding-top: 8rem;"><?= htmlspecialchars($data['petugas']) ; ?></td>
            <td colspan="2" style="padding-top: 8rem;">
                <?php
                if ($data['pejabat'] === 'Kepala Sekolah') {
                    echo htmlspecialchars($namaKepala);
                } else {
                    echo "Samingan";
                }
                ?>
            </td> <!-- perlu penyesuaian nama pejabat -->
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
                            <td><?= htmlspecialchars($data['tujuan']) ; ?></td>
                        </tr>
                        <tr>
                            <td>Pada Tanggal</td>
                            <td>:</td>
                            <td><?= formatTanggal($data['berangkat']) ; ?></td>
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
                            <td><?= htmlspecialchars($data['tujuan']) ; ?></td>
                        </tr>
                        <tr>
                            <td>Pada Tanggal</td>
                            <td>:</td>
                            <td><?= formatTanggal($data['berangkat']) ; ?></td>
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
                            <td><?= formatTanggal($data['pulang']) ; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding-top: 3rem;">Pejabat yang berwenang/ <br>Pejabat lainnya yang ditunjuk</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; padding-top: 3rem;"><?= htmlspecialchars($data['pejabat']) ; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center;">SMK Muhammadiyah Sampang</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; padding-top: 10rem; color:black;">
                                <?php
                                    if ($data['pejabat'] === 'Kepala Sekolah') {
                                        echo htmlspecialchars($namaKepala);
                                    } else {
                                        echo "Samingan";
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center;">NBM. 
                                <?php
                                    if ($data['pejabat'] === 'Kepala Sekolah') {
                                        echo htmlspecialchars($nbmKepala);
                                    } else {
                                        echo "669 491";
                                    }
                                ?> 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</div>
