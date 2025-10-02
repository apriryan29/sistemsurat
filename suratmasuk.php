<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

require_once 'include/functions.php';
require_once 'include/config.php';

// Initialize variables
$editMode = false;
$editData = [];
$msg = ""; // Variabel untuk menyimpan pesan sukses
$errorMsg = ""; // Variabel untuk menyimpan pesan kesalahan

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    // Get filename to delete
    $stmt = $config->prepare("SELECT nama_file FROM tb_masuk WHERE id_masuk = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $filenameToDelete = $row['nama_file'];
        // Delete file from server
        if (file_exists($filenameToDelete)) {
            unlink($filenameToDelete);
        }
    }

    $stmt->close(); // Menutup statement setelah digunakan

    $stmtDel = $config->prepare("DELETE FROM tb_masuk WHERE id_masuk = ?");
    $stmtDel->bind_param("i", $delete_id);
    if (!$stmtDel->execute()) {
        $errorMsg = "Gagal menghapus data: " . htmlspecialchars($stmtDel->error);
    } else {
        $msg = "Data berhasil dihapus.";
    }
    $stmtDel->close();
}

// Handle edit request to load data
if (isset($_GET['edit_id'])) {
    $editMode = true;
    $id = intval($_GET['edit_id']);
    $stmt = $config->prepare("SELECT * FROM tb_masuk WHERE id_masuk = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
    $stmt->close(); // Menutup statement setelah digunakan
}

// Handle form submission for add or edit
if (isset($_POST['suratmasuk'])) {
    $nomor = trim($_POST['nomor']);
    $instansi = trim($_POST['instansi']);
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];
    $loker = $_POST['id_loker'];
    $hal = trim($_POST['hal']);
    $id_edit = isset($_POST['id_edit']) ? intval($_POST['id_edit']) : 0;

    if (empty($nomor) || empty($instansi) || empty($tanggal) || empty($kategori) || empty($hal)) {
        $errorMsg = "Semua field harus diisi.";
    } else {
        // Menangani dalam mengunggah file
        $uploadOk = true;
        $targetDir = "uploads/";
        $newFilePath = "";
        
        if (isset($_FILES['nama_file']) && $_FILES['nama_file']['error'] != 4) { // Memilih file
            $fileName = basename($_FILES["nama_file"]["name"]);
            $targetFile = $targetDir . $fileName;

            // Tipe file/dokumen yang dapat diunggah
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (!in_array($fileType, ['jpg', 'png', 'pdf', 'doc', 'docx'])) {
                $errorMsg = "File tidak valid. Hanya file JPG, PNG, PDF, DOC, dan DOCX yang diizinkan.";
                $uploadOk = false;
            } elseif (move_uploaded_file($_FILES["nama_file"]["tmp_name"], $targetFile)) {
                $newFilePath = $targetFile;
            } else {
                $errorMsg = "Gagal mengunggah file.";
                $uploadOk = false;
            }
        }

        if ($uploadOk) {
            if ($id_edit > 0) {
                // Editing existing record
                $fileToUse = $newFilePath !== "" ? $newFilePath : $editData['nama_file'];

                // If new file uploaded, delete old file
                if ($newFilePath !== "" && file_exists($editData['nama_file'])) {
                    unlink($editData['nama_file']);
                }

                $stmt = $config->prepare("UPDATE tb_masuk SET nomor=?, instansi=?, tanggal=?, kategori=?, id_loker=?, hal=?, nama_file=? WHERE id_masuk=?");
                if ($stmt) {
                    $stmt->bind_param("sssssssi", $nomor, $instansi, $tanggal, $kategori, $loker, $hal, $fileToUse, $id_edit);
                    if ($stmt->execute()) {
                        $msg = "Data berhasil diupdate.";
                    } else {
                        $errorMsg = "Gagal simpan data: " . htmlspecialchars($stmt->error);
                    }
                } else {
                    $errorMsg = "Gagal mempersiapkan statement: " . htmlspecialchars($config->error);
                }
                $stmt->close();
            } else {
                // Insert new record
                if ($newFilePath === "") {
                    $errorMsg = "File harus diunggah untuk data baru.";
                    $uploadOk = false;
                } else {
                    $stmt = $config->prepare("INSERT INTO tb_masuk (nomor, instansi, tanggal, kategori, id_loker, hal, nama_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("sssssss", $nomor, $instansi, $tanggal, $kategori, $loker, $hal, $newFilePath);
                        if ($stmt->execute()) {
                            $msg = "Data berhasil disimpan.";
                        } else {
                            $errorMsg = "Gagal simpan data: " . htmlspecialchars($stmt->error);
                        }
                    } else {
                        $errorMsg = "Gagal mempersiapkan statement: " . htmlspecialchars($config->error);
                    }
                    $stmt->close();
                }
            }
        }
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
                <h2 class="mb-4 page-title"><?php echo $editMode ? "Edit Surat Masuk" : "Tambah Surat Masuk"; ?></h2>
                <div class="card shadow mb-4">
                    <!-- Input Data Surat Masuk Mulai -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_edit" value="<?php echo $editMode ? htmlspecialchars($editData['id_masuk']) : ''; ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nomor">Nomor Surat</label>
                                        <input type="text" id="nomor" name="nomor" class="form-control" placeholder="Nomor Surat" value="<?php echo $editMode ? htmlspecialchars($editData['nomor']) : ''; ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="instansi">Instansi/Yayasan</label>
                                        <input type="text" id="instansi" name="instansi" class="form-control" placeholder="Instansi/Yayasan" value="<?php echo $editMode ? htmlspecialchars($editData['instansi']) : ''; ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="tanggal">Tanggal</label>
                                        <input class="form-control" id="tanggal" name="tanggal" type="date" value="<?php echo $editMode ? htmlspecialchars($editData['tanggal']) : ''; ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kategori">Kategori</label>
                                        <select class="form-control" id="kategori" name="kategori" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Yayasan" <?php if($editMode && $editData['kategori'] == 'Yayasan') echo 'selected'; ?>>Yayasan</option>
                                            <option value="Dinas Pendidikan" <?php if($editMode && $editData['kategori'] == 'Dinas Pendidikan') echo 'selected'; ?>>Dinas Pendidikan</option>
                                            <option value="Instansi" <?php if($editMode && $editData['kategori'] == 'Instansi') echo 'selected'; ?>>Instansi Pemerintah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="hal">Perihal</label>
                                        <input type="text" id="hal" name="hal" class="form-control" placeholder="Perihal" value="<?php echo $editMode ? htmlspecialchars($editData['hal']) : ''; ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="loker">Loker Arsip File</label>
                                        <select class="form-control" id="id_loker" name="id_loker" required>
                                            <option value="" <?= !$editMode ? 'selected' : '' ?>>Pilih Kategori</option>
                                            <?php
                                            $query = mysqli_query($config, "SELECT * FROM tb_loker WHERE kategori_loker = 'Loker Surat Masuk'");
                                            while ($data = mysqli_fetch_array($query)) {
                                                // Cek apakah mode edit dan apakah id_loker saat ini sama dengan loker yang dipilih
                                                $selected = ($editMode && $editData['loker'] == $data['loker']) ? 'selected' : '';
                                                echo "<option value='{$data['loker']}' $selected>{$data['loker']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="file">Unggah File <?php if ($editMode) echo '(kosongkan jika tidak ingin diubah)'; ?></label>
                                        <input type="file" id="nama_file" name="nama_file" class="form-control-file">
                                        <?php if ($editMode && !empty($editData['nama_file'])): ?>
                                            <small>File sekarang: <a href="<?php echo htmlspecialchars($editData['nama_file']); ?>" target="_blank">Lihat</a></small>
                                        <?php endif; ?>
                                        <p><i>Disarankan mengunggah dengan format file dokumen<br> (pdf, docx, doc, png, atau jpg).</i></p>
                                    </div>
                                    <div style="display: flex; justify-content: flex-end; align-items: flex-end;">
                                        <button type="submit" class="btn btn-primary" name="suratmasuk"><?php echo $editMode ? "Update" : "Simpan"; ?></button>
                                        <?php if ($editMode): ?>
                                        <a href="<?php echo strtok($_SERVER["REQUEST_URI"], '?'); ?>" class="btn btn-secondary ml-2">Batal</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Input Data Surat Masuk Selesai -->
                </div>
            </div>
        </div>
                
        <!-- Tampilkan pesan di bawah form -->
        <?php if (!empty($msg)): ?>
            <div class='alert alert-success' id="success-msg"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMsg)): ?>
            <div class='alert alert-danger' id="error-msg"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <!-- Tabel data Surat Masuk -->
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="h5 page-title text-muted">Data Surat Masuk</h2>
                <div class="row my-4">
                    <!-- Small table -->
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
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor Surat</th>
                                            <th>Instansi</th>
                                            <th>Perihal</th>
                                            <th>Kategori</th>
                                            <th>Loker</th>
                                            <th>Tanggal</th>
                                            <th>File</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = mysqli_query($config, "SELECT * FROM tb_masuk ORDER BY id_masuk DESC");
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>
                                                <td>{$no}</td>
                                                <td>" . htmlspecialchars($row['nomor']) . "</td>
                                                <td>" . htmlspecialchars($row['instansi']) . "</td>
                                                <td>" . htmlspecialchars($row['hal']) . "</td>
                                                <td>" . htmlspecialchars($row['kategori']) . "</td>
                                                <td>" . htmlspecialchars($row['id_loker']) . "</td>
                                                <td>" . htmlspecialchars($row['tanggal']) . "</td>
                                                <td><a href='" . htmlspecialchars($row['nama_file']) . "' target='_blank'>Lihat</a></td>
                                                <td>
                                                    <a class='text-info' href='?edit_id=" . $row['id_masuk'] . "'><i class='fe fe-edit fe-16'></i></a>
                                                    <a class='text-danger ml-2' href='?delete_id=" . $row['id_masuk'] . "' onclick='return confirm(\"Apakah kamu yakin ingin menghapus Dokumen ini?\");'><i class='fe fe-trash-2 fe-16'></i></a>
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
        <!-- Akhir Tabel -->
    </div> <!-- .container-fluid -->
</main>
<!-- Konten Utama menu Dashboard Selesai-->

<!-- memanggil footer -->
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