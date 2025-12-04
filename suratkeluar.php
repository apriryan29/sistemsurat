<?php
session_start();

include 'include/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

$lavel = $_SESSION['level'];
//inisialisasi variabel
$msg = "";
$error_msg = "";

// PROSES HAPUS SURAT
if ($lavel == 'admin' && isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Prepared Statement untuk keamanan
    $stmt = $config->prepare("DELETE FROM tb_keluar WHERE id_keluar = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $msg = "Data berhasil dihapus";
    } else {
        $error_msg = "Gagal Menghapus data";
    }

    $stmt->close();
}

if ($lavel == 'kepala' && isset($_GET['id']) && isset($_GET['aksi'])) {
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
    SELECT 
        k.*, 
        p.tentang AS tentang
    FROM tb_keluar k
    LEFT JOIN tb_perihal p
      ON k.id_perihal = p.id_perihal
    ORDER BY k.id_keluar DESC
");




?>

<?php include 'include/header.php'; ?>

<?php if ($lavel=='admin'): ?>
<main role="main" class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4 page-title">Surat Keluar</h2>
        <div class="card shadow mb-4">
            <form action="#">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="example-select">Kategori</label>
                        <select class="form-control" id="example-select">
                            <option selected>Pilih Kategori</option>
                            <option value="sppd">Perjalanan Dinas [SPPD]</option>
                            <option value="undangan">Surat Undangan</option>
                            <option value="tugas">Surat Tugas</option>
                            <option value="tugasin">Surat Tugas Individu</option>
                            <option value="sk">Surat Keputusan</option>
                            <option value="keterangan">Surat Keterangan</option>
                            <option value="pemberitahuan">Surat Pemberitahuan</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="navigateToPage()">Buat</button>
                </div>
            </form>

<!-- Pemanggilan Modal Surat Keluar -->
            <?php include'out/sk.php'?>
            <?php include'out/sppd.php' ?>
            <?php include'out/tahu.php' ?>
            <?php include'out/tugas.php' ?>
            <?php include'out/tugasin.php' ?>
            <?php include'out/undang.php' ?>
            <?php include'out/keterangan.php' ?>
            
        </div>
    </div>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-success" id="success-msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger" id="error-msg"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>
        <?php
            if (isset($_GET['success'])) {
                echo "<div class='alert alert-success' id='success-msg'>Surat berhasil disimpan, menunggu verifikasi sebelum dicetak.</div>";
            }
        ?>
    <!-- Tabel data Surat Keluar -->
    <div class="row justify-content-center">
        <div class="col-12">
            <h2 class="h5 page-title text-muted">Data Surat Keluar</h2>
            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari berkas disini..." onkeyup="filterTable()">
                            </div>
                            <table class="table datatables" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nomor Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Perihal</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Cetak</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($query)) {

                                            // AMANKAN DATA NULL
                                            $ttd = $row['ttd'] ?? '';
                                            $status_verifikasi = $row['status_verifikasi'] ?? 'menunggu';

                                            // AUTO SETUJUI JIKA TANPA TTD
                                            if ($ttd === 'Tanpa Tanda Tangan' && $status_verifikasi === 'menunggu') {
                                                mysqli_query($config, "
                                                    UPDATE tb_keluar 
                                                    SET status_verifikasi = 'disetujui' 
                                                    WHERE id_keluar = '{$row['id_keluar']}'
                                                ");
                                                $status_verifikasi = 'disetujui';
                                            }

                                            // BADGE STATUS
                                            if ($status_verifikasi === 'disetujui') {
                                                $status = "<span class='badge badge-success'>DISETUJUI</span>";
                                            } elseif ($status_verifikasi === 'ditolak') {
                                                $status = "<span class='badge badge-danger'>DITOLAK</span>";
                                            } else {
                                                $status = "<span class='badge badge-warning'>MENUNGGU</span>";
                                            }

                                            // FILE CETAK SESUAI KATEGORI
                                            switch ($row['kategori']) {
                                                case 'pemberitahuan': $file = 'layoutsurat/cetak_pemberitahuan.php'; break;
                                                case 'undangan':      $file = 'layoutsurat/cetak_undang.php'; break;
                                                case 'tugas':         $file = 'layoutsurat/cetak_tugas.php'; break;
                                                case 'tugasin':       $file = 'layoutsurat/cetak_tugasin.php'; break;
                                                case 'sppd':          $file = 'layoutsurat/cetak_sppd.php'; break;
                                                case 'sk':            $file = 'layoutsurat/cetak_sk.php'; break;
                                                case 'keterangan':    $file = 'layoutsurat/cetak_keterangan.php'; break;
                                                default:              $file = null;
                                            }

                                            // BOLEH CETAK JIKA DISETUJUI & FILE ADA
                                            $bolehCetak = ($status_verifikasi === 'disetujui' && $file !== null);

                                            // TOMBOL CETAK
                                            if ($bolehCetak) {
                                                $btnCetak = "<a href='{$file}?id={$row['id_keluar']}' class='btn btn-sm btn-success'>
                                                                <i class='fe fe-printer'></i>
                                                            </a>";
                                            } else {
                                                $btnCetak = "<button class='btn btn-sm btn-secondary' disabled>
                                                                <i class='fe fe-lock'></i>
                                                            </button>";
                                            }

                                            // OUTPUT BARIS
                                            echo "<tr>
                                                    <td>{$no}</td>
                                                    <td>".htmlspecialchars($row['nomor_surat'])
                                                        ."/III.4.AU/"
                                                        .htmlspecialchars($row['kode_surat'])
                                                        ."/".date('Y', strtotime($row['tanggal']))."
                                                    </td>
                                                    <td>".htmlspecialchars($row['tujuan'])."</td>
                                                    <td>".($row['tentang'])."</td>
                                                    <td>".htmlspecialchars($row['kategori'])."</td>
                                                    <td>".htmlspecialchars($row['tanggal'])."</td>
                                                    <td class='text-center'>{$status}</td>
                                                    <td class='text-center'>{$btnCetak}</td>
                                                    <td>
                                                        <a class='text-info' href='?edit_id={$row['id_keluar']}'><i class='fe fe-edit fe-16'></i></a>
                                                        <a class='text-danger ml-2' href='?delete_id={$row['id_keluar']}' 
                                                        onclick='return confirm(\"Apakah kamu yakin ingin menghapus Dokumen ini?\");'>
                                                        <i class='fe fe-trash-2 fe-16'></i>
                                                        </a>
                                                    </td>
                                                </tr>";

                                            $no++;
                                        }

                                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php endif; ?>


<?php if ($lavel=='kepala'): ?>
<main role="main" class="main-content">
    <div class="row justify-content-center">
        <div class="col-12">
        <h2 class="page-title">Verifikasi Surat</h2>

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
                                <th>File</th>
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
                                    case 'undangan':      $file='layoutsurat/cetak_undang.php'; break;
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
                                    $preview = "<span <i class='fe fe-lock'></i></span>";
                                } else {
                                    $preview = "<a href='{$file}?id={$row['id_keluar']}'>
                                                <i class='fe fe-eye'></i>
                                                </a>";
                                }

                                $tahun = date('Y', strtotime($row['tanggal']));

                                echo "
                                <tr>
                                    <td>{$no}</td>
                                    <td>{$row['nomor_surat']}/III.4.AU/{$row['kode_surat']}/{$tahun}</td>
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
<?php endif; ?>

