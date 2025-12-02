<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

require_once 'include/functions.php';
require_once 'include/config.php';

$id_kepala = "1";
$sql = "SELECT * FROM tb_kepala WHERE id_kepala = '$id_kepala'";
$result = $config->query($sql);
$data = $result->fetch_assoc();

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pengguna = $_POST['nama_kepala'];
    $nbm_kepala = $_POST['nbm_kepala'];
    $ttd = $_FILES['ttd'];
    $ttd_cap = $_FILES['ttd_cap'];

    // Menangani unggahan file
    $uploadDir = 'uploads/'; // Folder untuk menyimpan file
    $ttdPath = '';
    $ttdCapPath = '';

    // Mengupdate tanda tangan
    if ($ttd['error'] == 0) {
        $ttdPath = $uploadDir . basename($ttd["name"]);
        move_uploaded_file($ttd["tmp_name"], $ttdPath);
    }

    // Mengupdate tanda tangan dan cap sekolah
    if ($ttd_cap['error'] == 0) {
        $ttdCapPath = $uploadDir . basename($ttd_cap["name"]);
        move_uploaded_file($ttd_cap["tmp_name"], $ttdCapPath);
    }

    // Update data ke database
    $sql_update = "UPDATE tb_kepala SET
        nama_kepala = '$nama_pengguna',
        nbm_kepala = '$nbm_kepala',
        ttd = '$ttdPath',
        ttd_cap = '$ttdCapPath'
        WHERE id_kepala = '$id_kepala'";

    if ($config->query($sql_update) === TRUE) {
        echo "<script>alert('Pembaruan data berhasil tersimpan');</script>";
    } else {
        echo "Error: " . $sql_update . "<br>" . $config->error;
    }
}
?>

<!-- Memanggil header -->
<?php include 'include/header.php'; ?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="page-title">Profil Kepala Sekolah</h2>
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title">Data Kepala Sekolah</strong>
                    </div>
                    <div class="card-body">
                        <form action="profildiri.php" method="post" id="userForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="nama_kepala">Nama Kepala Sekolah</label>
                                        <input type="text" name="nama_kepala" id="nama_kepala" class="form-control" value="<?php echo $data['nama_kepala']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nbm_kepala">NBM</label>
                                        <input type="text" name="nbm_kepala" id="nbm_kepala" class="form-control" value="<?php echo $data['nbm_kepala']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="ttd">Unggah Tanda Tangan</label>
                                        <input type="file" name="ttd" id="ttd" class="form-control-file">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="ttd_cap">Unggah Tanda Tangan dan Cap Sekolah</label>
                                        <input type="file" name="ttd_cap" id="ttd_cap" class="form-control-file">
                                    </div>
                                    <div style="display: flex; justify-content: flex-end;">
                                        <button type="button" class="btn btn-primary" id="editButton">Perbarui</button>
                                        <button type="button" class="btn btn-danger ml-2" id="cancelButton" style="display: none;">Batal</button>
                                        <button class="btn btn-primary ml-2" type="submit" id="saveButton" style="display: none;">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Konten Utama menu Dashboard Selesai-->

<!-- Memanggil footer -->
<?php include 'include/footer.php'; ?>

<script>
// JavaScript untuk mengaktifkan edit mode
document.getElementById('editButton').addEventListener('click', function() {
    const inputs = document.querySelectorAll('#userForm input[type="text"]');
    inputs.forEach(input => {
        input.removeAttribute('readonly');
    });
    document.getElementById('saveButton').style.display = 'inline-block'; // Tampilkan tombol simpan
    document.getElementById('cancelButton').style.display = 'inline-block'; // Tampilkan tombol cancel
    this.style.display = 'none'; // Sembunyikan tombol edit
});

// JavaScript untuk membatalkan pengeditan
document.getElementById('cancelButton').addEventListener('click', function() {
    const inputs = document.querySelectorAll('#userForm input[type="text"]');
    inputs.forEach(input => {
        input.setAttribute('readonly', 'readonly'); // Kembalikan menjadi readonly
    });
    document.getElementById('saveButton').style.display = 'none'; // Sembunyikan tombol simpan
    this.style.display = 'none'; // Sembunyikan tombol cancel
    document.getElementById('editButton').style.display = 'inline-block'; // Tampilkan kembali tombol edit
});
</script>