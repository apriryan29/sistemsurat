<?php
session_start();
include 'include/config.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Function to handle adding new instansi
if (isset($_POST['add_pegawai'])) {
    $pegawai = $_POST['pegawai'];
    $jabatan = $_POST['jabatan'];

    $stmt = $config->prepare("INSERT INTO tb_pegawai (pegawai, jabatan) VALUES (?, ?)");
    $stmt->bind_param("ss", $pegawai, $jabatan);
    $stmt->execute();
    $stmt->close();
}

// Function to handle editing an existing instansi
if (isset($_POST['edit_pegawai'])) {
    $id = $_POST['id_pegawai'];
    $pegawai = $_POST['pegawai'];
    $jabatan = $_POST['jabatan'];

    $stmt = $config->prepare("UPDATE tb_pegawai SET pegawai = ?, jabatan = ? WHERE id_pegawai = ?");
    $stmt->bind_param("sssi", $pegawai, $jabatan, $id);
    $stmt->execute();
    $stmt->close();
}

// Function to handle deleting an instansi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $config->prepare("DELETE FROM tb_pegawai WHERE id_pegawai = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch existing pegawai to display in the table
$result = $config->query("SELECT * FROM tb_pegawai");
?>

<!-- Memanggil header -->
<?php include 'include/header.php'; ?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-4 page-title">Daftar Pegawai SMK</h2>
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

                <h2 class="h5 page-title text-muted">Data Pegawai</h2>
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        id="searchInput" 
                                        class="form-control" 
                                        placeholder="Cari Data Pegawai disini...!" 
                                        onkeyup="filterTable()">
                                </div>
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Pegawai</th>
                                            <th>Jabatan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $row_number = 1; // Initialize row counter
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row_number}</td>
                                                    <td>{$row['pegawai']}</td>
                                                    <td>{$row['jabatan']}</td>
                                                    <td class='text-center'>
                                                        <a class='text-info' onclick='editInstansi({$row['id_pegawai']}, \"{$row['pegawai']}\", \"{$row['jabatan']}\")'>
                                                            <i class='fe fe-edit fe-16'></i>
                                                        </a>
                                                        <a class='text-danger' href='?delete={$row['id_pegawai']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>
                                                            <i class='fe fe-trash-2 fe-16'></i>
                                                        </a>
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
                                    <input type="hidden" id="edit_id" name="id_pegawai" value="">
                                    <div class="form-group">
                                        <label for="pegawai">Masukan Nama Pegawai</label>
                                        <input type="text" class="form-control" id="pegawai" name="pegawai" placeholder="Isi Nama Pegawai" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan">Masukan Jabatan</label>
                                        <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Isi Jabatan" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_pegawai">Simpan</button>
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
function editInstansi(id, pegawai, jabatan) {
    // Populate the modal with the current values
    document.getElementById('edit_id').value = id;
    document.getElementById('pegawai').value = pegawai;
    document.getElementById('jabatan').value = jabatan;

    // Change button name for editing
    const updateButton = document.querySelector("button[name='add_pegawai']");
    updateButton.innerText = 'Perbarui';
    updateButton.name = 'edit_pegawai'; // Change name for editing

    // Show the modal
    $('#kode').modal('show');
}

function clearModal() {
    document.getElementById('edit_id').value = '';
    document.getElementById('pegawai').value = '';
    document.getElementById('jabatan').value = '';

    // Reset button name for adding
    const addButton = document.querySelector("button[name='edit_pegawai']");
    addButton.innerText = 'Simpan';
    addButton.name = 'add_pegawai'; // Change back to add
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