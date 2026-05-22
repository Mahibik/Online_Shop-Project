<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'koneksi.php';

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'pembeli'
)");

@mysqli_query($conn, "ALTER TABLE users ADD role VARCHAR(50) DEFAULT 'pembeli'");

if (isset($_POST["register"])) {
    $username = strtolower(stripslashes($_POST["username"]));
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $konfirmasi = mysqli_real_escape_string($conn, $_POST["konfirmasi"]);
    $role = $_POST["role"];

    $cek_username = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    
    if ($cek_username && mysqli_num_rows($cek_username) > 0) {
        echo "<script>alert('Username sudah terdaftar! Silakan cari nama lain.');</script>";
    } else {
        if ($password !== $konfirmasi) {
            echo "<script>alert('Konfirmasi password tidak cocok bos!');</script>";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hash', '$role')");

            if ($insert) {
                echo "<script>
                        alert('Mantap! Akun berhasil dibuat! Silakan Login.');
                        window.location.href = 'login.php';
                      </script>";
            } else {
                echo "<script>alert('Gagal daftar! Error: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - DvzStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #f4f7fe 0%, #e2ebfc 100%); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); padding: 35px 30px; border-radius: 24px; box-shadow: 0 15px 35px rgba(0, 114, 255, 0.08); width: 100%; max-width: 400px; border: 1px solid rgba(255, 255, 255, 0.5); }
        h2 { text-align: center; font-weight: 800; background: linear-gradient(135deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 5px; font-size: 28px; }
        .subtitle { text-align: center; font-size: 13px; color: #a3aed0; margin-bottom: 25px; }
        label { font-weight: 600; font-size: 13px; color: #2b3674; display: block; margin-bottom: 6px; padding-left: 4px; }
        input[type="text"], input[type="password"], select { width: 100%; padding: 12px 16px; margin-bottom: 16px; border: 1px solid #d3daf2; background-color: #fff; border-radius: 14px; font-size: 14px; color: #2b3674; transition: all 0.3s ease; }
        input:focus, select:focus { border-color: #0072ff; outline: none; box-shadow: 0 4px 12px rgba(0, 114, 255, 0.1); }
        button { width: 100%; padding: 14px; background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; border: none; border-radius: 14px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.25); margin-top: 10px; }
        button:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 114, 255, 0.35); }
        .login-link { text-align: center; margin-top: 20px; font-size: 13px; color: #a3aed0; }
        .login-link a { color: #0072ff; text-decoration: none; font-weight: 700; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Buat Akun</h2>
        <div class="subtitle">Lengkapi data di bawah untuk mendaftar</div>
        
        <form action="" method="post">
            <label>Username Baru</label>
            <input type="text" name="username" placeholder="Bikin username baru" required autocomplete="off">

            <label>Password</label>
            <input type="password" name="password" placeholder="Bikin password aman" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="konfirmasi" placeholder="Ketik ulang password" required>

            <label>Daftar Sebagai</label>
            <select name="role" required>
                <option value="pembeli">👤 Pelanggan / Pembeli</option>
                <option value="seller">🏪 Seller / Admin Toko</option>
            </select>

            <button type="submit" name="register">Daftar Sekarang</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </div>
    </div>
</body>
</html>