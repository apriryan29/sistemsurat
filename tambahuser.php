<?php
session_start();
include 'include/config.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Hapus pengguna
if (isset($_GET['delete'])) {
    $id_user = $_GET['delete'];
    $query = "DELETE FROM tb_users WHERE id_user = $id_user";
    mysqli_query($config, $query);
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect setelah hapus
}

// Ambil data pengguna untuk ditampilkan
$query = "SELECT * FROM tb_users";
$result = mysqli_query($config, $query);

// Proses update pengguna
if (isset($_POST['update_user'])) {
    $id_user = $_POST['id_user'];
    $nama_pengguna = $_POST['pengguna'];
    $username = $_POST['username'];
    $nbm = $_POST['nbm'];
    $level = $_POST['level'];
    
    // Update kata sandi hanya jika diisi
    $password = $_POST['password'];
    if (!empty($password)) {
        $password;
        $query = "UPDATE tb_users SET username='$username', password='$password', nama_pengguna='$nama_pengguna', nbm='$nbm', level='$level' WHERE id_user='$id_user'";
    } else {
        $query = "UPDATE tb_users SET username='$username', nama_pengguna='$nama_pengguna', nbm='$nbm', level='$level' WHERE id_user='$id_user'";
    }
    
    mysqli_query($config, $query);
    header("Location: " . $_SERVER['PHP_SELF']);
}

// Ambil data pengguna jika mengedit
$user = null;
if (isset($_GET['edit'])) {
    $id_user = $_GET['edit'];
    $query = "SELECT * FROM tb_users WHERE id_user = $id_user";
    $result_edit = mysqli_query($config, $query);
    $user = mysqli_fetch_assoc($result_edit);
}
?>

<!-- Memanggil header -->
<?php include 'include/header.php'; ?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-4 page-title">Data Pengguna</h2>
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        id="searchInput" 
                                        class="form-control" 
                                        placeholder="Cari Data Pengguna disini...!" 
                                        onkeyup="filterTable()">
                                </div>
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Pengguna</th>
                                            <th>NBM</th>
                                            <th>Level</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['nama_pengguna']; ?></td>
                                            <td><?php echo $row['nbm']; ?></td>
                                            <td><?php echo $row['level']; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo $row['password']; ?></td>
                                            <td>
                                                <ul class="nav">
                                                    <li class="nav-item">
                                                        <a class="nav-link text-info my-0" href="?edit=<?php echo $row['id_user']; ?>">
                                                            <i class="fe fe-edit fe-16"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Pengguna -->
                <?php if ($user): ?>
                <div class="modal fade" id="editpengguna" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Pengguna</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">
                                    <div class="form-group">
                                        <label for="pengguna">Masukkan Nama Pengguna</label>
                                        <input type="text" class="form-control" id="pengguna" name="pengguna" value="<?php echo $user['nama_pengguna']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Masukkan Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nbm">Masukkan NBM</label>
                                        <input type="text" class="form-control" id="nbm" name="nbm" value="<?php echo $user['nbm']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="level">Pilih Level</label>
                                        <select class="form-control" id="level" name="level" required>
                                            <option value="kepala" <?php echo $user['level'] == 'kepala' ? 'selected' : ''; ?>>Kepala</option>
                                            <option value="admin" <?php echo $user['level'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Masukkan Kata Sandi Baru (kosongkan jika tidak diubah)</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Kata Sandi Baru">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="update_user">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Modal Edit selesai -->

            </div>
        </div>
    </div>
</main>
<!-- Konten Utama menu Dashboard Selesai-->

<!-- Memanggil footer -->
<?php include 'include/footer.php'; ?>

<script>
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

// Tampilkan modal edit jika ada pengguna yang diedit
<?php if ($user): ?>
$(document).ready(function() {
    $('#editpengguna').modal('show');
});
<?php endif; ?>
</script>