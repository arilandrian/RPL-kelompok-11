<?php
session_start(); // Memulai sesi untuk mengecek login

// Contoh cek sederhana apakah pengguna sudah login
// Misalnya jika ada sesi 'username', berarti pengguna sudah login
$loggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Makan Sederhana</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Warung Makan Sederhana</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#about">Tentang Kami</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <section id="hero">
        <div class="hero-text">
            <h2>Selamat Datang di Warung Makan Sederhana</h2>
            <p>Menyediakan masakan enak, harga bersahabat.</p>
            <!-- Tombol Lihat Menu -->
            <a href="#menu" class="btn">Lihat Menu</a>
        </div>
    </section>

    <section id="menu">
        <h2>Menu Signature</h2>

        <div class="order-button">
            <!-- Tombol ORDER SEKARANG -->
            <?php if ($loggedIn): ?>
                <a href="menu.php" class="btn order-btn">ORDER SEKARANG!</a>
            <?php else: ?>
                <a href="login.php" class="btn order-btn">ORDER SEKARANG!</a>
            <?php endif; ?>
        </div>
        
        <div class="menu-container">
            <div class="menu-item">
                <img src="gambar/nasi_goreng.jpg" alt="Nasi Goreng">
                <h3>Nasi Goreng</h3>
                <p>Rp 15,000</p>
            </div>
            <div class="menu-item">
                <img src="gambar/mie_goreng.jpg" alt="Mie Goreng">
                <h3>Mie Goreng</h3>
                <p>Rp 12,000</p>
            </div>
            <div class="menu-item">
                <img src="gambar/ayam_penyet.jpg" alt="Ayam Penyet">
                <h3>Ayam Penyet</h3>
                <p>Rp 20,000</p>
            </div>
            <div class="menu-item">
                <img src="gambar/sate_ayam.jpg" alt="Sate Ayam">
                <h3>Sate Ayam</h3>
                <p>Rp 18,000</p>
            </div>
            <div class="menu-item">
                <img src="gambar/es_teh.jpg" alt="Es Teh">
                <h3>Es Teh</h3>
                <p>Rp 5,000</p>
            </div>
            <div class="menu-item">
                <img src="gambar/es_jeruk.jpg" alt="Es Jeruk">
                <h3>Es Jeruk</h3>
                <p>Rp 7,000</p>
            </div>
            <div class="menu-item">
<<<<<<< HEAD
                <img src="gambar/mie_goreng.jpg" alt="Es Jambu">
                <h3>Es jambu</h3>
                <p>Rp 5,000</p>
            </div>
            <div class="menu-item">
                <img src="gambar/mie_goreng.jpg" alt="Es yasmine ">
                <h3>yasmine</h3>
                <p>Rp 80,000</p>
=======
                <img src="gambar/MUHAMMAD_ARIL_ANDRIAN.jpg" alt="Daging Sapi">
                <h3>Daging Sapi</h3>
                <p>Gratis</p>
>>>>>>> d0037d6dfb317686318b5ad21fadeee1ca30e188
            </div>
        </div>
    </section>

    <section id="about">
        <h2>Tentang Kami</h2>
        <p>Warung Makan Sederhana adalah tempat yang menyajikan masakan rumahan dengan cita rasa nusantara. Kami berkomitmen untuk memberikan pelayanan terbaik dan harga yang terjangkau.</p>
    </section>

    <section id="contact">
        <h2>Kontak Kami</h2>
        <p>Email: xxxxx@gmail.com | Telepon: 08xx-xxx-xxx</p>
    </section>

    <footer>
        <p>&copy; 2024 Warung Makan Sederhana</p>
    </footer>
</body>

</html>
