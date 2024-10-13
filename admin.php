<?php
session_start();
include 'koneksi.php'; // Koneksi database

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['id_user']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Gaya CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Library jQuery -->
</head>
<body>
    <div class="admin-container">
        <h1>Admin Panel</h1>

        <!-- Navigation Bar -->
        <ul class="nav-bar">
            <li><a href="#" onclick="loadContent('pengaturan_stok.php')">Pengaturan Stok</a></li>
            <li><a href="#" onclick="loadContent('pengaturan_akun.php')">Pengaturan Akun</a></li>
            <li><a href="#" onclick="loadContent('detail_pesanan.php')">Detail Pesanan</a></li>
            <li><a href="#" onclick="loadRiwayatTransaksi()">Riwayat Transaksi</a></li>
            <li><a href="logout.php" class="logout-button">Logout</a></li> <!-- Tambahkan tombol logout -->
        </ul>

        <!-- Konten akan dimuat di sini -->
        <div id="content-area">
            <!-- Konten dinamis dari file lain akan dimuat di sini -->
        </div>
    </div>