
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}
require_once 'include/functions.php';
require_once 'include/config.php';

// Menyimpan template surat
if (isset($_POST['perihal'])) {
    $tentang = $_POST['tentang'];
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $pembuka = $_POST['pembuka'];
    $isi = isset($_POST['isi']) ? $_POST['isi']: '';
    $penutup = $_POST['penutup'];
    $memperhatikan = isset($_POST['memperhatikan']) ? $_POST['memperhatikan'] : '';
    $menimbang = isset($_POST['menimbang']) ? $_POST['menimbang'] : '';
    $mengingat = isset($_POST['mengingat']) ? $_POST['mengingat'] : '';
    $menetapkan_2 = isset($_POST['menetapkan_2']) ? $_POST['menetapkan_2'] : '';
    $menetapkan_3 = isset($_POST['menetapkan_3']) ? $_POST['menetapkan_3'] : '';
    $menetapkan_4 = isset($_POST['menetapkan_4']) ? $_POST['menetapkan_4'] : '';
    $menetapkan_5 = isset($_POST['menetapkan_5']) ? $_POST['menetapkan_5'] : '';

    $stmt = $config->prepare("INSERT INTO tb_perihal (tentang, judul, kategori, pembuka, isi, penutup, memperhatikan, menimbang, mengingat, menetapkan_2, menetapkan_3, menetapkan_4, menetapkan_5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $tentang, $judul, $kategori, $pembuka, $isi, $penutup, $memperhatikan, $menimbang, $mengingat, $menetapkan_2, $menetapkan_3, $menetapkan_4, $menetapkan_5);
    $stmt->execute();
    $stmt->close();
}

// Memanggil header
include 'include/header.php';
?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="mb-4 page-title">Daftar Perihal</h2>
                <div class="card shadow mb-4">

                    <!-- Input Data Surat Masuk Mulai -->
                    <form action="#">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="example-select">Kategori</label>
                                        <select class="form-control" id="example-select">
                                            <option selected>Pilih Kategori</option>
                                            <option value="undangan">Surat Undangan</option>
                                            <option value="tugas">Surat Tugas</option>
                                            <option value="sk">Surat Keputusan</option>
                                            <option value="pemberitahuan">Surat Pemberitahuan</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    
                                    <button type="button" class="btn btn-primary" onclick="navigateToPage()">Tambah Perihal</button>
                                </div> <!-- /.col -->
                            </div>
                        </div>
                    </form>
                    <!-- Input Data Surat Masuk Selesai -->

                    <!-- Modal surat Keputusan (Model Utama) -->
                    <div class="modal fade" id="skModal" tabindex="-1" role="dialog" aria-labelledby="skModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="skModalLabel">Isi Surat Keputusan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="">
                                        <input type="hidden" name="kategori" value="sk">
                                        <div class="form-group">
                                            <label for="judul">Judul Template Surat Keputusan</label>
                                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan Judul Template" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tentang">Tentang Surat Keputusan</label>
                                            <input type="text" class="form-control" id="tentang" name="tentang" placeholder="Masukkan Perihal SK" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="pembuka">Pembuka</label>
                                            <input type="text" class="form-control" id="pembuka" name="pembuka" placeholder="Masukkan Pembuka Surat" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="memperhatikan">Memperhatikan</label>
                                            <textarea class="form-control" id="memperhatikan" name="memperhatikan" placeholder="Masukkan Isi Memperhatikan Surat"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="menimbang">Menimbang</label>
                                            <textarea class="form-control" id="menimbang" name="menimbang" placeholder="Masukkan Isi Menimbang Surat"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="mengingat">Mengingat</label>
                                            <textarea class="form-control" id="mengingat" name="mengingat" placeholder="Masukkan Isi Mengingat Surat"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="menetapkan_2">Ketetapan Poin 2</label>
                                            <textarea class="form-control" id="menetapkan_2" name="menetapkan_2" placeholder="Masukkan Ketetapan"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="menetapkan_3">Ketetapan Poin 3</label>
                                            <textarea class="form-control" id="menetapkan_3" name="menetapkan_3" placeholder="Masukkan Ketetapan"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="menetapkan_4">Ketetapan Poin 4</label>
                                            <textarea class="form-control" id="menetapkan_4" name="menetapkan_4" placeholder="Masukkan Ketetapan"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="penutup">Penutup</label>
                                            <input type="text" class="form-control" id="penutup" name="penutup" placeholder="Masukkan Penutup Surat" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal surat Undangan -->
                    <div class="modal fade" id="undanganModal" tabindex="-1" role="dialog" aria-labelledby="undanganModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="undanganModalLabel">Isi Surat Undangan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="">
                                        <input type="hidden" name="kategori" value="undangan">
                                        <div class="form-group">
                                            <label for="judul">Judul Template Surat Undangan</label>
                                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan Judul Template" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tentang">Tentang Surat Undangan</label>
                                            <input type="text" class="form-control" id="tentang" name="tentang" placeholder="Masukkan Perihal Surat Undangan" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="pembuka">Pembuka</label>
                                            <textarea class="form-control" id="pembuka" name="pembuka" placeholder="Masukkan Pembuka Surat" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="isi">Tengah</label>
                                            <textarea class="form-control" id="isi" name="isi" placeholder="Masukkan Tengah Surat" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="penutup">Penutup</label>
                                            <textarea class="form-control" id="penutup" name="penutup" placeholder="Masukkan Penutup Surat" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal surat Tugas -->
                    <div class="modal fade" id="tugasModal" tabindex="-1" role="dialog" aria-labelledby="tugasModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tugasModalLabel">Isi Surat Tugas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="">
                                        <input type="hidden" name="kategori" value="tugas">
                                        <div class="form-group">
                                            <label for="judul">Judul Template Surat Tugas</label>
                                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan Judul Template" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tentang">Tentang Surat Tugas</label>
                                            <input type="text" class="form-control" id="tentang" name="tentang" placeholder="Masukkan Perihal Surat Tugas" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="pembuka">Pembuka</label>
                                            <textarea class="form-control" id="pembuka" name="pembuka" placeholder="Masukkan Pembuka Surat" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="isi">Isi</label>
                                            <textarea class="form-control" id="isi" name="isi" placeholder="Masukkan Isi Surat" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="penutup">Penutup</label>
                                            <textarea class="form-control" id="penutup" name="penutup" placeholder="Masukkan Penutup Surat" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal surat Pemberitahuan -->
                    <div class="modal fade" id="tahuModal" tabindex="-1" role="dialog" aria-labelledby="tahuModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tahuModalLabel">Isi Surat Pemberitahuan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="">
                                        <input type="hidden" name="kategori" value="pemberitahuan">
                                        <div class="form-group">
                                            <label for="judul">Judul Template Surat Pemberitahuan</label>
                                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan Judul Template" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tentang">Tentang Surat Pemberitahuan</label>
                                            <input type="text" class="form-control" id="tentang" name="tentang" placeholder="Masukkan Perihal" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="pembuka">Pembuka</label>
                                            <textarea class="form-control" id="pembuka" name="pembuka" placeholder="Masukkan Pembuka Surat" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="isi">Isi Surat</label>
                                            <textarea class="form-control" id="isi" name="isi" placeholder="Masukkan Isi Surat" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="penutup">Penutup</label>
                                            <textarea class="form-control" id="penutup" name="penutup" placeholder="Masukkan Penutup Surat" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end card -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->

    <!-- Tabel data Surat Keluar -->
    <h2 class="h5 page-title text-muted">Daftar Perihal</h2>
    <div class="row my-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari Data Perihal disini...!" onkeyup="filterTable()">
                    </div>
                    <table class="table datatables" id="dataTable-1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Daftar Perihal</th>
                                <th>Tentang Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row_number = 1; // Initialize row counter
                            $result = $config->query("SELECT * FROM tb_perihal");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row_number}</td>
                                        <td>{$row['judul']}</td>
                                        <td>{$row['tentang']}</td>
                                        <td>
                                            <a class='text-info' href='?edit_id=" . $row['id_perihal'] . "'><i class='fe fe-edit fe-16'></i></a>
                                            <a class='text-danger ml-2' href='?delete_id=" . $row['id_perihal'] . "' onclick='return confirm(\"Apakah kamu yakin ingin menghapus Dokumen ini?\");'><i class='fe fe-trash-2 fe-16'></i></a>
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

function navigateToPage() {
    const selectElement = document.getElementById('example-select');
    const selectedValue = selectElement.value;
    
    // Menampilkan jendela modal sesuai dengan kategori yang dipili
    
    switch (selectedValue) {
        case 'undangan':
            $('#undanganModal').modal('show');
            return;
        case 'sk':
            $('#skModal').modal('show');
            return;
        case 'tugas':
            $('#tugasModal').modal('show');
            return;
        case 'pemberitahuan':
            $('#tahuModal').modal('show');
            return;
        case 'Lainnya':
            $('#').modal('show');
            return;
        default:
            alert('Silakan pilih kategori yang tersedia.');
            return;
    }
}

</script>