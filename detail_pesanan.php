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
        <?php
            // Ambil semua pesanan dari database yang belum diproses
            $query_pesanan = "SELECT o.id_order, u.nama_user AS nama_lengkap, o.no_meja, o.total_harga, o.waktu_pesan, o.status 
                              FROM tb_order o 
                              JOIN tb_user u ON o.id_user = u.id_user 
                              WHERE o.status = 'belum diproses' 
                              ORDER BY o.waktu_pesan DESC";
            $result_pesanan = $koneksi->query($query_pesanan);
            $no = 1;

            while ($pesanan = $result_pesanan->fetch_assoc()):
                // Ambil makanan yang dipesan beserta jumlahnya untuk setiap pesanan
                $id_order = $pesanan['id_order'];
                $query_makanan = "SELECT m.nama_masakan, p.jumlah 
                                  FROM tb_pesan p 
                                  JOIN tb_masakan m ON p.id_masakan = m.id_masakan 
                                  WHERE p.id_order = ?";
                $stmt_makanan = $koneksi->prepare($query_makanan);
                $stmt_makanan->bind_param("i", $id_order);
                $stmt_makanan->execute();
                $result_makanan = $stmt_makanan->get_result();
                
                // Gabungkan nama makanan yang dipesan beserta jumlahnya
                $makanan_dipesan = [];
                while ($makanan = $result_makanan->fetch_assoc()) {
                    $makanan_dipesan[] = $makanan['nama_masakan'] . ' (' . $makanan['jumlah'] . ')'; // Contoh: "Nasi Goreng (2)"
                }
                $nama_makanan = implode(", ", $makanan_dipesan); // Menggabungkan semua makanan yang dipesan dalam satu string
                
                $stmt_makanan->close();
            ?>
</body>
</html>