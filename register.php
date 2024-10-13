<?php
session_start();
include 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_user = $_POST['nama_user'];

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
            
            header("Location: login.php");
            exit(); 
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
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<div class="flex items-center justify-center h-full">
        <div class="form-container">
            <h2 class="text-2xl font-semibold mb-6 text-center">Register</h2>
            <form action="register.php" method="POST">
                <div class="mb-4">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">
                </div>
                <div class="mb-4">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="mb-6">
                    <label for="password_confirmation">Password Confirmation</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                </div>
                <div>
                    <button type="submit" name ="register">Register</button>
                </div>
            </form>
            <p class="mt-4 text-center">Already have an account? <a href="#">Login</a></p>
        </div>
    </div>
</body>
</html>
