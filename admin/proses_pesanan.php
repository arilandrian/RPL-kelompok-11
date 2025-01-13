<?php
session_start();
include '../connection/koneksi.php'; // Koneksi database

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['id_user']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil ID order yang akan diproses
$id_order = isset($_POST['id_order']) ? $_POST['id_order'] : 0;

// Ambil detail pesanan dari database
$query = "SELECT o.no_meja, u.nama_user, m.nama_masakan, p.jumlah, m.harga
          FROM tb_order o
          JOIN tb_user u ON o.id_user = u.id_user
          JOIN tb_pesan p ON o.id_order = p.id_order
          JOIN tb_masakan m ON p.id_masakan = m.id_masakan
          WHERE o.id_order = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_order);
$stmt->execute();
$result = $stmt->get_result();

$total_harga = 0; // Variabel untuk menyimpan total harga
$pesanan = []; // Array untuk menyimpan detail pesanan

while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['jumlah'] * $row['harga']; // Hitung subtotal
    $total_harga += $row['subtotal']; // Tambahkan subtotal ke total
    $pesanan[] = $row; // Simpan detail pesanan
}

$stmt->close();

// Proses jika tombol Cancel ditekan
if (isset($_POST['cancel_order'])) {
    // Hapus pesanan dari tb_pesan dan tb_order
    $query_hapus_pesan = "DELETE FROM tb_pesan WHERE id_order = ?";
    $stmt_hapus_pesan = $koneksi->prepare($query_hapus_pesan);
    $stmt_hapus_pesan->bind_param("i", $id_order);
    $stmt_hapus_pesan->execute();

    $query_hapus_order = "DELETE FROM tb_order WHERE id_order = ?";
    $stmt_hapus_order = $koneksi->prepare($query_hapus_order);
    $stmt_hapus_order->bind_param("i", $id_order);
    $stmt_hapus_order->execute();

    $stmt_hapus_pesan->close();
    $stmt_hapus_order->close();

    // Redirect kembali ke halaman admin atau tampilkan pesan sukses
    header("Location: admin.php?message=Pesanan berhasil dibatalkan");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/proses_pesanan.css"> <!-- Sesuaikan path jika perlu -->
    <title>Proses Pesanan</title>
</head>
<body>
    <div class="container">
        <h1>Proses Pesanan</h1>

        <?php if (empty($pesanan)): ?>
            <p>Tidak ada pesanan untuk diproses.</p>
        <?php else: ?>
            <h2>Detail Pesanan</h2>
            <p>Nama Pembeli: <strong><?php echo htmlspecialchars($pesanan[0]['nama_user']); ?></strong></p>
            <p>Nomor Meja: <strong><?php echo htmlspecialchars($pesanan[0]['no_meja']); ?></strong></p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Masakan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pesanan as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nama_masakan']); ?></td>
                                <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($item['harga'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($item['subtotal'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p><strong>Total Harga: <?php echo htmlspecialchars(number_format($total_harga, 2)); ?></strong></p>

            <form method="post" action="proses_pesanan.php">
                <input type="hidden" name="id_order" value="<?php echo $id_order; ?>">
                <label for="pembayaran">Pembayaran:</label>
                <input type="number" name="pembayaran" required>
                <button type="submit" name="proses_pesanan">Proses Pesanan</button>
            </form>

            <!-- Tombol Cancel untuk menghapus pesanan -->
            <form method="post" action="proses_pesanan.php" style="display:inline;">
                <input type="hidden" name="id_order" value="<?php echo $id_order; ?>">
                <button type="submit" name="cancel_order" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">Cancel</button>
            </form>

        <?php endif; ?>

        <?php
        // Proses jika form dikirim untuk memproses pesanan
        if (isset($_POST['proses_pesanan'])) {
            $id_order = $_POST['id_order'];
            $pembayaran = $_POST['pembayaran'];

            // Validasi pembayaran
            if ($pembayaran < $total_harga) {
                echo "<p class='error'>Pembayaran tidak cukup! Total harga adalah Rp " . htmlspecialchars(number_format($total_harga, 2)) . ".</p>";
            } else {
                // Hitung kembalian
                $kembalian = $pembayaran - $total_harga;

                // Insert ke tb_riwayat_transaksi
                $query_insert = "INSERT INTO tb_riwayat_transaksi (id_order, nama_user, no_meja, total_harga, pembayaran, kembalian, waktu_transaksi) 
                                 VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $koneksi->prepare($query_insert);
                $stmt->bind_param("issdds", $id_order, $pesanan[0]['nama_user'], $pesanan[0]['no_meja'], $total_harga, $pembayaran, $kembalian);

                if ($stmt->execute()) {
                    // Update status pesanan menjadi 'sudah diproses'
                    $query_update = "UPDATE tb_order SET status = 'sudah diproses' WHERE id_order = ?";
                    $stmt_update = $koneksi->prepare($query_update);
                    $stmt_update->bind_param("i", $id_order);
                    $stmt_update->execute();
                    $stmt_update->close();

                    echo "<p>Transaksi berhasil diproses. Kembalian: " . htmlspecialchars(number_format($kembalian, 2)) . "</p>";
                } else {
                    echo "<p>Gagal memproses transaksi.</p>";
                }

                $stmt->close();
            }
        }
        ?>

        <!-- Tombol Kembali -->
        <p>
            <a href="admin.php"><button>Kembali ke Panel Admin</button></a>
        </p>
    </div>
</body>
</html>