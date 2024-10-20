<?php
// Koneksi ke database
include '../connection/koneksi.php'; // Pastikan path ini benar

// Cek apakah ada ID yang diberikan
$id_masakan = isset($_GET['id']) ? $_GET['id'] : null;

// Ambil data menu yang ingin diedit jika ada ID
if ($id_masakan) {
    $query = "SELECT * FROM tb_masakan WHERE id_masakan = '$id_masakan'";
    $result = $koneksi->query($query); // Gunakan $koneksi
    $data = $result->fetch_assoc();
}

// Proses update data atau menambahkan menu baru
if (isset($_POST['update'])) {
    $nama_makanan = $_POST['nama_makanan'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if ($id_masakan) {
        // Update data jika ada ID
        $query = "UPDATE tb_masakan SET nama_masakan = '$nama_makanan', harga = '$harga', stok = '$stok' WHERE id_masakan = '$id_masakan'";
    } else {
        // Tambah menu baru jika tidak ada ID
        $query = "INSERT INTO tb_masakan (nama_masakan, harga, stok, status_masakan) VALUES ('$nama_makanan', '$harga', '$stok', 'tersedia')";
    }
    
    // Eksekusi query dan periksa kesalahan
    if ($koneksi->query($query) === TRUE) {
        header("Location: admin.php"); // Arahkan kembali setelah sukses
        exit(); // Pastikan untuk keluar setelah header
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error; // Tampilkan error jika ada
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsif untuk perangkat mobile -->
    <title><?= $id_masakan ? "Edit" : "Tambah" ?> Stok</title>
    <link rel="stylesheet" href="../css/edit.css"> <!-- Menghubungkan file CSS -->
</head>
<body>
    <h1><?= $id_masakan ? "Edit" : "Tambah" ?> Menu</h1>
    <form method="post">
        <label for="nama_makanan">Nama Makanan:</label>
        <input type="text" name="nama_makanan" id="nama_makanan" value="<?= $id_masakan ? htmlspecialchars($data['nama_masakan']) : '' ?>" required>
        
        <label for="harga">Harga:</label>
        <input type="number" name="harga" id="harga" value="<?= $id_masakan ? htmlspecialchars($data['harga']) : '' ?>" required>
        
        <label for="stok">Stok:</label>
        <input type="number" name="stok" id="stok" value="<?= $id_masakan ? htmlspecialchars($data['stok']) : '' ?>" required>
        
        <button type="submit" name="update"><?= $id_masakan ? "Update" : "Tambah" ?> Menu</button>
    </form>
    
    <a href="admin.php" class="btn-back">Kembali ke Pengaturan Stok</a> <!-- Tombol kembali -->
</body>
</html>