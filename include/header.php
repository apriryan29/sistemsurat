<?php
$level = $_SESSION['level']; // Pastikan variabel ini sudah diset saat login
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Sistem Surat - SMK</title>
    
    <!-- menampilkan logo pada judul aplikasi -->
    <?php
      echo '<link rel="shortcut icon" href="aset/smk.png">';
    ?>

    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="css/simplebar.css">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="css/feather.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="css/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="css/app-dark.css" id="darkTheme" disabled>
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
        <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
          <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>
        
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
              <i class="fe fe-sun fe-16"></i>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="avatar avatar-sm mt-2">
                <img src="./aset/smk.png" alt="..." class="avatar-img rounded-circle">
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="profil.php">Profil</a>
              <a class="dropdown-item" href="logout.php" onclick="return confirm('Apakah anda yakin akan keluar ?')">Logout</a>
            </div>
          </li>
        </ul>
      </nav>

      <?php if ($level == 'admin'): ?>
      <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
        <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
          <i class="fe fe-x"><span class="sr-only"></span></i>
        </a>
        <nav class="vertnav navbar navbar-light">
          <!-- nav bar -->
          <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./dashboard.php">
              <p><strong>SURAT SMK</strong></p>
            </a>
          </div>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="dashboard.php">
                <i class="fe fe-home fe-16"></i>
                <span class="ml-3 item-text">Dashboard</span>
              </a>
            </li>
          </ul>
          <p class="text-muted nav-heading mt-4 mb-1">
            <span>Menu Utama</span>
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
              <a href="#forms" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="fe fe-file fe-16"></i>
                <span class="ml-3 item-text">Surat</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100" id="forms">
                <li class="nav-item">
                  <a class="nav-link pl-3" href="suratmasuk.php"><span class="ml-1 item-text">Surat Masuk</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="suratkeluar.php"><span class="ml-1 item-text">Surat Keluar</span></a>
                </li>	
              </ul>
            </li>
            <li class="nav-item w-100">
              <a class="nav-link" href="arsipdokumen.php">
                <i class="fe fe-layers fe-16"></i>
                <span class="ml-3 item-text">Arsip Dokumen</span>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a href="#ui-elements" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="fe fe-folder fe-16"></i>
                <span class="ml-3 item-text">Rekap Arsip</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100" id="ui-elements">
                <li class="nav-item">
                  <a class="nav-link pl-3" href="rkmasuk.php"><span class="ml-1 item-text">Rekap Surat Masuk</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="rkkeluar.php"><span class="ml-1 item-text">Rekap Surat Keluar</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="rkdokumen.php"><span class="ml-1 item-text">Rekap Dokumen</span></a>
                </li>
              </ul>
            </li>
          </ul>
          <p class="text-muted nav-heading mt-4 mb-1">
            <span>Pengaturan</span>
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
              <a href="#profile" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="fe fe-user fe-16"></i>
                <span class="ml-3 item-text">Profil</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100" id="profile">
                <a class="nav-link pl-3" href="profil.php"><span class="ml-1">Data Sekolah</span></a>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a href="#layouts" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="fe fe-layout fe-16"></i>
                <span class="ml-3 item-text">Layout Surat</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100" id="layouts">
                <li class="nav-item">
                  <a class="nav-link pl-3" href="daftarinstansi.php"><span class="ml-1 item-text">Daftar Instansi</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="kodesurat.php"><span class="ml-1 item-text">Kode Surat</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="prihal.php"><span class="ml-1 item-text">Data Perihal</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="loker.php"><span class="ml-1 item-text">Loker</span></a>
                </li>
              </ul>
            </li>
          </ul>
          <div class="btn-box w-100 mt-4 mb-1">
            <a href="logout.php" class="btn mb-2 btn-primary btn-lg btn-block" onclick="return confirm('Apakah anda yakin akan keluar ?')">
              <i class="fe fe-log-out fe-12 mx-2"></i><span class="small">Keluar</span>
            </a>

			<p class="text-muted nav-heading mt-4 mb-1">
            <span>Aplikasi versi  1 . 1</span>
          	</p>
          </div>
        </nav>
      </aside>
      <?php elseif ($level == 'kepala'): ?>
      <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
        <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
          <i class="fe fe-x"><span class="sr-only"></span></i>
        </a>
        <nav class="vertnav navbar navbar-light">
          <!-- nav bar -->
          <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./dashboard.php">
              <p><strong>SURAT SMK</strong></p>
            </a>
          </div>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="dashboard.php">
                <i class="fe fe-home fe-16"></i>
                <span class="ml-3 item-text">Dashboard</span>
              </a>
            </li>
          </ul>
          <p class="text-muted nav-heading mt-4 mb-1">
            <span>Menu Utama</span>
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
              <a href="#ui-elements" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="fe fe-folder fe-16"></i>
                <span class="ml-3 item-text">Rekap Arsip</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100" id="ui-elements">
                <li class="nav-item">
                  <a class="nav-link pl-3" href="rkmasuk.php"><span class="ml-1 item-text">Rekap Surat Masuk</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="rkkeluar.php"><span class="ml-1 item-text">Rekap Surat Keluar</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link pl-3" href="rkdokumen.php"><span class="ml-1 item-text">Rekap Dokumen</span></a>
                </li>
              </ul>
            </li>
          </ul>
          <p class="text-muted nav-heading mt-4 mb-1">
            <span>Pengaturan</span>
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
              <a href="#profile" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                <i class="fe fe-user fe-16"></i>
                <span class="ml-3 item-text">Profil</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100" id="profile">
                <a class="nav-link pl-3" href="profildiri.php"><span class="ml-1">Data Pribadi</span></a>
              </ul>
              <ul class="collapse list-unstyled pl-4 w-100" id="profile">
                <a class="nav-link pl-3" href="profil.php"><span class="ml-1">Data Sekolah</span></a>
              </ul>
            </li>
            <li class="nav-item w-100">
              <a class="nav-link" href="tambahuser.php">
                <i class="fe fe-user-plus fe-16"></i>
                <span class="ml-3 item-text">Tambah Pengguna</span>
              </a>
            </li>
          </ul>
          <div class="btn-box w-100 mt-4 mb-1">
            <a href="logout.php" class="btn mb-2 btn-primary btn-lg btn-block" onclick="return confirm('Apakah anda yakin akan keluar ?')">
              <i class="fe fe-log-out fe-12 mx-2"></i><span class="small">Keluar</span>
            </a>

			<p class="text-muted nav-heading mt-4 mb-1">
            <span>Aplikasi versi  1 . 1</span>
          	</p>
          </div>
        </nav>
      </aside>
        
     <?php endif; ?>
    </div> <!-- .wrapper -->
