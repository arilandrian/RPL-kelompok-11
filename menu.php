<?php
session_start();
include 'connection/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    die("Silakan login terlebih dahulu.");
}

// Ambil data masakan yang tersedia
$query_masakan = "SELECT * FROM tb_masakan WHERE status_masakan = 'tersedia' AND stok > 0";
$result_masakan = $koneksi->query($query_masakan);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_meja = $_POST['no_meja'];
    $total_harga = 0;

    // Simpan stok sementara untuk rollback jika pesanan gagal
    $stok_sementara = [];

    foreach ($_POST['jumlah'] as $id_masakan => $jumlah) {
        if ($jumlah > 0) {
            // Ambil harga dan stok masakan
            $query_harga = "SELECT harga, stok FROM tb_masakan WHERE id_masakan = ?";
            $stmt_harga = $koneksi->prepare($query_harga);
            $stmt_harga->bind_param("i", $id_masakan);
            $stmt_harga->execute();
            $result_harga = $stmt_harga->get_result();
            $data_masakan = $result_harga->fetch_assoc();

            $harga_masakan = $data_masakan['harga'];
            $stok_masakan = $data_masakan['stok'];

            // Periksa apakah jumlah pesanan melebihi stok
            if ($jumlah > $stok_masakan) {
                echo "Jumlah pesanan untuk {$data_masakan['nama_masakan']} melebihi stok yang tersedia ($stok_masakan).";
                exit; // Hentikan eksekusi jika jumlah melebihi stok
            }

            // Hitung total harga
            $total_harga += $harga_masakan * $jumlah;

            // Simpan pesanan ke session
            $_SESSION['keranjang'][$id_masakan] = $jumlah;

            // Simpan stok untuk rollback
            $stok_sementara[$id_masakan] = $jumlah;
        }
    }

    // Proses checkout
    $query_order = "INSERT INTO tb_order (id_user, no_meja, total_harga, waktu_pesan) VALUES (?, ?, ?, NOW())";
    $stmt_order = $koneksi->prepare($query_order);
    $stmt_order->bind_param("isi", $_SESSION['id_user'], $no_meja, $total_harga);

    if ($stmt_order->execute()) {
        $id_order = $stmt_order->insert_id;

        // Simpan detail pesanan
        foreach ($_SESSION['keranjang'] as $id_masakan => $jumlah) {
            $query_pesan = "INSERT INTO tb_pesan (id_order, id_masakan, jumlah) VALUES (?, ?, ?)";
            $stmt_pesan = $koneksi->prepare($query_pesan);
            $stmt_pesan->bind_param("iii", $id_order, $id_masakan, $jumlah);
            $stmt_pesan->execute();
        }

        // Kurangi stok di database setelah pesanan berhasil
        foreach ($stok_sementara as $id_masakan => $jumlah) {
            $query_update_stok = "UPDATE tb_masakan SET stok = stok - ? WHERE id_masakan = ?";
            $stmt_update_stok = $koneksi->prepare($query_update_stok);
            $stmt_update_stok->bind_param("ii", $jumlah, $id_masakan);
            $stmt_update_stok->execute();
        }

        // Kosongkan keranjang
        unset($_SESSION['keranjang']);
        header("Location: terima.php"); // Arahkan ke halaman terima kasih
        exit();
    } else {
        echo "Terjadi kesalahan: " . $stmt_order->error;
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="css/menu.css"> <!-- Tautkan ke file CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="form-container">
        <h2>Menu Makanan</h2>
        <div class="logout-container">
            <form method="POST" action="logout.php">
                <button type="submit">Logout</button>
            </form>
        </div>
        <form method="POST" action="" onsubmit="return validateOrder();">
            <div class="input-meja">
                <label>No Meja:</label>
                <input type="text" name="no_meja" required>
                <h3>Daftar Makanan</h3>
                <div class="menu-container"> <!-- Kontainer untuk semua menu -->
                    <?php if ($result_masakan->num_rows > 0): ?>
                        <?php while ($masakan = $result_masakan->fetch_assoc()): ?>
                            <div class="menu-item">
                                <img src="gambar/<?php echo $masakan['gambar_masakan']; ?>" alt="<?php echo $masakan['nama_masakan']; ?>" width="100" height="100">
                                <div class="menu-info">
                                    <label class="menu-name"><?php echo $masakan['nama_masakan']; ?> (Rp <?php echo number_format($masakan['harga'], 0, ',', '.'); ?>)
                                </div>
                                <div class="input-container">
                                    <button type="button" class="decrement" onclick="changeValue(<?php echo $masakan['id_masakan']; ?>, -1)">-</button>
                                    <input type="number" id="jumlah_<?php echo $masakan['id_masakan']; ?>" name="jumlah[<?php echo $masakan['id_masakan']; ?>]" min="0" value="0" readonly>
                                    <button type="button" class="increment" onclick="changeValue(<?php echo $masakan['id_masakan']; ?>, 1)">+</button>
                                </div>
                                <label>Stok: <span id="stok_<?php echo $masakan['id_masakan']; ?>"><?php echo $masakan['stok']; ?></span></label>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Tidak ada menu yang tersedia saat ini.</p>
                    <?php endif; ?>
                </div> <!-- Akhir dari menu-container -->

                <div>
                    <h3>Total Pesanan: <span id="total_harga">Rp 0</span></h3>
                </div>

                <script>
                    function changeValue(idMasakan, increment) {
                        var input = document.getElementById('jumlah_' + idMasakan);
                        var stok = parseInt(document.getElementById('stok_' + idMasakan).innerText);
                        var currentValue = parseInt(input.value);
                        if (!isNaN(currentValue)) {
                            var newValue = currentValue + increment;
                            if (newValue >= 0 && newValue <= stok) { // Batasi jumlah berdasarkan stok
                                input.value = newValue;
                                updateTotal(); // Memperbarui total setiap kali jumlah diubah
                            }
                        }
                    }

                    function updateTotal() {
                        var total = 0;
                        <?php
                        $query_harga = "SELECT id_masakan, harga FROM tb_masakan WHERE status_masakan = 'tersedia' AND stok > 0";
                        $result_harga = $koneksi->query($query_harga);
                        while ($masakan = $result_harga->fetch_assoc()): ?>
                            var jumlah = parseInt(document.getElementById('jumlah_<?php echo $masakan['id_masakan']; ?>').value) || 0;
                            total += jumlah * <?php echo $masakan['harga']; ?>;
                        <?php endwhile; ?>
                        document.getElementById('total_harga').innerText = 'Rp ' + total.toLocaleString();
                    }

                    function validateOrder() {
                        var totalItems = 0;
                        <?php
                        $query_harga = "SELECT id_masakan FROM tb_masakan WHERE status_masakan = 'tersedia' AND stok > 0";
                        $result_harga = $koneksi->query($query_harga);
                        while ($masakan = $result_harga->fetch_assoc()): ?>
                            totalItems += parseInt(document.getElementById('jumlah_<?php echo $masakan['id_masakan']; ?>').value) || 0;
                        <?php endwhile; ?>
                        if (totalItems < 1) {
                            alert("Minimal pemesanan adalah 1 item. Silakan tambahkan item ke pesanan.");
                            return false; // Hentikan pengiriman form
                        }
                        return true; // Izinkan pengiriman form
                    }
                </script>

                <div class="button-container">
                    <button type="submit">Order</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>