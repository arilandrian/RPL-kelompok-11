<?php
session_start();
include '../connection/koneksi.php'; // Koneksi database

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['id_user']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Gaya CSS -->
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
            <li><a href="../logout.php" class="logout-button">Logout</a></li> <!-- Tambahkan tombol logout -->
        </ul>

        <!-- Konten akan dimuat di sini -->
        <div id="content-area">
            <!-- Konten dinamis dari file lain akan dimuat di sini -->
        </div>
    </div>

    <script>
        // Fungsi untuk memuat konten dari file lain menggunakan AJAX
        function loadContent(page) {
            $.ajax({
                url: page,
                type: 'GET',
                success: function(response) {
                    $('#content-area').html(response); // Masukkan konten ke div content-area
                },
                error: function() {
                    $('#content-area').html('<p>Error loading content.</p>');
                }
            });
        }


        // Fungsi untuk memuat riwayat transaksi dengan filter
        function loadRiwayatTransaksi(filter = 'today') {
            $.ajax({
                url: 'riwayat_transaksi.php',
                type: 'GET',
                data: { filter: filter },
                success: function(response) {
                    $('#content-area').html(response); // Masukkan konten ke div content-area
                },
                error: function() {
                    $('#content-area').html('<p>Error loading riwayat transaksi.</p>');
                }
            });
        }

        
        // Fungsi untuk menghapus menu menggunakan AJAX
        function hapusMenu(id_masakan) {
            if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                $.ajax({
                    url: 'pengaturan_stok.php',
                    type: 'GET',
                    data: { hapus: id_masakan }, // Kirim parameter hapus
                    success: function(response) {
                        alert('Menu berhasil dihapus!');
                        loadContent('pengaturan_stok.php'); // Refresh halaman stok setelah menghapus
                    },
                    error: function() {
                        alert('Gagal menghapus menu.');
                    }
                });
            }
        }

        // Tampilkan konten default (misalnya, detail pesanan)
        $(document).ready(function() {
            loadContent('detail_pesanan.php');
        });
    </script>
</body>
</html>