<?php
session_start();
include 'connection/koneksi.php'; // Pastikan koneksi database sudah benar

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['id_user']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Hapus akun jika tombol hapus ditekan
if (isset($_POST['hapus_akun'])) {
    $id_user = $_POST['id_user'];

    // Hapus semua riwayat transaksi terkait dengan pengguna ini
    $query_hapus_riwayat = "DELETE FROM tb_riwayat_transaksi WHERE id_order IN (SELECT id_order FROM tb_order WHERE id_user = ?)";
    $stmt_hapus_riwayat = $koneksi->prepare($query_hapus_riwayat);
    $stmt_hapus_riwayat->bind_param("i", $id_user);
    $stmt_hapus_riwayat->execute();
    $stmt_hapus_riwayat->close();

    // Hapus semua detail pesan terkait dengan pengguna ini
    $query_hapus_detail = "DELETE FROM tb_pesan WHERE id_order IN (SELECT id_order FROM tb_order WHERE id_user = ?)";
    $stmt_hapus_detail = $koneksi->prepare($query_hapus_detail);
    $stmt_hapus_detail->bind_param("i", $id_user);
    $stmt_hapus_detail->execute();
    $stmt_hapus_detail->close();

    // Hapus semua pesanan terkait dengan pengguna ini
    $query_hapus_pesanan = "DELETE FROM tb_order WHERE id_user = ?";
    $stmt_hapus_pesanan = $koneksi->prepare($query_hapus_pesanan);
    $stmt_hapus_pesanan->bind_param("i", $id_user);
    $stmt_hapus_pesanan->execute();
    $stmt_hapus_pesanan->close();

    // Hapus akun pengguna
    $query_hapus = "DELETE FROM tb_user WHERE id_user = ?";
    $stmt_hapus = $koneksi->prepare($query_hapus);
    $stmt_hapus->bind_param("i", $id_user);
    
    if ($stmt_hapus->execute()) {
        // Arahkan kembali ke admin.php setelah menghapus
        header("Location: admin.php");
        exit();
    } else {
        echo "<p>Gagal menghapus akun.</p>";
    }

    $stmt_hapus->close();
}

// Ambil semua akun pembeli dari database, kecuali admin
$query_akun = "SELECT id_user, username, nama_user, password FROM tb_user WHERE username != 'admin'";
$result_akun = $koneksi->query($query_akun);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Sesuaikan dengan file CSS yang ada -->
</head>
<body>
    <div class="admin-container">
        <h2>Pengaturan Akun</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama Pengguna</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            while ($akun = $result_akun->fetch_assoc()): 
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($akun['username']); ?></td>
                    <td><?php echo htmlspecialchars($akun['nama_user']); ?></td>
                    <td><?php echo htmlspecialchars($akun['password']); ?></td> <!-- Menampilkan password -->
                    <td>
                        <form method="POST" action="pengaturan_akun.php">
                            <input type="hidden" name="id_user" value="<?php echo $akun['id_user']; ?>">
                            <button type="submit" name="hapus_akun" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini? Semua data terkait akan dihapus.');">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br>
    </div>
</body>
</html>
