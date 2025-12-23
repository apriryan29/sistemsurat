<?php
session_start();
include 'include/config.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Function to handle adding new instansi
if (isset($_POST['add_instansi'])) {
    $nama_instansi = $_POST['nama_instansi'];
    $alamat = $_POST['alamat'];
    $kategori = $_POST['kategori'];

    $stmt = $config->prepare("INSERT INTO tb_instansi (nama_instansi, alamat, kategori) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama_instansi, $alamat, $kategori);
    $stmt->execute();
    $stmt->close();
}

// Function to handle editing an existing instansi
if (isset($_POST['edit_instansi'])) {
    $id = $_POST['id_instansi'];
    $nama_instansi = $_POST['nama_instansi'];
    $alamat = $_POST['alamat'];
    $kategori = $_POST['kategori'];

    $stmt = $config->prepare("UPDATE tb_instansi SET nama_instansi = ?, alamat = ?, kategori = ? WHERE id_instansi = ?");
    $stmt->bind_param("sssi", $nama_instansi, $alamat, $kategori, $id);
    $stmt->execute();
    $stmt->close();
}

// Function to handle deleting an instansi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $config->prepare("DELETE FROM tb_instansi WHERE id_instansi = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch existing instansi to display in the table
$result = $config->query("SELECT * FROM tb_instansi");
?>

<!-- Memanggil header -->
<?php include 'include/header.php'; ?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-4 page-title">Daftar Instansi</h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12"></div>
                        <div class="card shadow mb-4">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kode" onclick="clearModal()">
                                Tambah Data
                            </button>
                        </div> <!-- /.col -->
                    </div>
                </div>

                <h2 class="h5 page-title text-muted">Data Instansi</h2>
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        id="searchInput" 
                                        class="form-control" 
                                        placeholder="Cari Data Instansi disini...!" 
                                        onkeyup="filterTable()">
                                </div>
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Instansi</th>
                                            <th>Alamat Instansi</th>
                                            <th>Kategori</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $row_number = 1; // Initialize row counter
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row_number}</td>
                                                    <td>{$row['nama_instansi']}</td>
                                                    <td>{$row['alamat']}</td>
                                                    <td>{$row['kategori']}</td>
                                                    <td>
                                                        <ul class='nav'>
                                                            <li class='nav-item'>
                                                                <a class='nav-link text-info my-0' onclick='editInstansi({$row['id_instansi']}, \"{$row['nama_instansi']}\", \"{$row['alamat']}\")'>
                                                                    <i class='fe fe-edit fe-16'></i>
                                                                </a>
                                                            </li>
                                                            <li class='nav-item'>
                                                                <a class='nav-link text-danger my-0' href='?delete={$row['id_instansi']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>
                                                                    <i class='fe fe-trash-2 fe-16'></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>";
                                                $row_number++; // Increment row counter
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal tambah instansi -->
                <div class="modal fade" id="kode" tabindex="-1" role="dialog" aria-labelledby="kodeModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="kodeModalLabel">Formulir Instansi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" id="edit_id" name="id_instansi" value="">
                                    <div class="form-group">
                                        <label for="nama_instansi">Masukan Nama Instansi</label>
                                        <input type="text" class="form-control" id="nama_instansi" name="nama_instansi" placeholder="Isi Nama Instansi" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Masukan Alamat Instansi</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Isi Alamat Instansi" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kategori">Kategori</label>
                                        <select class="form-control" id="kategori" name="kategori" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Yayasan">Yayasan</option>
                                            <option value="Dinas Pendidikan">Dinas Pendidikan</option>
                                            <option value="Instansi Pemerintah">Instansi Pemerintah</option>
                                            <option value="Sekolah">Sekolah</option>
                                            <option value="Lainnya">Lainnya....</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_instansi">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal selesai -->
            </div>
        </div>
    </div>
</main>
<!-- Konten Utama menu Dashboard Selesai-->

<!-- Memanggil footer -->
<?php include 'include/footer.php'; ?>

<script>
function editInstansi(id, nama_instansi, alamat, kategori) {
    // Populate the modal with the current values
    document.getElementById('edit_id').value = id;
    document.getElementById('nama_instansi').value = nama_instansi;
    document.getElementById('alamat').value = alamat;
    document.getElementById('kategori').value = kategori;

    // Change button name for editing
    const updateButton = document.querySelector("button[name='add_instansi']");
    updateButton.innerText = 'Update';
    updateButton.name = 'edit_instansi'; // Change name for editing

    // Show the modal
    $('#kode').modal('show');
}

function clearModal() {
    document.getElementById('edit_id').value = '';
    document.getElementById('nama_instansi').value = '';
    document.getElementById('alamat').value = '';
    document.getElementById('kategori').value = '';

    // Reset button name for adding
    const addButton = document.querySelector("button[name='edit_instansi']");
    addButton.innerText = 'Simpan';
    addButton.name = 'add_instansi'; // Change back to add
}

// Reset the modal when it is closed
$('#kode').on('hidden.bs.modal', function () {
    clearModal(); // Call the clearModal function to reset fields
});

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('dataTable-1');
    const trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) { // Start from 1 to skip header
        const tds = trs[i].getElementsByTagName('td');
        let match = false;

        // Check all relevant columns
        for (let j = 1; j < tds.length - 1; j++) { // -1 to skip action column
            if (tds[j].textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
                break;
            }
        }

        trs[i].style.display = match ? '' : 'none'; // Show or hide row
    }
}
</script>