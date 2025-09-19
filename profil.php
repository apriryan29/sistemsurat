<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

require_once 'include/functions.php';
require_once 'include/config.php';

// Mengambil data sekolah berdasarkan NPSN
$id_sekolah = "1"; // Ganti dengan metode yang sesuai untuk mendapatkan NPSN
$sql = "SELECT * FROM tb_sekolah WHERE id_sekolah = '$id_sekolah'";
$result = $config->query($sql);
$data = $result->fetch_assoc();

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npsn = $_POST['npsn'];
    $nama_sekolah = $_POST['nama_sekolah'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $kepala_sekolah = $_POST['kepala_sekolah'];
    $nip = $_POST['nip'];
    $kecamatan = $_POST['kecamatan'];
    $web = $_POST['web'];
    $email = $_POST['email'];
    $majelis = $_POST['majelis'];
    $yayasan = $_POST['yayasan'];
    $kelompok_kejuruan = $_POST['kelompok_kejuruan'];
    $kode_pos = $_POST['kode_pos'];

    // Update data ke database
    $sql_update = "UPDATE tb_sekolah SET
        npsn = '$npsn',
        nama_sekolah = '$nama_sekolah',
        alamat = '$alamat',
        telepon = '$telepon',
        nama_kepala = '$kepala_sekolah',
        nip = '$nip',
        kecamatan = '$kecamatan',
        web = '$web',
        email = '$email',
        majelis = '$majelis',
        yayasan = '$yayasan',
        kelompok = '$kelompok_kejuruan',
        kode_pos = '$kode_pos'
        WHERE id_sekolah = '$id_sekolah'";

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
                <h2 class="page-title">Profil</h2>
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title">Data Sekolah</strong>
                    </div>
                    <div class="card-body">
                        <form action="profil.php" method="post" enctype="multipart/form-data" id="schoolForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="npsn">NPSN</label>
                                        <input type="text" name="npsn" id="npsn" class="form-control" value="<?php echo $data['npsn']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama_sekolah">Nama Sekolah</label>
                                        <input type="text" name="nama_sekolah" id="nama_sekolah" class="form-control" placeholder="Masukan Nama Sekolah" value="<?php echo $data['nama_sekolah']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="alamat">Alamat</label>
                                        <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukan Alamat Lengkap" value="<?php echo $data['alamat']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kecamatan">Kecamatan</label>
                                        <input type="text" name="kecamatan" id="kecamatan" class="form-control" placeholder="Masukan Kecamatan" value="<?php echo $data['kecamatan']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="telepon">Telepon</label>
                                        <input type="text" name="telepon" id="telepon" class="form-control" placeholder="Masukan nomor Telepon" value="<?php echo $data['telepon']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kepala_sekolah">Kepala Sekolah</label>
                                        <input type="text" name="kepala_sekolah" id="kepala_sekolah" class="form-control" placeholder="Masukan Nama Kepala Sekolah" value="<?php echo $data['nama_kepala']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nip">NIP / NBM</label>
                                        <input type="text" name="nip" id="nip" class="form-control" placeholder="Masukan NIP atau NBM Kepala Sekolah" value="<?php echo $data['nip']; ?>" readonly>
                                    </div>
                                </div> <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $data['email']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="web">Website</label>
                                        <input type="text" name="web" id="web" class="form-control" placeholder="Website" value="<?php echo $data['web']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="majelis">Majelis</label>
                                        <input type="text" name="majelis" id="majelis" class="form-control" placeholder="Masukan Majelis" value="<?php echo $data['majelis']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="yayasan">Yayasan / Instansi</label>
                                        <input type="text" name="yayasan" id="yayasan" class="form-control" placeholder="Masukan Yayasan" value="<?php echo $data['yayasan']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kelompok_kejuruan">Kelompok Kejuruan</label>
                                        <input type="text" name="kelompok_kejuruan" id="kelompok_kejuruan" class="form-control" placeholder="Masukan Kelompok Kejuruan" value="<?php echo $data['kelompok']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kode_pos">Kode Pos</label>
                                        <input type="number" name="kode_pos" id="kode_pos" class="form-control" placeholder="Masukan Kode Pos" value="<?php echo $data['kode_pos']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="logo">Unggah Logo Sekolah</label>
                                        <input type="file" name="logo" id="logo" class="form-control-file">
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
    const inputs = document.querySelectorAll('#schoolForm input[type="text"], #schoolForm input[type="email"], #schoolForm input[type="number"]');
    inputs.forEach(input => {
        input.removeAttribute('readonly');
    });
    document.getElementById('saveButton').style.display = 'inline-block'; // Tampilkan tombol simpan
    document.getElementById('cancelButton').style.display = 'inline-block'; // Tampilkan tombol cancel
    this.style.display = 'none'; // Sembunyikan tombol edit
});

// JavaScript untuk membatalkan pengeditan
document.getElementById('cancelButton').addEventListener('click', function() {
    const inputs = document.querySelectorAll('#schoolForm input[type="text"], #schoolForm input[type="email"], #schoolForm input[type="number"]');
    inputs.forEach(input => {
        input.setAttribute('readonly', 'readonly'); // Kembalikan menjadi readonly
    });
    document.getElementById('saveButton').style.display = 'none'; // Sembunyikan tombol simpan
    this.style.display = 'none'; // Sembunyikan tombol cancel
    document.getElementById('editButton').style.display = 'inline-block'; // Tampilkan kembali tombol edit
});
</script>