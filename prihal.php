<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

?>

<!-- Memanggil header -->
	<?php 
		include'include/header.php';
	?>



<!-- Konten Utama menu Dashboard -->
 <main role="main" class="main-content">
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-12">
			<h2 class="mb-4 page-title">Daftar Perihal</h2>
				<div class="card shadow mb-4">

				<!-- Input Data Surat Masuk Mulai -->
				<form action="#"></form>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group mb-3">
								<label for="example-select">Kategori</label>
								<select class="form-control" id="example-select">
									<!-- Pilihan Kategori Surat -->
									<option selected>Pilih Kategori</option>
									<option value="undangan">Surat Undangan</option>
									<option value="tugas">Surat Tugas</option>
									<option value="sk">Surat Keputusan</option>
									<option value="pemberitahuan">Surat Pemberitahuan</option>
									<option value="Lainnya">Lainnya</option>
								</select>
							</div>
							
							<button type="submit" class="btn btn-primary" onclick="navigateToPage()">Tambah Perihal</button>
						</div> <!-- /.col -->
					</form>
				<!-- Input Data Surat Masuk Selesai -->
 
				<!-- model surat Tugas -->
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
								<!-- Form pengisian Surat Tugas -->
								<form>
									<div class="form-group">
										<label for="judul">Judul Template Surat Tugas</label>
										<input type="text" class="form-control" id="judul" placeholder="Masukkan Judul Template">
									</div>
									<div class="form-group">
										<label for="perihal">Tentang Surat Tugas</label>
										<input type="text" class="form-control" id="perihal" placeholder="Masukkan Perihal Surat Tugas">
									</div>
									<div class="form-group">
										<label for="pembuka">Pembuka</label>
										<textarea type="text" class="form-control" id="pembuka" placeholder="Masukkan Pembuka Surat"></textarea>
									</div>
									<div class="form-group">
										<label for="isi">Penutup</label>
										<textarea type="text" class="form-control" id="isi" placeholder="Masukkan Penutup Surat"></textarea>
									</div>
									<div class="form-group">
										<label for="penutup">Penutup</label>
										<textarea type="text" class="form-control" id="penutup" placeholder="Masukkan Penutup Surat"></textarea>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
								<button type="button" class="btn btn-primary">Simpan</button>
							</div>
						</div>
					</div>
				</div>
				<!-- model surat Keputusan SELESAI -->


				<!-- model surat Keputusan -->
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
								<!-- Form pengisian Surat Keputusan -->
								<form>
									<div class="form-group">
										<label for="judul">Judul Template Surat Keputusan</label>
										<input type="text" class="form-control" id="judul" placeholder="Masukkan Judul Template">
									</div>
									<div class="form-group">
										<label for="perihal">Tentang Surat Keputusan</label>
										<input type="text" class="form-control" id="perihal" placeholder="Masukkan Perihal SK">
									</div>
									<div class="form-group">
										<label for="pembuka">Pembuka</label>
										<input type="text" class="form-control" id="pembuka" placeholder="Masukkan Pembuka Surat">
									</div>
									<div class="form-group">
										<label for="perhatian">Memperhatikan</label>
										<textarea type="text" class="form-control" id="perhatian" placeholder="Masukkan Isi Memperhatikan Surat"></textarea>
									</div>
									<div class="form-group">
										<label for="menimbang">Menimbang</label>
										<textarea type="text" class="form-control" id="menimbang" placeholder="Masukkan Isi Menimbang Surat"></textarea>
									</div>
									<div class="form-group">
										<label for="pengingat">Mengingat</label>
										<textarea type="text" class="form-control" id="pengingat" placeholder="Masukkan Isi Mengingat Surat"></textarea>
									</div>
									<div class="form-group">
										<label for="tetap">Ketetapan Poin 2</label>
										<textarea type="text" class="form-control" id="tetap" placeholder="Masukkan Ketetapan"></textarea>
									</div>
									<div class="form-group">
										<label for="tetap">Ketetapan Poin 3</label>
										<textarea type="text" class="form-control" id="tetap" placeholder="Masukkan Ketetapan"></textarea>
									</div>
									<div class="form-group">
										<label for="tetap">Ketetapan Poin 4</label>
										<textarea type="text" class="form-control" id="tetap" placeholder="Masukkan Ketetapan"></textarea>
									</div>
									<div class="form-group">
										<label for="penutup">Penutup</label>
										<input type="text" class="form-control" id="penutup" placeholder="Masukkan Penutup Surat">
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
								<button type="button" class="btn btn-primary">Simpan</button>
							</div>
						</div>
					</div>
				</div>
				<!-- model surat Keputusan SELESAI -->

				
				<!-- model surat Pemberitahuan -->
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
								<!-- Form pengisian Surat Pemberitahuan -->
								<form>
									<div class="form-group">
										<label for="judul">Judul Template Surat Pemberitahuan</label>
										<input type="text" class="form-control" id="judul" placeholder="Masukkan Judul Template">
									</div>
									<div class="form-group">
										<label for="perihal">Tentang Surat Pemberitahuan</label>
										<input type="text" class="form-control" id="perihal" placeholder="Masukkan Perihal">
									</div>
									<div class="form-group">
										<label for="pembuka">Pembuka</label>
										<textarea class="form-control" id="pembuka" placeholder="Masukkan Pembuka Surat"></textarea>
									</div>
									<div class="form-group">
										<label for="isi">Isi Surat</label>
										<textarea type="text" class="form-control" id="isi" placeholder="Masukkan Isi Surat"></textarea>
									</div>
									<div class="form-group">
										<label for="pengingat">Penutup</label>
										<textarea type="text" class="form-control" id="penutup" placeholder="Masukkan Penutup Surat"></textarea>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
								<button type="button" class="btn btn-primary">Simpan</button>
							</div>
						</div>
					</div>
				</div>
				<!-- model surat pemberitahuan SELESAI -->
			</div>
		</div>
	</div>


			<!-- Tabel data Surat Keluar -->
                <h2 class="h5 page-title text-muted">Daftar Perihal</h2>
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        id="searchInput" 
                                        class="form-control" 
                                        placeholder="Cari Data Perihal disini...!" 
                                        onkeyup="filterTable()">
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
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row_number}</td>
                                                    <td>{$row['']}</td>
                                                    <td>
														<a class='text-info' href='?edit_id=" . $row['id_sk'] . "'><i class='fe fe-edit fe-16'></i></a>
														<a class='text-danger ml-2' href='?delete_id=" . $row['id_sk'] . "' onclick='return confirm(\"Apakah kamu yakin ingin menghapus Dokumen ini?\");'><i class='fe fe-trash-2 fe-16'></i></a>
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
				</div> <!-- end section -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
<!-- Tabel data Surat Masuk -->
</main>
<!-- Konten Utama menu Dashboard Selesai-->

<!-- memanggil footer -->
<?php 
    include'include/footer.php';
?>


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
    
    // Menampilkan jendela model sesuai dengan kategori yang dipilih
    
    switch (selectedValue) {
        case 'Dinas Pendidikan':
            pageUrl = '#';
            break;
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