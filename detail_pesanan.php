<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database sudah benar

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['id_user']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Sesuaikan dengan file CSS yang ada -->
</head>
<body>
    <div class="admin-container">
        <h2>Detail Pesanan</h2>
        <table>
        <tr>
            <th>No</th>
            <th>Nama Lengkap Pembeli</th>
            <th>No Meja</th>
            <th>Total Harga</th>
            <th>Waktu Pesan</th>
            <th>Status</th>
            <th>Makanan Dipesan</th>
            <th>Aksi</th>
        </tr>
</body>
</html>