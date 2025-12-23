<?php
// EMAIL SUDAH DITENTUKAN
$email = "apririanto125@gmail.com";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Sistem Surat - SMK</title>

<link rel="shortcut icon" href="aset/smk.png">

<style>
*{
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

.lupa-container{
    width: 100%;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f6f9;
}

.lupa-card{
    background: #ffffff;
    width: 100%;
    max-width: 400px;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}

.lupa-card h3{
    margin-bottom: 10px;
    text-align: center;
    color: #333;
}

.lupa-card p{
    text-align: center;
    font-size: 14px;
    color: #555;
}

.lupa-card b{
    display: block;
    text-align: center;
    margin: 10px 0 20px;
    color: #0d6efd;
    word-break: break-all;
}

.lupa-card button{
    width: 100%;
    padding: 12px;
    background: #4a90e2;
    border: none;
    color: #fff;
    font-size: 16px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
}

.lupa-card button:hover{
    background: #357ABD;
}

.lupa-card a{
    display: block;
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
    color: #555;
    text-decoration: none;
}

.lupa-card a:hover{
    text-decoration: underline;
}

#logo{
    display: block;
    margin: -10px auto 10px;
    width: 100px;
}

.error-message{
    display:none;
    margin: 15px auto;
    padding: 12px;
    max-width: 400px;
    border-radius: 8px;
    font-size: 14px;
    text-align: center;
    margin-bottom: 50px;
}
</style>
</head>

<body>

<div class="lupa-container">
  <div class="lupa-card">
    <div id="passwordError" class="error-message"></div>

    <img id="logo" src="aset/smk.png">

    <h3>Lupa Kata Sandi</h3>
    <p>Link reset Kata Sandi akan dikirim ke:</p>
    <b><?= $email ?></b>

    <form method="post" action="proses_lupa_sandi.php">
        <input type="hidden" name="email" value="<?= $email ?>">
        <button type="submit">Kirim Link Reset</button>
    </form>

    <a href="index.php">← Kembali ke Login</a>

  </div>
</div>

<script>
window.onload = function(){
    const params = new URLSearchParams(window.location.search);
    const box = document.getElementById('passwordError');

    if(params.has('success')){
        box.innerHTML = "Link reset password berhasil dikirim ke email.";
        box.style.display = "block";
        box.style.background = "#d1e7dd";
        box.style.color = "#0f5132";

        setTimeout(() => {
            box.style.display = "none";
        }, 3000);
    }

    if(params.has('error')){
        box.innerHTML = "Email tidak ditemukan.";
        box.style.display = "block";
        box.style.background = "#f8d7da";
        box.style.color = "#842029";
    }
}
</script>

</body>
</html>
