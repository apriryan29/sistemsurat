<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host='localhost';
$username='root';
$password = '';
$database = 'sistem-arsip';

$config = mysqli_connect("localhost", "root", "", "sistem-arsip");
if ($config->connect_error) {
    die("Koneksi gagal: " . $config->connect_error);
}
?>