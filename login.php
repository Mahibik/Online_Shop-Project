<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION["login"])) {
    if ($_SESSION["role"] == 'seller') {
        header("Location: seller.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if ($password == $row["password"] || password_verify($password, $row["password"])) {
            
            $_SESSION["login"] = true;
            $_SESSION["user_nama"] = $username;
            $_SESSION["role"] = $row["role"]; 

            if ($row["role"] == 'seller') {
                header("Location: seller.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DvzStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #f4f7fe 0%, #e2ebfc 100%); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); padding: 40px 30px; border-radius: 24px; box-shadow: 0 15px 35px rgba(0, 114, 255, 0.08); width: 100%; max-width: 380px; border: 1px solid rgba(255, 255, 255, 0.5); }
        h2 { text-align: center; font-weight: 800; background: linear-gradient(135deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px; font-size: 28px; }
        .subtitle { text-align: center; font-size: 13px; color: #a3aed0; margin-bottom: 30px; font-weight: 400; }
        label { font-weight: 600; font-size: 13px; color: #2b3674; display: block; margin-bottom: 8px; padding-left: 4px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 14px 18px; margin-bottom: 22px; border: 1px solid #d3daf2; background-color: #fff; border-radius: 16px; font-size: 14px; color: #2b3674; transition: all 0.3s ease; }
        input:focus { border-color: #0072ff; outline: none; box-shadow: 0 4px 12px rgba(0, 114, 255, 0.1); }
        button { width: 100%; padding: 14px; background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; border: none; border-radius: 16px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.25); }
        button:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 114, 255, 0.35); }
        .error { color: #ff4757; background-color: #ffe3e5; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 600; text-align: center; margin-bottom: 20px; border: 1px solid rgba(255, 71, 87, 0.2); }
        .register-link { text-align: center; margin-top: 25px; font-size: 13px; color: #a3aed0; }
        .register-link a { color: #0072ff; text-decoration: none; font-weight: 700; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>DvzStore.</h2>
        <div class="subtitle">Silakan masuk untuk mulai berbelanja</div>
        
        <?php if (isset($error)) : ?>
            <div class="error">Username atau Password salah!</div>
        <?php endif; ?>

        <form action="" method="post">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username kamu" required autocomplete="off">

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password kamu" required>

            <button type="submit" name="login">Masuk Sekarang</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Buat Akun Baru</a>
        </div>
    </div>
</body>
</html>