<?php
session_start();
include 'include/config.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Function to handle adding new instansi
if (isset($_POST['add_loker'])) {
    $loker = $_POST['loker'];
    $kategori = $_POST['kategori_loker'];

    $stmt = $config->prepare("INSERT INTO tb_loker (loker, kategori_loker) VALUES (?, ?)");
    $stmt->bind_param("ss", $loker, $kategori);
    $stmt->execute();
    $stmt->close();
}

// Function to handle editing an existing loker
if (isset($_POST['edit_loker'])) {
    $id = $_POST['id_loker'];
    $loker = $_POST['loker'];
    $kategori = $_POST['kategori_loker'];

    $stmt = $config->prepare("UPDATE tb_loker SET loker = ?, kategori_loker = ? WHERE id_loker = ?");
    $stmt->bind_param("sssi", $loker, $kategori, $id);
    $stmt->execute();
    $stmt->close();
}

// Function to handle deleting an instansi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $config->prepare("DELETE FROM tb_loker WHERE id_loker = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch existing instansi to display in the table
$result = $config->query("SELECT * FROM tb_loker");
?>

<!-- Memanggil header -->
<?php include 'include/header.php'; ?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-4 page-title">Daftar Loker Penyimpanan</h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12"></div>
                        <div class="card shadow mb-4">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kode" onclick="clearModal()">
                                Tambah Loker
                            </button>
                        </div> <!-- /.col -->
                    </div>
                </div>

                <h2 class="h5 page-title text-muted">Data Loker</h2>
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
                                            <th>Nama Loker</th>
                                            <th>Kategori</th>
                                            <th>Total Surat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $row_number = 1; // Initialize row counter [ID LOKER PERLU DIPERBAIKI SUPAYA DAPAT MENAMPILKAN JUMLAH SURAT YANG MENGGUNAKAN LOKER TERSEBUTA]
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row_number}</td>
                                                    <td>{$row['loker']}</td>
                                                    <td>{$row['kategori_loker']}</td>
                                                    <td>{$row['id_loker']}</td>
                                                    <td>
                                                        <ul class='nav'>
                                                            <li class='nav-item'>
                                                                <a class='nav-link text-info my-0' onclick='editInstansi({$row['id_loker']}, \"{$row['loker']}\")'>
                                                                    <i class='fe fe-edit fe-16'></i>
                                                                </a>
                                                            </li>
                                                            <li class='nav-item'>
                                                                <a class='nav-link text-danger my-0' href='?delete={$row['id_loker']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>
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
                                <h5 class="modal-title" id="kodeModalLabel">Formulir Loker Penyimpanan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" id="edit_id" name="id_loker" value="">
                                    <div class="form-group">
                                        <label for="loker">Masukan Nama Instansi</label>
                                        <input type="text" class="form-control" id="loker" name="loker" placeholder="Isi Nama Loker Penyimpanan" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kategori_loker">Kategori</label>
                                        <select class="form-control" id="kategori_loker" name="kategori_loker" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Loker Surat Keluar">Surat Keluar</option>
                                            <option value="Loker Surat Masuk">Surat Masuk</option>
                                            <option value="Loker Arsip Dokumen">Dokumen</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_loker">Simpan</button>
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
function editInstansi(id, lokeri, kategori) {
    // Populate the modal with the current values
    document.getElementById('edit_id').value = id;
    document.getElementById('loker').value = loker;
    document.getElementById('kategori_loker').value = kategori;

    // Change button name for editing
    const updateButton = document.querySelector("button[name='add_loker']");
    updateButton.innerText = 'Update';
    updateButton.name = 'edit_loker'; // Change name for editing

    // Show the modal
    $('#kode').modal('show');
}

function clearModal() {
    document.getElementById('edit_id').value = '';
    document.getElementById('loker').value = '';
    document.getElementById('kategori_loker').value = '';

    // Reset button name for adding
    const addButton = document.querySelector("button[name='edit_loker']");
    addButton.innerText = 'Simpan';
    addButton.name = 'add_loker'; // Change back to add
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