<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'koneksi.php';

if (!isset($_SESSION["login"]) || $_SESSION["role"] != 'seller') {
    echo "<script>alert('Halaman ini khusus Seller cuy!'); window.location.href='index.php';</script>";
    exit;
}

$produk_query = mysqli_query($conn, "SELECT * FROM katalog ORDER BY id DESC");
$pesanan_query = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY id DESC"); // Mengambil data pesanan masuk

if (!$produk_query) { die("<h2>DATABASE ERROR:</h2> " . mysqli_error($conn)); }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Seller - DvzStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7fe; color: #2b3674; }
        .navbar { background: white; display: flex; justify-content: space-between; align-items: center; padding: 15px 5%; box-shadow: 0 4px 20px rgba(0,0,0,0.03); position: sticky; top: 0; z-index: 100; }
        .brand { font-size: 24px; font-weight: 800; color: #0072ff; text-decoration: none; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); margin-bottom: 30px; }
        h2 { border-left: 5px solid #0072ff; padding-left: 12px; margin-bottom: 25px; font-size: 22px; }
        .btn-tambah { display: inline-block; background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; padding: 10px 20px; text-decoration: none; border-radius: 12px; font-weight: 600; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #f4f7fe; }
        th { background-color: #f8f9fc; color: #a3aed0; font-size: 13px; font-weight: 600; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .btn-hapus { background-color: #ffe3e5; color: #ff4757; padding: 6px 12px; text-decoration: none; border-radius: 8px; font-size: 12px; font-weight: 600; }
        .btn-logout { background-color: #ffe3e5; color: #ff4757; padding: 8px 15px; text-decoration: none; border-radius: 10px; font-weight: 600; margin-left: 15px; }
        .status-badge { background-color: #e0f2fe; color: #0284c7; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="#" class="brand">Seller Center.</a>
        <div>
            <span>Admin: <b><?php echo $_SESSION["user_nama"]; ?></b></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>
    <div class="container">
        
        <div class="card">
            <h2>📦 Pesanan Masuk</h2>
            <table>
                <tr>
                    <th>ID Order</th>
                    <th>Nama Pembeli</th>
                    <th>Total Bayar</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
                <?php while ($pesanan = mysqli_fetch_assoc($pesanan_query)) : ?>
                <tr>
                    <td>#<?php echo $pesanan['id']; ?></td>
                    <td><b><?php echo htmlspecialchars($pesanan['nama_pembeli']); ?></b></td>
                    <td>Rp <?php echo number_format($pesanan['total_belanja'], 0, ',', '.'); ?></td>
                    <td><?php echo $pesanan['tanggal']; ?></td>
                    <td><span class="status-badge"><?php echo $pesanan['status']; ?></span></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="card">
            <h2>🛒 Kelola Produk Toko</h2>
            <a href="tambah_produk.php" class="btn-tambah">+ Tambah Produk Baru</a>
            <table>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($produk_query)) : ?>
                <?php $id_produk = isset($row['id']) ? $row['id'] : 'Data Rusak'; ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($row['gambar_url'] ?? ''); ?>"></td>
                    <td><b><?php echo htmlspecialchars($row['nama_produk'] ?? 'Produk Tanpa Nama'); ?></b></td>
                    <td>Rp <?php echo number_format((float)($row['harga'] ?? 0), 0, ',', '.'); ?></td>
                    <td>
                        <?php if ($id_produk !== 'Data Rusak') : ?>
                            <a href="hapus_produk.php?id=<?php echo $id_produk; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                        <?php else : ?>
                            <span style="color: red; font-size: 12px;">Data Error</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        
    </div>
</body>
</html>