<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST["submit"])) {
    $nama = $_POST["nama_produk"];
    $harga = $_POST["harga"];
    
    // --- PROSES UPLOAD FOTO ---
    $gambar_nama = $_FILES['gambar_file']['name'];
    $gambar_tmp = $_FILES['gambar_file']['tmp_name'];
    
    // Bikin nama file jadi unik agar tidak tertimpa jika ada nama foto yang sama
    $nama_foto_baru = uniqid() . '-' . $gambar_nama;
    
    // Tentukan lokasi folder tempat menyimpan foto (folder 'uploads' yang tadi kamu buat)
    $lokasi_simpan = 'uploads/' . $nama_foto_baru;

    // Pindahkan foto dari laptop ke folder 'uploads' di XAMPP
    move_uploaded_file($gambar_tmp, $lokasi_simpan);

    // Simpan data ke database (lokasi_simpan yang dimasukkan agar bisa dibaca oleh index.php)
    $query = "INSERT INTO katalog (nama_produk, harga, gambar_url) VALUES ('$nama', '$harga', '$lokasi_simpan')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Mantap bos! Produk dan Foto berhasil ditambahkan.');
                window.location.href = 'index.php';
              </script>";
    } else {
        die("GAGAL MENAMBAH PRODUK! <br><br> Error dari MySQL: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - DvzStore</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .kotak-form { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 350px; }
        h2 { color: #00a8ff; text-align: center; margin-top: 0; }
        label { font-weight: bold; font-size: 14px; color: #333; }
        /* Style input form disesuaikan */
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; margin: 8px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="file"] { margin: 8px 0 20px 0; width: 100%; }
        input:focus { border-color: #00a8ff; outline: none; }
        button { width: 100%; padding: 12px; background-color: #00a8ff; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background-color: #008dd6; }
        .kembali { display: block; text-align: center; margin-top: 15px; color: #00a8ff; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <div class="kotak-form">
        <h2>Tambah Produk Baru</h2>
        
        <form action="" method="post" enctype="multipart/form-data">
            <label>Nama Produk :</label>
            <input type="text" name="nama_produk" required>

            <label>Harga Barang (Angka Saja) :</label>
            <input type="number" name="harga" placeholder="Contoh: 50000" required>

            <label>Pilih Foto Produk Lokal :</label>
            <input type="file" name="gambar_file" accept="image/*" required>

            <button type="submit" name="submit">Simpan Produk</button>
        </form>
        
        <a href="index.php" class="kembali">&laquo; Kembali ke Toko</a>
    </div>

</body>
</html>