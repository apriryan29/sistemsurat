<?php
// Memulai session
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Memanggil fungsi dan koneksi database
require_once 'include/config.php';
require_once 'include/functions.php';

$edit_mode = false;
$edit_data = null;
$error_msg = '';
$success_msg = '';
$id_edit = 0;

// Jika ada edit_id di GET, ambil data untuk diedit
if (isset($_GET['edit_id'])) {
    $id_edit = intval($_GET['edit_id']);
    $stmt = $config->prepare("SELECT * FROM tb_dokumen WHERE id_dokumen = ?");
    $stmt->bind_param("i", $id_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
        $edit_mode = true;
    } else {
        $error_msg = "Data dokumen tidak ditemukan.";
    }
    $stmt->close();
}

// Handle form submit untuk tambah atau edit
if (isset($_POST['submit'])) {
    $instansi = trim($_POST['instansi']);
    $tanggal = trim($_POST['tanggal']);
    $kategori = trim($_POST['kategori']);
    $loker = trim($_POST['id_loker']);
    $upload_file_name = $_FILES['nama_file']['name'] ?? '';
    $upload_file_tmp = $_FILES['nama_file']['tmp_name'] ?? '';

    // Validasi input wajib
    if (empty($instansi) || empty($tanggal) || empty($kategori) || (!$edit_mode && empty($upload_file_name))) {
        $error_msg = "Semua Formulir harus diisi!";
    } else {
        if ($edit_mode) {
            // EDIT MODE
            // Jika ada file baru diupload, proses upload dan update nama_file
            if (!empty($upload_file_name)) {
                $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/jpeg', 'image/png'];

                $fileType = mime_content_type($upload_file_tmp);
                $fileSize = $_FILES['nama_file']['size'];

                // Validasi tipe file dan ukuran
                if (!in_array($fileType, $allowedTypes)) {
                    $error_msg = "Tipe file tidak diizinkan. Harap unggah file PDF, DOC, DOCX, PPT, PPTX, XLSX, JPG, atau PNG.";
                } elseif ($fileSize > 10 * 1024 * 1024) { // 10MB
                    $error_msg = "Ukuran file harus kurang dari 10MB.";
                } else {
                    $targetDir = "uploads/";
                    $targetFile = $targetDir . basename($upload_file_name);
                    if (move_uploaded_file($upload_file_tmp, $targetFile)) {
                        // Update termasuk file
                        $stmt = $config->prepare("UPDATE tb_dokumen SET instansi=?, tanggal=?, kategori=?, loker=?, nama_file=? WHERE id_dokumen=?");
                        $stmt->bind_param("sssssi", $instansi, $tanggal, $kategori, $loker, $targetFile, $id_edit);
                    } else {
                        $error_msg = "Unggah file gagal.";
                    }
                }
            } else {
                // Update tanpa mengubah file
                $stmt = $config->prepare("UPDATE tb_dokumen SET instansi=?, tanggal=?, kategori=?, id_loker=? WHERE id_dokumen=?");
                $stmt->bind_param("ssssi", $instansi, $tanggal, $kategori, $loker, $id_edit);
            }

            if (empty($error_msg)) {
                if ($stmt->execute()) {
                    $success_msg = "Data berhasil diperbarui.";
                    $edit_mode = false;
                    $edit_data = null;
                    header("Location: arsipdokumen.php"); // redirect agar refresh tanpa parameter edit_id
                    exit();
                } else {
                    $error_msg = "Gagal memperbarui data: " . htmlspecialchars($stmt->error);
                }
                $stmt->close();
            }
        } else {
            // ADD MODE - Insert data baru
            if (!empty($upload_file_name)) {
                $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/jpeg', 'image/png'];

                $fileType = mime_content_type($upload_file_tmp);
                $fileSize = $_FILES['nama_file']['size'];

                // Validasi tipe file dan ukuran
                if (!in_array($fileType, $allowedTypes)) {
                    $error_msg = "Tipe file tidak diizinkan. Harap unggah file PDF, DOC, DOCX, PPT, PPTX, XLSX, JPG, atau PNG.";
                } elseif ($fileSize > 10 * 1024 * 1024) { // 10MB
                    $error_msg = "Ukuran file harus kurang dari 10MB.";
                } else {
                    $targetDir = "uploads/";
                    $targetFile = $targetDir . basename($upload_file_name);
                    if (move_uploaded_file($upload_file_tmp, $targetFile)) {
                        $stmt = $config->prepare("INSERT INTO tb_dokumen (instansi, tanggal, kategori, loker, nama_file) VALUES (?, ?, ?, ?, ?)");
                        if ($stmt) {
                            $stmt->bind_param("sssss", $instansi, $tanggal, $kategori, $loker, $targetFile);
                            if ($stmt->execute()) {
                                $success_msg = "Data berhasil disimpan.";
                            } else {
                                $error_msg = "Gagal simpan data: " . htmlspecialchars($stmt->error);
                            }
                            $stmt->close();
                        } else {
                            $error_msg = "Persiapan statement gagal: {$config->error}";
                        }
                    } else {
                        $error_msg = "Unggah file gagal.";
                    }
                }
            }
        }
    }
}

// Handle delete action (opsional, dari kode lama)
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $config->prepare("DELETE FROM tb_dokumen WHERE id_dokumen = ?");
    $stmt->bind_param("i", $delete_id);
    
    $stmt->close();
    header("Location: arsipdokumen.php");
    exit();
}
?>

