<?php
session_start();
include 'include/config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepared statement (AMAN)
    $stmt = $config->prepare("
        SELECT id_user, username, password, level 
        FROM tb_users 
        WHERE BINARY username = ?
        AND BINARY password = ?
        LIMIT 1
    ");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $_SESSION['id_user']  = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level']    = $user['level'];

        header("Location: dashboard.php");
        exit();
    }

    // Jika gagal
    header("Location: index.php?error=1");
    exit();
}
?>
