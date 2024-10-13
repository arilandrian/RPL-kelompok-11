<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="flex items-center justify-center h-full">
        <div class="login-container">
            <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">
                </div>
                <div class="mb-6">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <button type="submit" name ="login">Login</button>
                </div>
            </form>
            <p class="mt-4 text-center">Tidak punya akun? <a href="#">Sign Up</a></p>
        </div>
    </div>

    
	<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek pengguna terdaftar
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
        if ($username == 'admin') {
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

</body>
</html>


