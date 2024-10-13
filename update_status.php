<?php
session_start();
include 'connection/koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    die("Silakan login sebagai admin.");
}

// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_order = $_POST['id_order'];

    // Update status menjadi "sudah diproses"
    $query = "UPDATE tb_order SET status = 'sudah diproses' WHERE id_order = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_order);
    
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
}
?>
