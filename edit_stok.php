<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsif untuk perangkat mobile -->
    <title><?= $id_masakan ? "Edit" : "Tambah" ?> Stok</title>
    <link rel="stylesheet" href="css/edit.css"> <!-- Menghubungkan file CSS -->
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