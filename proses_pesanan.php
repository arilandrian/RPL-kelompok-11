<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/proses_pesanan.css"> <!-- Sesuaikan path jika perlu -->
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