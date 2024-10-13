<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "db_warung";

// Koneksi ke database
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>