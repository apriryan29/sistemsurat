<?php
session_start();
include 'include/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id   = intval($_GET['id']);
    $aksi = $_GET['aksi'];

    if ($aksi == 'setujui') {
        $status = 'disetujui';
    } elseif ($aksi == 'tolak') {
        $status = 'ditolak';
    } else {
        $status = 'menunggu';
    }

    mysqli_query($config, "UPDATE tb_keluar SET status_verifikasi='$status' WHERE id_keluar=$id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


$query = mysqli_query($config, "
    SELECT k.*, p.tentang
    FROM tb_keluar k
    LEFT JOIN tb_perihal p ON k.id_perihal = p.id_perihal
    ORDER BY k.id_keluar DESC
");
?>

<?php include 'include/header.php'; ?>

<main role="main" class="main-content">
<div class="row justify-content-center">
<div class="col-12">

<h2 class="h5 page-title text-muted">Verifikasi Surat</h2>

<div class="card shadow">
<div class="card-body">

<input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari surat..." onkeyup="filterTable()">

<table class="table datatables" id="dataTable-1">
<thead>
<tr>
    <th>No</th>
    <th>No Surat</th>
    <th>Tujuan</th>
    <th>Perihal</th>
    <th>Kategori</th>
    <th>Tanggal</th>
    <th>Dokumen (Preview)</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php
$no = 1;
while ($row = mysqli_fetch_assoc($query)) {

    $ttd    = $row['ttd'] ?? '';
    $status = $row['status_verifikasi'] ?? 'menunggu';


    if ($ttd == 'Tanpa Tanda Tangan' && $status == 'menunggu') {
        mysqli_query($config, "UPDATE tb_keluar SET status_verifikasi='disetujui' WHERE id_keluar='{$row['id_keluar']}'");
        $status = 'disetujui';
    }

    switch ($row['kategori']) {
        case 'pemberitahuan': $file='layoutsurat/cetak_pemberitahuan.php'; break;
        case 'undangan':      $file='layoutsurat/cetak_undangan.php'; break;
        case 'tugas':         $file='layoutsurat/cetak_tugas.php'; break;
        case 'tugasin':       $file='layoutsurat/cetak_tugasin.php'; break;
        case 'sppd':          $file='layoutsurat/cetak_sppd.php'; break;
        case 'sk':            $file='layoutsurat/cetak_sk.php'; break;
        case 'keterangan':    $file='layoutsurat/cetak_keterangan.php'; break;
        default:              $file='#';
    }

    if ($status == 'disetujui') {
        $badge = "<span class='badge badge-success'>DISETUJUI</span>";
    } elseif ($status == 'ditolak') {
        $badge = "<span class='badge badge-danger'>DITOLAK</span>";
    } else {
        $badge = "<span class='badge badge-warning'>MENUNGGU</span>";
    }

    if ($status == 'menunggu') {
        $aksi = "
            <a href='?id={$row['id_keluar']}&aksi=setujui' class='btn btn-sm btn-success'
               onclick=\"return confirm('Setujui surat ini?')\">✔ Setujui</a>

            <a href='?id={$row['id_keluar']}&aksi=tolak' class='btn btn-sm btn-danger'
               onclick=\"return confirm('Tolak surat ini?')\">✖ Tolak</a>
        ";
    } else {
        $aksi = "<i class='fe fe-lock'></i>";
    }

    if ($status == 'disetujui') {
        $preview = "<a href='{$file}?id={$row['id_keluar']}'>
                       <i class='fe fe-eye'></i>
                    </a>";
    } else {
        $preview = "<span <i class='fe fe-lock'></i></span>";
    }

    echo "
    <tr>
        <td>{$no}</td>
        <td>{$row['nomor_surat']}</td>
        <td>{$row['tujuan']}</td>
        <td>{$row['tentang']}</td>
        <td>{$row['kategori']}</td>
        <td>{$row['tanggal']}</td>
        <td class='text-center'>{$preview}</td>
        <td>{$badge}</td>
        <td>{$aksi}</td>
    </tr>
    ";

    $no++;
}
?>

</tbody>
</table>
</div>
</div>
</div>
</div>
</main>

<?php include 'include/footer.php'; ?>

<script>
function filterTable() {
    let f = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#dataTable-1 tbody tr").forEach(tr => {
        tr.style.display = tr.innerText.toLowerCase().includes(f) ? "" : "none";
    });
}
</script>
