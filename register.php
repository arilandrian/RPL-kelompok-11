<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database sudah benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_user = $_POST['nama_user'];

    // Cek apakah username sudah ada
    $query_check = "SELECT * FROM tb_user WHERE username = ?";
    $stmt_check = $koneksi->prepare($query_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $message = "Username sudah terdaftar. Silakan pilih username lain.";
    } else {
        // Proses registrasi
        $query_register = "INSERT INTO tb_user (username, password, nama_user) VALUES (?, ?, ?)";
        $stmt_register = $koneksi->prepare($query_register);
        $stmt_register->bind_param("sss", $username, $password, $nama_user);

        if ($stmt_register->execute()) {
            // Jika registrasi berhasil, arahkan ke halaman login
            header("Location: login.php");
            exit(); // Pastikan menghentikan eksekusi lebih lanjut setelah redirect
        } else {
            $message = "Terjadi kesalahan saat registrasi: " . $stmt_register->error;
        }
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="css/register.css"> <!-- Tautkan ke file CSS -->
</head>

<body>
    <div class="form-container">
        <h2>Registrasi</h2>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="nama_user">Nama Lengkap:</label>
            <input type="text" id="nama_user" name="nama_user" required>

            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a>.</p>
    </div>
    <!-- Tombol Kembali ke Beranda -->
    <div class="button-container">
        <a href="index.php">
            <button type="button">Kembali ke Beranda</button>
        </a>
    </div>
</body>

</html>