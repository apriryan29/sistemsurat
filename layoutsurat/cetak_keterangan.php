<?php
    include 'kopsurat.php';
    include '../include/config.php';


    if (!isset($_GET['id'])) {
        die("ID surat tidak dikirim.");
}

    $id = intval($_GET['id']);

    $stmt = $config->prepare("SELECT * FROM tb_keluar WHERE id_keluar = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();



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

    // Format tanggal Indonesia
    $tgl = date('j', strtotime($data['tanggal']));
    $bulan = $bulanIndo[date('n', strtotime($data['tanggal']))];
    $tahun = date('Y', strtotime($data['tanggal']));
    $tanggalFormat = "$tgl $bulan $tahun";
?>

<div style="font-family: 'Times New Roman'; color: black;">
    <div style="text-align: center; margin-top: 4rem;">
        <table style="width: 100%;">
            <tr>
                <td style="font-weight: bold; font-size: 24px; text-align: center;"><u>SURAT KETERANGAN</u></td>
            </tr>
            <tr>
                <td style="font-size: 20px;">NO: 
                    <?= htmlspecialchars($data['nomor_surat']); ?>
                    /III.4.AU /<?= htmlspecialchars($data['kode_surat']); ?>
                    /<?= date('Y', strtotime($data['tanggal'])) ?>
                </td>
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
                <td style="padding-top: 2rem;"><?= htmlspecialchars($data['tujuan']); ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal lahir</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['keterangan']); ?></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['nis']); ?></td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['ortu']); ?></td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['tujuan']); ?></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 1rem;"><?= htmlspecialchars($data['isi']); ?></td>
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
                <?= $tanggalFormat; ?>
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