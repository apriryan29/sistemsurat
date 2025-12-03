<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

require_once 'include/functions.php';
require_once 'include/config.php';

$id_kepala = 1;

// ============================
// AMBIL DATA DARI DATABASE
// ============================
$sql = "SELECT * FROM tb_kepala WHERE id_kepala = '$id_kepala'";
$result = $config->query($sql);
$data = $result->fetch_assoc();

// ============================
// PROSES SIMPAN DATA
// ============================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_kepala = $_POST['nama_kepala'];
    $nbm_kepala  = $_POST['nbm_kepala'];

    $uploadDir = "uploads/";

    // Pakai file lama sebagai default
    $ttdPath    = $data['ttd'];
    $ttdCapPath = $data['ttd_cap'];

    // ========= UPLOAD TTD =========
    if (!empty($_FILES['ttd']['name'])) {
        $ext = pathinfo($_FILES['ttd']['name'], PATHINFO_EXTENSION);
        $namaFile = time() . "_ttd." . $ext;
        $ttdPath = $uploadDir . $namaFile;
        move_uploaded_file($_FILES["ttd"]["tmp_name"], $ttdPath);
    }

    // ========= UPLOAD CAP =========
    if (!empty($_FILES['ttd_cap']['name'])) {
        $ext = pathinfo($_FILES['ttd_cap']['name'], PATHINFO_EXTENSION);
        $namaFile = time() . "_cap." . $ext;
        $ttdCapPath = $uploadDir . $namaFile;
        move_uploaded_file($_FILES["ttd_cap"]["tmp_name"], $ttdCapPath);
    }

    // ============================
    // UPDATE DATABASE
    // ============================
    $sql_update = "UPDATE tb_kepala SET
        nama_kepala = '$nama_kepala',
        nbm_kepala  = '$nbm_kepala',
        ttd         = '$ttdPath',
        ttd_cap     = '$ttdCapPath'
        WHERE id_kepala = '$id_kepala'";

    if ($config->query($sql_update) === TRUE) {
        echo "<script>alert('Data berhasil diperbarui');window.location='profildiri.php';</script>";
        exit();
    } else {
        echo "Gagal update: " . $config->error;
    }
}
?>

<?php include 'include/header.php'; ?>

<!-- ==================== KONTEN ==================== -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
            <h2 class="page-title">Profil Kepala Sekolah</h2>
                <div class="card shadow mb-4">
                    <div class="card-header"><strong class="card-title">Data Kepala Sekolah</strong></div>
                        <div class="card-body">
                            <form action="" method="post" id="userForm" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label>Nama Kepala Sekolah</label>
                                <input type="text" name="nama_kepala" class="form-control" value="<?= $data['nama_kepala']; ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label>NBM</label>
                                <input type="text" name="nbm_kepala" class="form-control" value="<?= $data['nbm_kepala']; ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label>Tanda Tangan Saat Ini</label><br>
                                <?php if(!empty($data['ttd'])) { ?>
                                    <img src="<?= $data['ttd']; ?>" width="120">
                                <?php } else { echo "Belum ada"; } ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Upload Tanda Tangan Baru</label>
                                <input type="file" name="ttd" class="form-control-file" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label>Tanda Tangan + Cap Saat Ini</label><br>
                                    <?php if(!empty($data['ttd_cap'])) { ?>
                                        <img src="<?= $data['ttd_cap']; ?>" width="120">
                                    <?php } else { echo "Belum ada"; } ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Upload TTD + Cap Baru</label>
                                <input type="file" name="ttd_cap" class="form-control-file" disabled>
                            </div>
                            <div style="display:flex; justify-content:flex-end;">
                                <button type="button" class="btn btn-primary" id="editButton">Edit</button>
                                <button type="button" class="btn btn-secondary ml-2" id="cancelButton" style="display:none;">Batal</button>
                                <button type="submit" class="btn btn-success ml-2" id="saveButton" style="display:none;">Simpan</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'include/footer.php'; ?>

<!-- ==================== SCRIPT ==================== -->
<script>
const editBtn   = document.getElementById("editButton");
const cancelBtn = document.getElementById("cancelButton");
const saveBtn   = document.getElementById("saveButton");
const inputs    = document.querySelectorAll("#userForm input");

editBtn.onclick = function() {
    inputs.forEach(i => i.removeAttribute("readonly"));
    document.querySelectorAll("input[type=file]").forEach(i => i.disabled = false);
    editBtn.style.display = "none";
    cancelBtn.style.display = "inline-block";
    saveBtn.style.display = "inline-block";
};

cancelBtn.onclick = function() {
    location.reload();
};
</script>
