<?php
session_start();
include 'connection/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    die("Silakan login terlebih dahulu.");
}

// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil id_user dari sesi
    $id_user = $_SESSION['id_user'];
    // Ambil no_meja dari input
    $no_meja = $_POST['no_meja'];

    // Ambil total_harga dari POST
    $total_harga = 0;
    if (isset($_POST['jumlah'])) {
        foreach ($_POST['jumlah'] as $id_masakan => $jumlah) {
            if ($jumlah > 0) {
                // Ambil harga dari database
                $query_harga = "SELECT harga FROM tb_masakan WHERE id_masakan = ?";
                $stmt_harga = $koneksi->prepare($query_harga);
                $stmt_harga->bind_param("i", $id_masakan);
                $stmt_harga->execute();
                $result_harga = $stmt_harga->get_result();

                if ($result_harga->num_rows > 0) {
                    $masakan = $result_harga->fetch_assoc();
                    $total_harga += $masakan['harga'] * $jumlah;
                }
            }
        }
    }

    // Insert ke tb_order
    $query_order = "INSERT INTO tb_order (id_user, no_meja, total_harga, waktu_pesan) VALUES (?, ?, ?, NOW())";
    $stmt_order = $koneksi->prepare($query_order);
    $stmt_order->bind_param("isi", $id_user, $no_meja, $total_harga);
    
    if ($stmt_order->execute()) {
        $id_order = $stmt_order->insert_id; // Ambil id_order yang baru saja dibuat

        // Insert detail pesanan ke tb_pesan
        foreach ($_POST['jumlah'] as $id_masakan => $jumlah) {
            if ($jumlah > 0) {
                $query_pesan = "INSERT INTO tb_pesan (id_order, id_masakan, jumlah) VALUES (?, ?, ?)";
                $stmt_pesan = $koneksi->prepare($query_pesan);
                $stmt_pesan->bind_param("iii", $id_order, $id_masakan, $jumlah);
                $stmt_pesan->execute();
            }
        }

        // Kosongkan keranjang setelah pemesanan berhasil
        unset($_SESSION['keranjang']);
        echo "<h2>Terimakasih sudah membeli ^^ Pesanan akan segera diproses!</h2>";
    } else {
        echo "Terjadi kesalahan saat memproses pesanan: " . $stmt_order->error;
    }
} else {
    echo "Request tidak valid.";
}
?>