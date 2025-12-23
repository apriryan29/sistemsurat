<?php
include 'include/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];

    // Cek email
    $stmt = $config->prepare(
        "SELECT id_user FROM tb_users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: password.php?error=1");
        exit;
    }

    $user = $result->fetch_assoc();
    $id_user = $user['id_user'];

    // Generate token
    $token = bin2hex(random_bytes(32));
    $expired = date("Y-m-d H:i:s", strtotime("+30 minutes"));

    // Simpan token ke tabel user
    $update = $config->prepare(
        "UPDATE tb_users 
         SET reset_password = ?, reset_ex = ? 
         WHERE id_user = ?"
    );
    $update->bind_param("ssi", $token, $expired, $id_user);
    $update->execute();

    // Link reset
    $link = "http://localhost/sistemarsip/reset_password.php?token=$token";

    // Kirim email
    mail(
        $email,
        "Reset Kata Sandi",
        "Klik link berikut untuk reset kata sandi Anda:\n\n$link"
    );

    header("Location: password.php?success=1");
    exit;
}
