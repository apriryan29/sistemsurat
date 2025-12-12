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

    // 1. Cari kategori surat
    $sql = $config->prepare("SELECT kategori FROM tb_keluar WHERE id_keluar = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();
    $data = $result->fetch_assoc();
    $sql->close();

    if (!$data) {
        $error_msg = "Data tidak ditemukan.";
        return;
    }

    $kategori = $data['kategori'];

    // 2. Hapus data detail berdasarkan kategori
    if ($kategori == "pemberitahuan") {
        $stmt1 = $config->prepare("DELETE FROM tb_pemberitahuan WHERE id_keluar = ?");
    }
    elseif ($kategori == "undangan") {
        $stmt1 = $config->prepare("DELETE FROM tb_undangan WHERE id_keluar = ?");
    }
    elseif ($kategori == "tugas individu") {
        $stmt1 = $config->prepare("DELETE FROM tb_tugas WHERE id_keluar = ?");
    }
    elseif ($kategori == "sppd") {
        $stmt1 = $config->prepare("DELETE FROM tb_sppd WHERE id_keluar = ?");
    }

    // Eksekusi delete detail
    if (isset($stmt1)) {
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();
    }

    // 3. Hapus data induk tb_keluar
    $stmt = $config->prepare("DELETE FROM tb_keluar WHERE id_keluar = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
            window.location.href = 'suratkeluar.php?deleted=1';
        </script>";
        exit;

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
        COALESCE(
            p.tentang,
            tg.keperluan,
            sp.isi,
            kt.isi
        ) AS tentang
    FROM tb_keluar k
    LEFT JOIN tb_perihal p ON k.id_perihal = p.id_perihal
    LEFT JOIN tb_tugas tg ON k.id_keluar = tg.id_keluar
    LEFT JOIN tb_sppd sp ON k.id_keluar = sp.id_keluar
    LEFT JOIN tb_keterangan kt ON k.id_keluar = kt.id_keluar
    ORDER BY k.id_keluar DESC
");


// PROSES EDIT SURAT
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);

    // Ambil kategori / jenis surat
    $sql = $config->prepare("SELECT kategori FROM tb_keluar WHERE id_keluar = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();
    $data = $result->fetch_assoc();

    $jenis = $data['kategori'];
    // Ambil data lengkap sesuai kategori
    switch ($jenis) {
        case 'pemberitahuan':
            $sql = "
                SELECT k.*, p.* 
                FROM tb_keluar k
                LEFT JOIN tb_pemberitahuan p ON k.id_keluar = p.id_keluar
                WHERE k.id_keluar = ?";
            break;

        case 'undangan':
            $sql = "
                SELECT k.*, u.* 
                FROM tb_keluar k
                LEFT JOIN tb_undangan u ON k.id_keluar = u.id_keluar
                WHERE k.id_keluar = ?";
            break;

        case 'tugas individu':
            $sql = "
                SELECT k.*, t.* 
                FROM tb_keluar k
                LEFT JOIN tb_tugas t ON k.id_keluar = t.id_keluar
                WHERE k.id_keluar = ?";
            break;

        case 'sppd':
            $sql = "
                SELECT k.*, s.* 
                FROM tb_keluar k
                LEFT JOIN tb_sppd s ON k.id_keluar = s.id_keluar
                WHERE k.id_keluar = ?";
            break;

        case 'keterangan':
            $sql = "
                SELECT k.*, kt.* 
                FROM tb_keluar k
                LEFT JOIN tb_keterangan kt ON k.id_keluar = kt.id_keluar
                WHERE k.id_keluar = ?";
            break;

        case 'sk':
            $sql = "
                SELECT k.*, sk.* 
                FROM tb_keluar k
                LEFT JOIN tb_sk sk ON k.id_keluar = sk.id_keluar
                WHERE k.id_keluar = ?";
            break;
    }

    $stmt = $config->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// =========================
// PROSES UPDATE DATA SURAT
// =========================
if (isset($_POST['update_surat'])) {

    $id_keluar = intval($_POST['id_keluar']);
    $kategori  = $_POST['kategori'];

    // UPDATE tb_keluar (kecuali nomor_surat tetap)
    $stmt = $config->prepare("
        UPDATE tb_keluar 
        SET tujuan=?, kode_surat=?, tanggal=?, id_perihal=?, ttd=?
        WHERE id_keluar=?
    ");

    $stmt->bind_param(
        "sssssi",
        $_POST['tujuan'],
        $_POST['kode_surat'],
        $_POST['tanggal'],
        $_POST['id_perihal'],
        $_POST['ttd'],
        $id_keluar
    );
    $stmt->execute();
    $stmt->close();

    // ============================
    // UPDATE TABEL DETAIL SESUAI KATEGORI
    // ============================
    switch ($kategori) {

        case 'undangan':
            $q = $config->prepare("
                UPDATE tb_undangan SET
                    isi_undangan=?, tempat=?, tanggal_acara=?, waktu_acara=?
                WHERE id_keluar=?
            ");
            $q->bind_param(
                "ssssi",
                $_POST['isi_undangan'],
                $_POST['tempat'],
                $_POST['tanggal_acara'],
                $_POST['waktu_acara'],
                $id_keluar
            );
            $q->execute();
            $q->close();
            break;

        case 'pemberitahuan':
            $q = $config->prepare("
                UPDATE tb_pemberitahuan SET isi=? WHERE id_keluar=?
            ");
            $q->bind_param("si", $_POST['isi'], $id_keluar);
            $q->execute();
            $q->close();
            break;

        case 'tugas individu':
            $q = $config->prepare("
                UPDATE tb_tugas SET keperluan=?, tanggal_tugas=? WHERE id_keluar=?
            ");
            $q->bind_param("ssi", $_POST['keperluan'], $_POST['tanggal_tugas'], $id_keluar);
            $q->execute();
            $q->close();
            break;

        case 'sppd':
            $q = $config->prepare("
                UPDATE tb_sppd SET isi=?, lama=?, tujuan_sppd=? WHERE id_keluar=?
            ");
            $q->bind_param("sssi", $_POST['isi'], $_POST['lama'], $_POST['tujuan_sppd'], $id_keluar);
            $q->execute();
            $q->close();
            break;

        case 'keterangan':
            $q = $config->prepare("
                UPDATE tb_keterangan SET isi=? WHERE id_keluar=?
            ");
            $q->bind_param("si", $_POST['isi'], $id_keluar);
            $q->execute();
            $q->close();
            break;

        case 'sk':
            $q = $config->prepare("
                UPDATE tb_sk SET isi=?, dasar=?, penutup=? WHERE id_keluar=?
            ");
            $q->bind_param("sssi", $_POST['isi'], $_POST['dasar'], $_POST['penutup'], $id_keluar);
            $q->execute();
            $q->close();
            break;
    }

    echo "<script>
            alert('Data berhasil diperbarui.');
            window.location.href='suratkeluar.php';
          </script>";
    exit();
}

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

        <?php if (isset($_GET['edit_id'])): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {

        let jenis = "<?php echo $jenis; ?>";
        let modalID = "";

        switch(jenis) {
            case "pemberitahuan": modalID = "#pemberitahuanModal"; break;
            case "undangan":      modalID = "#undangModal"; break;
            case "tugas":         modalID = "#tugasModal"; break;
            case "tugas individu":modalID = "#tugasinModal"; break;
            case "sppd":          modalID = "#sppdModal"; break;
            case "sk":            modalID = "#skModal"; break;
            case "keterangan":    modalID = "#keteranganModal"; break;
        }

        if (modalID !== "") {
            $(modalID).modal("show");

            // Kirim data PHP ke JS
            let data = <?php echo json_encode($data); ?>;

            // Isi semua input dengan nilai lama
            for (let key in data) {
                let input = document.querySelector(modalID + " [name='"+ key +"']");
                if (input) input.value = data[key];
            }
        }
    });
    </script>
    <?php endif; ?>


        <?php if (!empty($msg)): ?>
            <div class="alert alert-success" id="success-msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger" id="error-msg"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>
        <?php
            if (isset($_GET['success_pemberitahuan'])) {
                echo "<div class='alert alert-success' id='success-msg'>Surat berhasil disimpan, menunggu verifikasi sebelum dicetak.</div>";
            }
            if (isset($_GET['success_tugasin'])) {
                echo "<div class='alert alert-success' id='success-msg'>Surat berhasil disimpan, menunggu verifikasi sebelum dicetak.</div>";
            }
            if (isset($_GET['success_undangan'])) {
                echo "<div class='alert alert-success' id='success-msg'>Surat berhasil disimpan, menunggu verifikasi sebelum dicetak.</div>";
            }
        ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class='alert alert-success' id='success-msg'>Data berhasil dihapus.</div>
        <?php endif; ?>

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
                                                case 'tugas individu':$file = 'layoutsurat/cetak_tugasin.php'; break;
                                                case 'sppd':          $file = 'layoutsurat/cetak_sppd.php'; break;
                                                case 'sk':            $file = 'layoutsurat/cetak_sk.php'; break;
                                                case 'keterangan':    $file = 'layoutsurat/cetak_keterangan.php'; break;
                                                default:              $file = null;
                                            }

                                            // BOLEH CETAK JIKA DISETUJUI & FILE ADA
                                            $bolehCetak = ($status_verifikasi === 'disetujui' && $file !== null);

                                            // TOMBOL CETAK
                                            if ($bolehCetak) {
                                                $btnCetak = "<a href='{$file}?id={$row['id_keluar']}' target='_blank' class='btn btn-sm btn-success'>
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
                                    case 'tugas individu':$file='layoutsurat/cetak_tugasin.php'; break;
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

                                // Kepala sekolah SELALU bisa melihat surat apapun statusnya
                                $preview = "
                                    <a href='{$file}?id={$row['id_keluar']}' target='_blank'>
                                        <i class='fe fe-eye'></i>
                                    </a>";


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