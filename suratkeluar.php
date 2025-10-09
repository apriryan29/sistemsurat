<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}
?>

<?php include 'include/header.php'; ?>

<main role="main" class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4 page-title">Surat Keluar</h2>
        <div class="card shadow mb-4">
            <form action="#">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="example-select">Kategori</label>
                        <select class="form-control" id="example-select">
                            <option selected>Pilih Kategori</option>
                            <option value="sppd">Perjalanan Dinas [SPPD]</option>
                            <option value="undangan">Surat Undangan</option>
                            <option value="tugas">Surat Tugas</option>
                            <option value="sk">Surat Keputusan</option>
                            <option value="pemberitahuan">Surat Pemberitahuan</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="navigateToPage()">Buat</button>
                </div>
            </form>

<!-- Pemanggilan Modal Surat Keluar -->
            <?php include'out/sk.php'?>
            <?php include'out/sppd.php' ?>
            <?php include'out/tahu.php' ?>
            <?php include'out/tugas.php' ?>
            </div>
            
        </div>
    </div>

    <!-- Tabel data Surat Keluar -->
    <div class="row justify-content-center">
        <div class="col-12">
            <h2 class="h5 page-title text-muted">Data Surat Keluar</h2>
            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari berkas disini..." onkeyup="filterTable()">
                            </div>
                            <table class="table datatables" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nomor Surat</th>
                                        <th>Tujuan Surat</th>
                                        <th>Perihal</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data surat keluar akan ditampilkan di sini -->
                                    <!-- Contoh data, ganti dengan data dari database -->
                                    <tr>
                                        <td>1</td>
                                        <td>001/IV.4/A/2025</td>
                                        <td>John Doe</td>
                                        <td>Surat Tugas</td>
                                        <td>tugas</td>
                                        <td>2025-10-01</td>
                                        <td>
                                            <a href="#" class="text-info"><i class="fe fe-edit"></i></a>
                                            <a href="#" class="text-danger" onclick="return confirm('Hapus data?')"><i class="fe fe-trash-2"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'include/footer.php'; ?>

<script>
function navigateToPage() {
    const selectElement = document.getElementById('example-select');
    const selectedValue = selectElement.value;

    switch (selectedValue) {
        case 'sppd':
            $('#sppdModal').modal('show');
            break;
        case 'sk':
            $('#skModal').modal('show');
            break;
        case 'undangan':
            $('#undanganModal').modal('show');
            break;
        case 'tugas':
            $('#tugasModal').modal('show');
            break;
        case 'pemberitahuan':
            $('#pemberitahuanModal').modal('show');
            break;
        default:
            alert('Silakan pilih kategori yang tersedia.');
            break;
    }
}

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