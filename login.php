<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah pengguna terdaftar
    $query = "SELECT * FROM tb_user WHERE username = ? AND password = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];

        // Jika admin, arahkan ke admin.php
        if ($username === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: menu.php");
        }
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Tautkan ke file CSS -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
        <!-- Tombol Kembali ke Beranda -->
        <div class="button-container">
        <a href="index.php">
            <button type="button">Kembali ke Beranda</button>
        </a>
    </div>
</body>
</html>
