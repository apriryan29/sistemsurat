<?php
session_start();
include 'include/config.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Function to handle adding new code
if (isset($_POST['add_kode'])) {
    $kode_surat = $_POST['kode'];
    $pokok_kode = $_POST['pokok'];

    $stmt = $config->prepare("INSERT INTO tb_kode (kode_surat, pokok_kode) VALUES (?, ?)");
    $stmt->bind_param("ss", $kode_surat, $pokok_kode);
    
    $stmt->execute();
    $stmt->close();
}

// Function to handle editing an existing code
if (isset($_POST['edit_kode'])) {
    $id = $_POST['id'];
    $kode_surat = $_POST['kode'];
    $pokok_kode = $_POST['pokok'];

    $stmt = $config->prepare("UPDATE tb_kode SET kode_surat = ?, pokok_kode = ? WHERE id_kode = ?");
    $stmt->bind_param("ssi", $kode_surat, $pokok_kode, $id);
    
    $stmt->execute();
    $stmt->close();
}

// Function to handle deleting a code
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $config->prepare("DELETE FROM tb_kode WHERE id_kode = ?");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $stmt->close();
}

// Fetch existing codes to display in the table
$result = $config->query("SELECT * FROM tb_kode");
?>

<!-- Memanggil header -->
<?php include 'include/header.php'; ?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-4 page-title">Kode Surat </h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12"></div>
                        <div class="card shadow mb-4">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kode" onclick="clearModal()">
                                Tambah Kode
                            </button>
                        </div> <!-- /.col -->
                    </div>
                </div>
                <h2 class="h5 page-title text-muted">Data Kode Surat</h2>
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
                                            <th>Kode</th>
                                            <th>Pokok Kode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $row_number = 1; // Initialize row counter
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row_number}</td> <!-- Display row number -->
                                                    <td>{$row['kode_surat']}</td>
                                                    <td>{$row['pokok_kode']}</td>
                                                    <td>
                                                        <ul class='nav'>
                                                            <li class='nav-item'>
                                                                <a class='nav-link text-info my-0' onclick='editKode({$row['id_kode']}, \"{$row['kode_surat']}\", \"{$row['pokok_kode']}\")'>
                                                                    <i class='fe fe-edit fe-16'></i>
                                                                </a>
                                                            </li>
                                                            <li class='nav-item'>
                                                                <a class='nav-link text-danger my-0' href='?delete={$row['id_kode']}' onclick='return confirm(\"Yakin ingin menghapus?\");'>
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

                <!-- Modal tambah kode -->
                <div class="modal fade" id="kode" tabindex="-1" role="dialog" aria-labelledby="kodeModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="kodeModalLabel">Formulir Kode</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" id="edit_id" name="id" value="">
                                    <div class="form-group">
                                        <label for="kode">Masukan Kode</label>
                                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Isi Kode" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pokok">Masukan Pokok Penggunaan Kode</label>
                                        <input type="text" class="form-control" id="pokok" name="pokok" placeholder="Pokok Kode" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_kode">Simpan</button>
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
function editKode(id, kode, pokok) {
    // Populate the modal with the current values
    document.getElementById('edit_id').value = id;
    document.getElementById('kode').value = kode;
    document.getElementById('pokok').value = pokok;

    // Change button name for editing
    const updateButton = document.querySelector("button[name='add_kode']");
    updateButton.innerText = 'Update';
    updateButton.name = 'edit_kode'; // Change name for editing

    // Show the modal
    $('#kode').modal('show');
}

function clearModal() {
    document.getElementById('edit_id').value = '';
    document.getElementById('kode').value = '';
    document.getElementById('pokok').value = '';

    // Reset button name for adding
    const addButton = document.querySelector("button[name='edit_kode']");
    addButton.innerText = 'Simpan';
    addButton.name = 'add_kode'; // Change back to add
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