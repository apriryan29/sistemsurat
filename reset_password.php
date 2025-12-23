<?php
include 'include/config.php';

$token = $_GET['token'] ?? '';

$stmt = $config->prepare(
    "SELECT id_user 
     FROM tb_users 
     WHERE reset_password = ? 
       AND reset_ex > NOW()"
);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Token tidak valid atau sudah kadaluarsa.");
}

$data = $result->fetch_assoc();
$id_user = $data['id_user'];
?>

<form method="post">
    <input type="password" name="password" placeholder="Password Baru" required>
    <input type="password" name="confirm" placeholder="Ulangi Password" required>
    <button type="submit">Simpan Password</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        die("Password dan konfirmasi tidak sesuai.");
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $update = $config->prepare(
        "UPDATE tb_users 
         SET password = ?, reset_password = NULL, reset_ex = NULL 
         WHERE id_user = ?"
    );
    $update->bind_param("si", $hashedPassword, $id_user);
    $update->execute();

    echo "Password berhasil diubah." . '<a href="index.php">Kembali ke Login</a>';
}