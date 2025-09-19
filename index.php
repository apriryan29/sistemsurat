<?php

  //Mulai session dan halaman paling utama dibuka
  ob_start();
  session_start();

  //cek session
  if(isset($_SESSION['username'])){
    header("Location: ./dashboard.php");
    die();
}
  //memanggil koneksi database dan fungsi
  require_once 'include/config.php';
  require_once 'include/functions.php';
  $config = conn($host, $username, $password, $database);

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>Sistem Surat - SMK</title>

<!-- menampilkan logo pada judul aplikasi -->
<?php
  echo '<link rel="shortcut icon" href="aset/smk.png">';
?>

<!-- css intern halaman utama login -->
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
  * {
    box-sizing: border-box;
  }
  body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,rgb(227, 229, 232));
    display: flex;
    justify-content: center;
    align-items: center;
  }
  #loginContainer {
    background: white;
    width: 380px;
    max-width: 90vw;
    padding: 25px 30px 30px 30px;
    border-radius: 12px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.20);
    color: #333;
  }
  #logo {
    display: block;
    margin: -20px auto -5px;
  }
  img {
    margin: 0 auto;
    padding-top: 20px;
    padding-bottom: 20px;
    width: 100px; 
    height: auto;
  }
  h2 {
    margin-top: 0;
    margin-bottom: 20px;
    font-weight: 600;
    color: #3a3a3a;
    text-align: center;
    font-size: 24px;
  }
  form {
    display: flex;
    flex-direction: column;
  }
  label {
    font-size: 14px;
    margin-bottom: 6px;
    font-weight: 500;
  }
  input[type="text"],
  input[type="password"] {
    padding: 12px 14px;
    margin-bottom: 5px;
    font-size: 16px;
    border-radius: 8px;
    border: 1.5px solid #ccc;
    transition: border-color 0.3s;
  }
  input[type="text"]:focus,
  input[type="password"]:focus {
    outline: none;
    border-color: #4a90e2;
    box-shadow: 0 0 8px #4a90e2aa;
  }
  button[type="submit"] {
    padding: 12px;
    background: #4a90e2;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  button[type="submit"]:hover {
    background: #357ABD;
  }
  .links {
    margin-top: 2px;
    margin-bottom: 28px;
    font-size: 14px;
    text-align: right;
    color: #666;
  }
  .links a {
    color: #4a90e2;
    text-decoration: none;
    font-weight: 600;
    margin: 0 6px;
    transition: text-decoration 0.3s;
  }
  .links a:hover {
    text-decoration: underline;
  }
  .error-message {
    color: #d22;
    font-size: 13px;
    margin-bottom: 10px;
    display: none;
  }
  @media (max-width: 360px) {
    #loginContainer {
      width: 100%;
      padding: 20px;
      border-radius: 0;
      height: 100vh;
      box-shadow: none;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
  }
</style>
</head>
<body>
  <main id="loginContainer" role="main" aria-label="Login Form">
    
<!-- Logo dan Judul Login -->
    <?php echo '<img id="logo" src="aset/smk.png">';?>
    <h2>Login</h2>

<!-- menampilkan login gagal dan kembali ke halaman login -->
    <script>
      window.onload = function(){
        const urlParms = new URLSearchParams(window.location.search);
        if(urlParms.has('error')){
          alert("Login gagal: Username atau Password salah!");
        }
      }
    </script>    
<!-- start isi form login -->
    <form action="login.php" method="POST" id="loginForm" novalidate>
      <label for="username">Username</label>
      <input
        type="text"
        id="username"
        name="username"
        autocomplete="username"
        aria-required="true"
        aria-describedby="usernameError"
        placeholder="Masukkan username"
        required
      />
      <div id="usernameError" class="error-message" role="alert"></div>

      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        autocomplete="current-password"
        aria-required="true"
        aria-describedby="passwordError"
        placeholder="Masukkan kata sandi"
        required
      />
      <div id="passwordError" class="error-message" role="alert"></div>
      <div class="links">
        <a href="#" tabindex="0">Lupa Kata Sandi?</a>
      </div>

      <button type="submit" aria-label="Login ke akun">Masuk</button>
    
    </form>

    <!-- end isi form login -->
  </main>
</body>
</html>