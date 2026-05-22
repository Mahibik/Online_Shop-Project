<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
$query = mysqli_query($conn, "SELECT keranjang.id AS id_keranjang, keranjang.jumlah, katalog.nama_produk, katalog.harga, katalog.gambar_url FROM keranjang JOIN katalog ON keranjang.produk_id = katalog.id");
$total_belanja = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Keranjang - DvzStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7fe; color: #2b3674; }
        .navbar { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); display: flex; justify-content: space-between; align-items: center; padding: 15px 5%; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05); }
        .brand { font-size: 26px; font-weight: 800; background: linear-gradient(135deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none; }
        .container { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        h2 { color: #2b3674; margin-bottom: 20px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fc; color: #a3aed0; font-weight: 600; font-size: 14px; }
        img { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; }
        .btn-hapus { color: #ff4757; background-color: #ffe3e5; padding: 6px 12px; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600; transition: 0.3s; }
        .btn-hapus:hover { background-color: #ff4757; color: white; }
        .total-box { margin-top: 20px; text-align: right; padding: 20px; background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); border-radius: 16px; font-size: 20px; font-weight: 700; color: white; box-shadow: 0 10px 20px rgba(0, 114, 255, 0.2); }
        .btn-kembali { display: inline-block; margin-top: 20px; color: #0072ff; text-decoration: none; font-weight: 600; }
        .btn-checkout { background: linear-gradient(135deg, #11998e, #38ef7d); color: white; padding: 12px 25px; text-decoration: none; border-radius: 12px; font-weight: 700; float: right; margin-top: 20px; transition: 0.3s; box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3); }
        .btn-checkout:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(56, 239, 125, 0.4); }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="brand">DvzStore.</a>
        <div><span>Halo, <b style="color: #0072ff;"><?php echo $_SESSION["user_nama"]; ?></b></span></div>
    </nav>
    <div class="container">
        <h2>🛒 Keranjang Belanja</h2>
        <table>
            <tr>
                <th>Foto</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <?php 
                $subtotal = $row['harga'] * $row['jumlah']; 
                $total_belanja += $subtotal; 
            ?>
            <tr>
                <td><img src="<?php echo htmlspecialchars($row['gambar_url']); ?>"></td>
                <td><b style="color: #2b3674;"><?php echo htmlspecialchars($row['nama_produk']); ?></b><br><small>Jml: <?php echo $row['jumlah']; ?></small></td>
                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                <td><a href="hapus_keranjang.php?id=<?php echo $row['id_keranjang']; ?>" class="btn-hapus">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <div class="total-box">
            Total Pembayaran: Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?>
        </div>
        
        <a href="index.php" class="btn-kembali">&laquo; Lanjut Belanja</a>
        
        <a href="checkout.php" class="btn-checkout">Checkout Sekarang &raquo;</a>
        <div style="clear: both;"></div>
    </div>
</body>
</html>