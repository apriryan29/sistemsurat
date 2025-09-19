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

<!-- Konten Utama menu Dashboard -->
 <main role="main" class="main-content">
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-12">
			<h2 class="mb-4 page-title">Surat Keluar</h2>
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
									<option value="SPPD">Perjalanan Dinas [SPPD]</option>
									<option value="undangan">Surat Undangan</option>
									<option value="tugas">Surat Tugas</option>
									<option value="skp">Surat Keputusan Perseorangan</option>
									<option value="pemberitahuan">Surat Pemberitahuan</option>
									<option value="Orang Tua">Pemberitahuan Wali Murid</option>
								</select>
							</div>
							
							<button type="submit" class="btn btn-primary" onclick="navigateToPage()">Buat</button>
						</div> <!-- /.col -->
					</form>
				<!-- Input Data Surat Masuk Selesai -->
 
				<!-- model surat keluar Instansi -->
				<div class="modal fade" id="skModal" tabindex="-1" role="dialog" aria-labelledby="skModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="skModalLabel">Detail Surat Keputusan</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<!-- Form or details for the Instansi category can go here -->
								<form>
									<div class="form-group">
										<label for="kategori">Kategori</label>
										<input type="text" class="form-control" id="kategori" value="Surat Keputusan" readonly>
									</div>
									<div class="form-group">
										<label for="nomor-surat">Nomor Surat</label>
										<input type="text" class="form-control" id="nomor-surat" value="contoh: 001/IV.4/A/2025" readonly>
									</div>
									<div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input class="form-control" id="tanggal" name="tanggal" type="date" value="<?php echo $editMode ? htmlspecialchars($editData['tanggal']) : ''; ?>">
                                    </div>
									<div class="form-group">
										<label for="tujuan">Tujuan</label>
										<input type="text" class="form-control" id="tujuan" placeholder="Masukkan Tujuan">
									</div>
									<div class="form-group">
										<label for="perihal">Perihal</label>
										<input type="text" class="form-control" id="perihal" placeholder="Masukkan Perihal">
									</div>
									<div class="form-group">
										<label for="isi">Isi Surat</label>
										<input type="text" class="form-control" id="isi" placeholder="Masukkan Isi Surat">
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
				<!-- model surat keluar Instansi SELESAI -->
				
				<!-- model surat keluar Yayasan -->
				<div class="modal fade" id="pemberitahuanModal" tabindex="-1" role="dialog" aria-labelledby="pemberitahuanModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="pemberitahuanModalLabel">Detail Surat Yayasan</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<!-- Form or details for the Instansi category can go here -->
								<form>
									<div class="form-group">
										<label for="kategori">Kategori</label>
										<input type="text" class="form-control" id="kategori" value="Surat Pemberitahuan" readonly>
									</div>
									<div class="form-group">
										<label for="nomor-surat">Nomor Surat</label>
										<input type="text" class="form-control" id="nomor-surat" value="contoh: 001/IV.4/A/2025" readonly>
									</div>
									<div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input class="form-control" id="tanggal" name="tanggal" type="date" value="<?php echo $editMode ? htmlspecialchars($editData['tanggal']) : ''; ?>">
                                    </div>
									<div class="form-group">
										<label for="tujuan">Tujuan</label>
										<input type="text" class="form-control" id="tujuan" placeholder="Masukkan Tujuan">
									</div>
									<div class="form-group">
										<label for="perihal">Perihal</label>
										<input type="text" class="form-control" id="perihal" placeholder="Masukkan Perihal">
									</div>
									<div class="form-group">
										<label for="isi">Isi Surat</label>
										<input type="text" class="form-control" id="isi" placeholder="Masukkan Isi Surat">
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
				<!-- model surat keluar Instansi SELESAI -->


<!-- memanggil JS Fungsi MULAI -->
					<script>
						function navigateToPage() {
							const selectElement = document.getElementById('example-select');
							const selectedValue = selectElement.value;
							
							// Menampilkan jendela model sesuai dengan kategori yang dipilih
							
							switch (selectedValue) {
								case 'SPPD':
									pageUrl = '#';
									break;
								case 'Dinas Pendidikan':
									pageUrl = '#';
									break;
								case 'skp':
									$('#skModal').modal('show');
									return;
								case 'pemberitahuan':
									$('#pemberitahuanModal').modal('show');
									return;
								case 'Orang Tua':
									pageUrl = '#';
									break;
								default:
									alert('Silakan pilih kategori yang tersedia.');
									return;
							}
						}
					</script>
<!-- memanggil JS Fungsi END -->

				</div>
			</div>
		</div> <!-- / .card -->


<!-- Tabel data Surat Masuk -->
				<div class="row justify-content-center">
					<div class="col-12">
					<h2 class="h5 page-title text-muted">Data Surat Keluar</h2>
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
							<!-- table -->
							<table class="table datatables" id="dataTable-1">
								<thead>
								<tr>
									<th>No.</th>
									<th>Nomer Surat</th>
									<th>Tujuan Surat</th>
									<th>Perihal</th>
									<th>Kategori</th>
									<th>Tanggal</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<tr>							
									<td>368</td>
									<td>Imani Lara</td>
									<td>(478) 446-9234</td>
									<td>Borland hahajdty adauydajdkjakdjhd</td>
									<td>9022 Suspendisse Rd.</td>
									<td>Jun 8, 2019</td>
									<td>
									<ul class="nav">
										<li class="nav-item">
											<a class="nav-link text-info my-0" href="#">
											<i class="fe fe-edit fe-16"></i>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link text-success my-0" href="./#" data-toggle="modal" data-target=".modal-shortcut">
											<span class="fe fe-printer fe-16"></span>
											</a>
										</li>
										<li class="nav-item nav-notif">
											<a class="nav-link text-danger my-0" href="./#" data-toggle="modal" data-target=".modal-notif">
											<span class="fe fe-trash-2 fe-16"></span>
											</a>
										</li>
										</ul>
									</td>
								</tr>
							</tbody>
						</table>
						</div>
					</div>
					</div> <!-- simple table -->
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
</script>