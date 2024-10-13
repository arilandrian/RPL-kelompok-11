<?php
session_start();
include 'connection/koneksi.php'; // Pastikan koneksi database sudah benar

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['id_user']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Set filter berdasarkan parameter GET
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Ambil semua riwayat transaksi berdasarkan filter
if ($filter === 'month') {
    $query_transaksi = "
        SELECT o.id_order, u.nama_user, o.no_meja, o.total_harga, o.waktu_pesan, o.status, 
               GROUP_CONCAT(CONCAT(m.nama_masakan, ' (', p.jumlah, ')') SEPARATOR ', ') AS daftar_masakan
        FROM tb_order o
        JOIN tb_user u ON o.id_user = u.id_user
        JOIN tb_pesan p ON o.id_order = p.id_order
        JOIN tb_masakan m ON p.id_masakan = m.id_masakan
        WHERE o.status = 'sudah diproses' AND MONTH(o.waktu_pesan) = MONTH(CURRENT_DATE()) AND YEAR(o.waktu_pesan) = YEAR(CURRENT_DATE())
        GROUP BY o.id_order
        ORDER BY o.waktu_pesan DESC
    ";
} else { // default ke 'today'
    $query_transaksi = "
        SELECT o.id_order, u.nama_user, o.no_meja, o.total_harga, o.waktu_pesan, o.status, 
               GROUP_CONCAT(CONCAT(m.nama_masakan, ' (', p.jumlah, ')') SEPARATOR ', ') AS daftar_masakan
        FROM tb_order o
        JOIN tb_user u ON o.id_user = u.id_user
        JOIN tb_pesan p ON o.id_order = p.id_order
        JOIN tb_masakan m ON p.id_masakan = m.id_masakan
        WHERE o.status = 'sudah diproses' AND DATE(o.waktu_pesan) = CURDATE()
        GROUP BY o.id_order
        ORDER BY o.waktu_pesan DESC
    ";
}

// Ambil total penjualan
$query_total_penjualan = "
    SELECT SUM(total_harga) AS total_penjualan
    FROM tb_order
    WHERE status = 'sudah diproses' AND (
        (DATE(waktu_pesan) = CURDATE() AND '$filter' = 'today') OR 
        (MONTH(waktu_pesan) = MONTH(CURRENT_DATE()) AND YEAR(waktu_pesan) = YEAR(CURRENT_DATE()) AND '$filter' = 'month')
    )
";
$result_total_penjualan = $koneksi->query($query_total_penjualan);
$total_penjualan = $result_total_penjualan->fetch_assoc()['total_penjualan'] ?? 0;

$result_transaksi = $koneksi->query($query_transaksi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Sesuaikan dengan file CSS yang ada -->
</head>

<body>
    <div class="admin-container">
        <h2>Riwayat Transaksi</h2>
        <div class="filter-buttons">
            <button onclick="loadRiwayatTransaksi('today')" class="<?php echo ($filter === 'today') ? 'active' : ''; ?>">Hari Ini</button>
            <button onclick="loadRiwayatTransaksi('month')" class="<?php echo ($filter === 'month') ? 'active' : ''; ?>">Bulan Ini</button>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Nama Pembeli</th>
                <th>No Meja</th>
                <th>Daftar Masakan</th>
                <th>Total Harga</th>
                <th>Waktu Pesan</th>
            </tr>
        </table>
</body>

</html>