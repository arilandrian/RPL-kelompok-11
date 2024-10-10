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
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="mb-6">
                    <label for="password_confirmation">Password Confirmation</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
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
