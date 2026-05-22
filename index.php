<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$produk_query = mysqli_query($conn, "SELECT * FROM katalog ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DvzStore - Belanja Online Mudah & Terpercaya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* CSS MODERN UPDATE */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7fe; color: #2b3674; }
        
        /* Navbar dengan efek Glassmorphism (Kaca) */
        .navbar { 
            background: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex; justify-content: space-between; align-items: center; 
            padding: 15px 5%; 
            position: sticky; top: 0; z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .brand { font-size: 26px; font-weight: 800; background: linear-gradient(135deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none; letter-spacing: -0.5px; }
        
        .user-menu { display: flex; align-items: center; gap: 15px; font-size: 14px; font-weight: 600; }
        
        /* Tombol Modern */
        .btn-keranjang { background: linear-gradient(135deg, #11998e, #38ef7d); color: white; padding: 10px 20px; text-decoration: none; border-radius: 30px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3); }
        .btn-keranjang:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(56, 239, 125, 0.4); }
        
        .btn-logout { background-color: #fff; color: #ff4757; border: 2px solid #ff4757; padding: 8px 20px; text-decoration: none; border-radius: 30px; font-weight: 600; transition: all 0.3s ease; }
        .btn-logout:hover { background-color: #ff4757; color: white; box-shadow: 0 4px 15px rgba(255, 71, 87, 0.3); }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        /* Banner Gradient Smooth */
        .banner { 
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); 
            color: white; padding: 50px 30px; border-radius: 24px; text-align: center; 
            margin-bottom: 40px; box-shadow: 0 15px 30px rgba(0, 114, 255, 0.2); 
            position: relative; overflow: hidden;
        }
        .banner h2 { font-size: 36px; font-weight: 800; margin-bottom: 10px; }
        .banner p { font-size: 16px; font-weight: 300; opacity: 0.9; }

        .section-title { font-size: 22px; font-weight: 700; margin-bottom: 20px; color: #2b3674; display: flex; align-items: center; gap: 10px; }
        .section-title::before { content: ''; display: block; width: 6px; height: 24px; background: linear-gradient(135deg, #00c6ff, #0072ff); border-radius: 10px; }
        
        /* Grid & Kartu Produk Melayang */
        .katalog-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 25px; }
        .kartu-produk { 
            background-color: #ffffff; border-radius: 20px; overflow: hidden; 
            display: flex; flex-direction: column; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            border: 1px solid rgba(0,0,0,0.02);
        }
        .kartu-produk:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 20px 40px rgba(0, 114, 255, 0.12); 
        }
        
        .gambar-container { width: 100%; height: 220px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fc; overflow: hidden; }
        .gambar-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .kartu-produk:hover .gambar-container img { transform: scale(1.08); }
        
        .info-produk { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .nama-produk { font-size: 15px; color: #2b3674; margin-bottom: 10px; font-weight: 600; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .harga { color: #0072ff; font-size: 20px; font-weight: 800; margin-bottom: 20px; margin-top: auto; }
        
        .btn-beli { 
            display: block; text-align: center; 
            background: #f4f7fe; color: #0072ff; 
            padding: 10px; text-decoration: none; border-radius: 12px; 
            font-weight: 700; font-size: 14px; transition: all 0.3s ease; 
        }
        .kartu-produk:hover .btn-beli { 
            background: linear-gradient(135deg, #00c6ff, #0072ff); 
            color: white; 
            box-shadow: 0 4px 15px rgba(0, 114, 255, 0.3);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="brand">DvzStore.</a>
        <div class="user-menu">
            <span>Halo, <b style="color: #0072ff;"><?php echo htmlspecialchars($_SESSION["user_nama"] ?? 'User'); ?></b></span>
            <a href="keranjang.php" class="btn-keranjang">🛒 Keranjang</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>
    <main class="container">
        <div class="banner">
            <h2>Pesta Diskon DvzStore!</h2>
            <p>Jelajahi koleksi terbaik kami dengan antarmuka yang lebih segar.</p>
        </div>
        
        <h3 class="section-title">Katalog Produk</h3>
        <div class="katalog-container">
            <?php while ($row = mysqli_fetch_assoc($produk_query)) : ?>
                <div class="kartu-produk">
                    <div class="gambar-container">
                        <img src="<?php echo htmlspecialchars($row['gambar_url'] ?? ''); ?>" alt="Foto">
                    </div>
                    <div class="info-produk">
                        <div class="nama-produk">
                            <?php echo htmlspecialchars($row['nama_produk'] ?? 'Produk Tidak Dikenal'); ?>
                        </div>
                        <div class="harga">
                            Rp <?php echo number_format((float)($row['harga'] ?? 0), 0, ',', '.'); ?>
                        </div>
                        <a href="tambah_keranjang.php?id=<?php echo $row['id'] ?? 0; ?>" class="btn-beli">+ Keranjang</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>