<!-- Memanggil header -->
<?php include 'include/header.php'; ?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-4 page-title"><?= $edit_mode ? "Dokumen" : "Dokumen" ?></h2>
                <div class="card shadow mb-4">

                    <!-- Form Input/Edit Dokumen -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="instansi">Nama Dokumen</label>
                                        <input type="text" id="instansi" name="instansi" class="form-control" placeholder="Nama Dokumen"
                                            value="<?= $edit_mode ? htmlspecialchars($edit_data['instansi']) : '' ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="tanggal">Tanggal</label>
                                        <input class="form-control" id="tanggal" name="tanggal" type="date" placeholder="Tanggal"
                                            value="<?= $edit_mode ? htmlspecialchars($edit_data['tanggal']) : '' ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kategori">Kategori</label>
                                        <select class="form-control" id="kategori" name="kategori" required>
                                            <option value="" <?= !$edit_mode ? 'selected' : '' ?>>Pilih Kategori</option>
                                            <option value="Yayasan" <?= $edit_mode && $edit_data['kategori'] == 'Yayasan' ? 'selected' : '' ?>>Yayasan</option>
                                            <option value="Dinas Pendidikan" <?= $edit_mode && $edit_data['kategori'] == 'Dinas Pendidikan' ? 'selected' : '' ?>>Dinas Pendidikan</option>
                                            <option value="Instansi" <?= $edit_mode && $edit_data['kategori'] == 'Instansi' ? 'selected' : '' ?>>Instansi Pemerintah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="loker">Loker Arsip File</label>
                                        <select class="form-control" id="id_loker" name="id_loker" required>
                                            <option value="" <?= !$edit_mode ? 'selected' : '' ?>>Pilih Kategori</option>
                                            <?php
                                            $query = mysqli_query($config, "SELECT * FROM tb_loker WHERE kategori_loker = 'Loker Arsip Dokumen'");
                                            while ($data = mysqli_fetch_array($query)) {
                                                // Cek apakah mode edit dan apakah id_loker saat ini sama dengan loker yang dipilih
                                                $selected = ($edit_mode && $edit_data['loker'] == $data['loker']) ? 'selected' : '';
                                                echo "<option value='{$data['loker']}' $selected>{$data['loker']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama_file">Unggah File <?= $edit_mode ? '(kosongkan jika tidak ingin mengganti)' : '' ?></label>
                                        <input type="file" id="nama_file" name="nama_file" class="form-control-file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xlsx,.jpg,.jpeg,.png">
                                        <?php if ($edit_mode && !empty($edit_data['nama_file'])): ?>
                                            <small>File saat ini: <a href="<?= htmlspecialchars($edit_data['nama_file']) ?>" target="_blank">Lihat</a></small>
                                        <?php endif; ?>
                                        <p><i>Disarankan mengunggah dengan format file dokumen<br> (pdf, docx, doc, png, atau jpg).</i></p>
                                    </div>
                                    <div style="display: flex; justify-content: flex-end; align-items: flex-end;">
                                        <button type="submit" class="btn btn-primary" name="submit"><?= $edit_mode ? "Update" : "Simpan" ?></button>
                                        <?php if ($edit_mode): ?>
                                        <a href="<?php echo strtok($_SERVER["REQUEST_URI"], '?'); ?>" class="btn btn-secondary ml-2">Batal</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Form Input/Edit Dokumen Selesai -->
                </div>

        <!-- Tampilkan pesan error/sukses -->
        <?php if (!empty($msg)): ?>
            <div class='alert alert-success' id="success-msg"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMsg)): ?>
            <div class='alert alert-danger' id="error-msg"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <!-- Tabel data Dokumen -->
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="h5 page-title text-muted">Data Dokumen</h2>
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        id="searchInput" 
                                        class="form-control" 
                                        placeholder="Cari berkas disini..." 
                                        onkeyup="filterTable()">
                                </div>
                                <!-- table -->
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Dokumen</th>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Loker</th>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = mysqli_query($config, "SELECT * FROM tb_dokumen");
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>
                                                    <td>{$no}</td>
                                                    <td>" . htmlspecialchars($row['instansi']) . "</td>
                                                    <td>" . htmlspecialchars($row['tanggal']) . "</td>
                                                    <td>" . htmlspecialchars($row['kategori']) . "</td>
                                                    <td>" . htmlspecialchars($row['id_loker']) . "</td>
                                                    <td><a href='" . htmlspecialchars($row['nama_file']) . "' target='_blank'>Lihat</a></td>
                                                    <td>
                                                        <a class='text-info' href='?edit_id={$row['id_dokumen']}' title='Edit'><i class='fe fe-edit fe-16'></i></a>
                                                        <a class='text-danger' href='?delete_id={$row['id_dokumen']}' title='Hapus' onclick='return confirm(\"Apakah kamu yakin ingin menghapus Dokumen ini?\");'><i class='fe fe-trash-2 fe-16'></i></a>
                                                    </td>
                                                </tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- tabel diakhiri -->
                            </div>
                        </div>
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
</script>

<script>
    // Menyembunyikan pesan setelah 5 detik
    setTimeout(function() {
        const successMsg = document.getElementById('success-msg');
        const errorMsg = document.getElementById('error-msg');
        
        if (successMsg) {
            successMsg.style.display = 'none';
        }
        if (errorMsg) {
            errorMsg.style.display = 'none';
        }
    }, 2000);
</script>