<?php include 'include/footer.php'; ?>

<script>
function navigateToPage() {
    const selectElement = document.getElementById('example-select');
    const selectedValue = selectElement.value;

    switch (selectedValue) {
        case 'sppd':
            $('#sppdModal').modal('show');
            break;
        case 'sk':
            $('#skModal').modal('show');
            break;
        case 'undangan':
            $('#undangModal').modal('show');
            break;
        case 'tugas':
            $('#tugasModal').modal('show');
            break;
        case 'tugasin':
            $('#tugasinModal').modal('show');
            break;
        case 'keterangan':
            $('#keteranganModal').modal('show');
            break;
        case 'pemberitahuan':
            $('#pemberitahuanModal').modal('show');
            break;
        default:
            alert('Silakan pilih kategori yang tersedia.');
            break;
    }
}

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('dataTable-1');
    const trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) { // Mulai dari 1 untuk menghindari header
        const tds = trs[i].getElementsByTagName('td');
        let match = false;

        // Cek semua kolom yang relevan
        for (let j = 1; j < tds.length - 1; j++) { // -1 untuk menghindari kolom Aksi
            if (tds[j].textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
                break;
            }
        }

        trs[i].style.display = match ? '' : 'none'; // Tampilkan atau sembunyikan baris
    }
}

setTimeout(function() {
    const successMsg = document.getElementById('success-msg');
    const errorMsg = document.getElementById('error-msg');
    
    if (successMsg) successMsg.style.display = 'none';
    if (errorMsg) errorMsg.style.display = 'none';

}, 3000);

</script>