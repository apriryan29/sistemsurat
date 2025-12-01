<?php
include 'kopsurat.php';
include '../include/config.php';

// Ambil surat terakhir berdasarkan ID terbesar (surat terakhir yang dimasukkan)
$query = "
SELECT 
    k.nomor_surat,
    k.isi,
    k.lampiran,
    k.tujuan,
    k.tanggal,
    p.tentang,
    p.pembuka,
    p.isi AS isi_perihal,
    p.penutup
FROM tb_keluar k
JOIN tb_perihal p ON k.id_perihal = p.id_perihal
WHERE k.kategori = 'pemberitahuan'
ORDER BY k.id_keluar DESC
LIMIT 1
";

$result = $config->query($query);

if ($result->num_rows == 0) {
    die("Surat pemberitahuan belum tersedia.");
}

$data = $result->fetch_assoc();

// Array nama bulan Indonesia
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

<div style="font-family:'Times New Roman'; color:black; margin-right:2rem; margin-left:1rem;">
<table style="font-size:22px; width:100%;">
<tr>
    <td style="width:7%;">Nomor</td>
    <td>:</td>
    <td><?= htmlspecialchars($data['nomor_surat']); ?></td>
    <td style="text-align:end;">Sampang, <?= $tanggalFormat; ?></td>
</tr>
<tr>
    <td>Lamp</td>
    <td>:</td>
    <td colspan="2"><?= htmlspecialchars($data['lampiran']); ?></td>
</tr>
<tr>
    <td>Perihal</td>
    <td>:</td>
    <td colspan="2"><?= htmlspecialchars($data['tentang']); ?></td>
</tr>
<tr><td colspan="4" style="padding-top:2rem; padding-left:5rem;">Kepada Yth.</td></tr>
<tr><td colspan="4" style="padding-left:5rem;"><?= htmlspecialchars($data['tujuan']); ?></td></tr>
<tr><td colspan="4" style="padding-left:5rem;">Di - Tempat</td></tr>
</table>

<!-- ISI SURAT -->
<table style="font-size:22px; width:90%; margin-left:5rem; text-align:justify;">
<tr>
    <td style="padding-top:3rem;">
        Bismillahirohmanirrohim <br> Assalamualaikum wr. wb.
    </td>
</tr>

<tr>
    <td style="padding-top:1rem; text-indent:3rem;">
        <?= htmlspecialchars($data['pembuka']); ?>
    </td>
</tr>

<tr>
    <td style="padding-top:1rem; text-indent:3rem;">
        <?= htmlspecialchars($data['isi_perihal']); ?>
    </td>
</tr>

<tr>
    <td style="padding-top:1rem;">
        <?= nl2br(htmlspecialchars($data['isi'])); ?>
    </td>
</tr>

<tr>
    <td style="padding-top:1rem; text-indent:3rem;">
        <?= htmlspecialchars($data['penutup']); ?>
    </td>
</tr>

<tr>
    <td style="padding-top:1rem;">
        Jazakumullohu khoiron katsiron <br> Wassalamu'alaikum wr. wb.
    </td>
</tr>
</table>

<!-- TANDA TANGAN -->
<table style="font-size:22px; width:100%;" class="no-break">
<tr>
    <td style="padding-top:3rem; padding-left:45rem;">Kepala Sekolah</td>
</tr>

<tr>
    <td style="padding-top:8rem; padding-left:45rem;">
        Budi Martanto, S.S <br> NBM. 1084 462
    </td>
</tr>
<tr>
    <td style="padding-top:8rem; padding-left:45rem;"></td>
</tr>
</table>
</div>

<style>
@media print {
    .no-break {
        page-break-inside: avoid;
        width: 100%;
    }
    .no-break tr {
        page-break-inside: avoid;
    }
}
</style>
