<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

require_once 'include/functions.php';
require_once 'include/config.php';

// Mengambil data pengguna berdasarkan id_user
$id_user = "1"; // Ganti dengan metode yang sesuai untuk mendapatkan ID pengguna
$sql = "SELECT * FROM tb_users WHERE id_user = '$id_user'";
$result = $config->query($sql);
$data = $result->fetch_assoc();

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $nbm = $_POST['nbm'];
    $level = $_POST['level'];
    $password = $_POST['password'];

    // Update data ke database
    $sql_update = "UPDATE tb_users SET
        username = '$username',
        nama_pengguna = '$nama_pengguna',
        nbm = '$nbm',
        level = '$level'";

    // Update password hanya jika diisi
    if (!empty($password)) {
        $sql_update .= ", password = '" . password_hash($password, PASSWORD_DEFAULT) . "'"; // Hash password
    }

    $sql_update .= " WHERE id_user = '$id_user'";

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
                <h2 class="page-title">Profil Pengguna</h2>
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title">Data Pengguna</strong>
                    </div>
                    <div class="card-body">
                        <form action="profil.php" method="post" id="userForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $data['username']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama_pengguna">Nama Pengguna</label>
                                        <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" value="<?php echo $data['nama_pengguna']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nbm">NBM</label>
                                        <input type="text" name="nbm" id="nbm" class="form-control" value="<?php echo $data['nbm']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="level">Level</label>
                                        <input type="text" name="level" id="level" class="form-control" value="<?php echo $data['level']; ?>" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password">Kata Sandi Baru (kosongkan jika tidak diubah)</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi Baru">
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
    const inputs = document.querySelectorAll('#userForm input[type="text"], #userForm input[type="password"]');
    inputs.forEach(input => {
        input.removeAttribute('readonly');
    });
    document.getElementById('saveButton').style.display = 'inline-block'; // Tampilkan tombol simpan
    document.getElementById('cancelButton').style.display = 'inline-block'; // Tampilkan tombol cancel
    this.style.display = 'none'; // Sembunyikan tombol edit
});

// JavaScript untuk membatalkan pengeditan
document.getElementById('cancelButton').addEventListener('click', function() {
    const inputs = document.querySelectorAll('#userForm input[type="text"], #userForm input[type="password"]');
    inputs.forEach(input => {
        input.setAttribute('readonly', 'readonly'); // Kembalikan menjadi readonly
    });
    document.getElementById('saveButton').style.display = 'none'; // Sembunyikan tombol simpan
    this.style.display = 'none'; // Sembunyikan tombol cancel
    document.getElementById('editButton').style.display = 'inline-block'; // Tampilkan kembali tombol edit
});
</script>