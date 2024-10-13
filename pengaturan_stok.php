<?php
// Koneksi ke database
include 'koneksi.php';

// Hapus menu jika ada perintah hapus melalui AJAX
if (isset($_GET['hapus'])) {
    $id_masakan = $_GET['hapus'];
    $query = "DELETE FROM tb_masakan WHERE id_masakan = '$id_masakan'";
    if ($koneksi->query($query)) {
        // Kembalikan respon sukses untuk AJAX
        echo "Menu berhasil dihapus";
        exit();
    } else {
        echo "Gagal menghapus menu";
        exit();
    }
}

// Ambil data menu
$query = "SELECT * FROM tb_masakan";
$result = $koneksi->query($query);

// Update status_masakan berdasarkan stok
while ($data = $result->fetch_assoc()) {
    $stok = $data['stok'];
    $status_masakan = ($stok > 0) ? 'tersedia' : 'tidak tersedia';

    // Update status_masakan di database jika perlu
    $id_masakan = $data['id_masakan'];
    $update_status_query = "UPDATE tb_masakan SET status_masakan = '$status_masakan' WHERE id_masakan = '$id_masakan'";
    $koneksi->query($update_status_query);
}

// Refresh data setelah update
$query = "SELECT * FROM tb_masakan";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pengaturan Stok</title>
    <link rel="stylesheet" href="css/stok.css"> <!-- Menghubungkan file CSS -->
</head>

<body>
    <h1>Pengaturan Stok</h1>

    <form action="edit_stok.php" method="get">
        <button type="submit" name="tambah">Tambah Menu</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama Makanan</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($data = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $data['id_masakan']; ?></td>
                <td><?= $data['nama_masakan']; ?></td>
                <td><?= $data['harga']; ?></td>
                <td><?= $data['stok']; ?></td>
                <td><?= $data['status_masakan']; ?></td>
                <td>
                    <a href="edit_stok.php?id=<?= $data['id_masakan']; ?>">Edit</a>
                    <a href="#" onclick="hapusMenu(<?= $data['id_masakan']; ?>); return false;">Hapus</a> <!-- AJAX Hapus -->
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        // Fungsi untuk menghapus menu menggunakan AJAX
        function hapusMenu(id_masakan) {
            if (confirm('Yakin ingin menghapus menu ini?')) {
                $.ajax({
                    url: 'pengaturan_stok.php',
                    type: 'GET',
                    data: { hapus: id_masakan },
                    success: function(response) {
                        alert(response); // Tampilkan pesan hasil penghapusan
                        location.reload(); // Muat ulang halaman untuk merefresh daftar menu
                    },
                    error: function() {
                        alert('Gagal menghapus menu.');
                    }
                });
            }
        }
    </script>

</body>

</html>


