<?php 
// Mulai session
session_start();

// Koneksi dengan database
include 'include/config.php';

// Menampilkan error
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Menghindari SQL Injection
    $username = mysqli_real_escape_string($config, $_POST['username']);
    $password = mysqli_real_escape_string($config, $_POST['password']);

    // Query login
    $query = "SELECT * FROM tb_users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($config, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $user['level']; // Simpan level jika diperlukan
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: index.php?error=1"); // Username atau password salah
        exit();
    }
}
?>