<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}
  //memanggil koneksi database dan fungsi
  require_once 'include/config.php';
  require_once 'include/functions.php';
?>

<!-- Memanggil header -->
	<?php 
		include'include/header.php';
    $query = mysqli_query($config, "SELECT * FROM tb_users");

    if ($query) {
        // Mengambil data pengguna
        $user = mysqli_fetch_assoc($query);
        $nama_pengguna = $user['nama_pengguna']; // Sesuaikan dengan nama kolom yang benar
    } else {
        $nama_pengguna = "Pengguna tidak ditemukan"; // Pesan default jika query gagal
    }
	?>

<!-- Konten Utama menu Dashboard -->
	<main role="main" class="main-content">
		<div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
				    <h2 class="h2 mb-5 page-title">Selamat Datang di Sistem Persuratan <br>SMK Muhammadiyah Sampang!</h2> <!-- Tambahkan Nama pengguna -->  

<!-- Card Rekap data aplikasi mulai -->
                <!-- info small box -->
              <div class="row">
                <div class="col-md-4 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <span class="h2 text-primary mb-0">
                            <?php  
                            //Panggil id masuk yang tersedia
                              $sql = $config->query("SELECT COUNT(id_masuk) as masuk from tb_masuk");
                                while ($data= $sql->fetch_assoc()) {
                                  $jml=$data['masuk'];

                                  echo $jml;
                              }
                            ?>
                          </span>
                          <p class="small mb-4"><strong> Surat Masuk</strong></p>
                        </div>
                        <div class="col-auto">
                          <span class="fe fe-32 fe-sunset text-primary mb-0"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <span class="h2 text-success mb-0">0</span>
                          <p class="small mb-4"><strong> Surat Keluar</strong></p>
                        </div>
                        <div class="col-auto">
                          <span class="fe fe-32 fe-sunrise text-success mb-0"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <span class="h2 mb-0 text-warning">
                            <?php  
                            //menampilkan Jumlah Dokumen
                              $sql = $config->query("SELECT COUNT(id_dokumen) as masuk from tb_dokumen");
                                while ($data= $sql->fetch_assoc()) {
                                  $jml=$data['masuk'];

                                  echo $jml;
                              }
                            ?>
                          </span>
                          <p class="small mb-4"><strong> Dokumen</strong></p>
                        </div>
                        <div class="col-auto">
                          <span class="fe fe-32 fe-inbox text-warning mb-0"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> <!-- end section -->
              <!-- widgets -->
            </class>
<!-- card rekap data aplikasi selesai -->
<!-- Watermark SMK -->
           <div class="wrapper">
              <div class="align-items-center h-100 d-flex w-50 mx-auto">
                <div class="mx-auto text-center">
                  <h1 class="display-1 m-0 font-weight-bolder text-muted" style="font-size:30px;">SISTEM PERSURATAN</h1>
                  <h1 class="mb-1 text-muted font-weight-bold">SMK MUHAMMADIYAH SAMPANG</h1>
                  <h6 class="mb-3 text-muted">Jalan Raya Tugu Barat No.24 Sampang, Kabupaten Cilacap</h6>
                </div>
              </div>
            </div>
<!-- Waatermark end -->

        </div>
		</div>
	</main>

<!-- memanggil footer -->
	<?php 
		include'include/footer.php';
	?>