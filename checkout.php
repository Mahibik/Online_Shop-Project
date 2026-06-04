<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

$nama_pembeli = $_SESSION["user_nama"] ?? 'Pelanggan'; 

$query_total = mysqli_query($conn, "SELECT SUM(katalog.harga * keranjang.jumlah) AS total, SUM(keranjang.jumlah) AS total_barang FROM keranjang JOIN katalog ON keranjang.produk_id = katalog.id");
$row_total = mysqli_fetch_assoc($query_total);
$total_belanja = $row_total['total'];
$berat_total = $row_total['total_barang'] * 1000; 

if ($total_belanja == 0 || $total_belanja == null) {
    echo "<script>alert('Keranjang kosong cuy!'); window.location.href='index.php';</script>";
    exit;
}

$status_api = "🚀 Mode Demo Aktif (Loading Instan, Anti-Timeout!)";

$kota_list = [
    ['city_id' => '152', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat', 'province' => 'DKI Jakarta'],
    ['city_id' => '153', 'type' => 'Kota', 'city_name' => 'Jakarta Selatan', 'province' => 'DKI Jakarta'],
    ['city_id' => '154', 'type' => 'Kota', 'city_name' => 'Jakarta Timur', 'province' => 'DKI Jakarta'],
    ['city_id' => '155', 'type' => 'Kota', 'city_name' => 'Jakarta Barat', 'province' => 'DKI Jakarta'],
    ['city_id' => '22', 'type' => 'Kota', 'city_name' => 'Bandung', 'province' => 'Jawa Barat'],
    ['city_id' => '54', 'type' => 'Kota', 'city_name' => 'Bekasi', 'province' => 'Jawa Barat'],
    ['city_id' => '78', 'type' => 'Kota', 'city_name' => 'Bogor', 'province' => 'Jawa Barat'],
    ['city_id' => '108', 'type' => 'Kota', 'city_name' => 'Depok', 'province' => 'Jawa Barat'],
    ['city_id' => '398', 'type' => 'Kota', 'city_name' => 'Semarang', 'province' => 'Jawa Tengah'],
    ['city_id' => '427', 'type' => 'Kota', 'city_name' => 'Surakarta (Solo)', 'province' => 'Jawa Tengah'],
    ['city_id' => '501', 'type' => 'Kabupaten', 'city_name' => 'Wonosobo', 'province' => 'Jawa Tengah'],
    ['city_id' => '419', 'type' => 'Kabupaten', 'city_name' => 'Sleman', 'province' => 'DI Yogyakarta'],
    ['city_id' => '505', 'type' => 'Kota', 'city_name' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
    ['city_id' => '256', 'type' => 'Kota', 'city_name' => 'Malang', 'province' => 'Jawa Timur'],
    ['city_id' => '444', 'type' => 'Kota', 'city_name' => 'Surabaya', 'province' => 'Jawa Timur'],
    ['city_id' => '114', 'type' => 'Kota', 'city_name' => 'Denpasar', 'province' => 'Bali'],
    ['city_id' => '278', 'type' => 'Kota', 'city_name' => 'Medan', 'province' => 'Sumatera Utara'],
    ['city_id' => '318', 'type' => 'Kota', 'city_name' => 'Padang', 'province' => 'Sumatera Barat'],
    ['city_id' => '328', 'type' => 'Kota', 'city_name' => 'Palembang', 'province' => 'Sumatera Selatan'],
    ['city_id' => '334', 'type' => 'Kota', 'city_name' => 'Pekanbaru', 'province' => 'Riau'],
    ['city_id' => '54', 'type' => 'Kota', 'city_name' => 'Batam', 'province' => 'Kepulauan Riau'],
    ['city_id' => '21', 'type' => 'Kota', 'city_name' => 'Balikpapan', 'province' => 'Kalimantan Timur'],
    ['city_id' => '390', 'type' => 'Kota', 'city_name' => 'Samarinda', 'province' => 'Kalimantan Timur'],
    ['city_id' => '51', 'type' => 'Kota', 'city_name' => 'Banjarmasin', 'province' => 'Kalimantan Selatan'],
    ['city_id' => '345', 'type' => 'Kota', 'city_name' => 'Pontianak', 'province' => 'Kalimantan Barat'],
    ['city_id' => '254', 'type' => 'Kota', 'city_name' => 'Makassar', 'province' => 'Sulawesi Selatan'],
    ['city_id' => '274', 'type' => 'Kota', 'city_name' => 'Manado', 'province' => 'Sulawesi Utara'],
    ['city_id' => '17', 'type' => 'Kota', 'city_name' => 'Ambon', 'province' => 'Maluku'],
    ['city_id' => '156', 'type' => 'Kota', 'city_name' => 'Jayapura', 'province' => 'Papua']
];

$ongkir = 0;
$layanan_terpilih = "";


if (isset($_POST['hitung_ongkir'])) {
    $tujuan = $_POST['kota_tujuan'];
    $kurir = $_POST['kurir'];
    
    
    $harga_dasar = rand(12, 65) * 1000;
    $harga_tambahan = array(0, 500, 0, 0)[rand(0,3)]; 
    
    $ongkir = $harga_dasar + $harga_tambahan;
    $layanan_terpilih = strtoupper($kurir) . " - REGULAR (Estimasi 2-3 Hari)";
}


if (isset($_POST['buat_pesanan'])) {
    $grand_total = $_POST['grand_total']; 
    
    mysqli_query($conn, "INSERT INTO pesanan (nama_pembeli, total_belanja, status) VALUES ('$nama_pembeli', '$grand_total', 'Menunggu Pembayaran')");
    mysqli_query($conn, "TRUNCATE TABLE keranjang");
    
    echo "<script>
            alert('🎉 Pesanan Berhasil! Total tagihan: Rp " . number_format($grand_total, 0, ',', '.') . "'); 
            window.location.href='index.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout Pesanan - DvzStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7fe; color: #2b3674; padding: 40px 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        h2 { border-bottom: 2px solid #f4f7fe; padding-bottom: 15px; margin-bottom: 20px; color: #0072ff; }
        .alert-box { background: #eaffe9; color: #11998e; padding: 12px 15px; border-radius: 8px; font-size: 13px; font-weight: 700; margin-bottom: 20px; border: 1px solid #38ef7d; }
        .rincian { background: #f8f9fc; padding: 20px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #00c6ff; }
        label { font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block; margin-top: 15px; }
        select { width: 100%; padding: 12px; border: 1px solid #d3daf2; border-radius: 12px; font-size: 14px; outline: none; }
        select:focus { border-color: #0072ff; }
        .btn-hitung { width: 100%; padding: 14px; background: #2b3674; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; margin-top: 25px; transition: 0.3s; font-size: 15px; }
        .btn-hitung:hover { background: #1a2352; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(43, 54, 116, 0.2); }
        .grand-total { font-size: 26px; font-weight: 800; color: #ff4757; text-align: right; margin-top: 15px; }
        .btn-bayar { display: block; width: 100%; text-align: center; padding: 16px; background: linear-gradient(135deg, #11998e, #38ef7d); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-size: 18px; margin-top: 20px; transition: 0.3s; box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3); }
        .btn-bayar:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(56, 239, 125, 0.4); }
    </style>
</head>
<body>
<div class="container">
    <h2>Checkout Pesanan 🛒</h2>
    
    <div class="alert-box">
        <?php echo $status_api; ?>
    </div>

    <div class="rincian">
        <p><b>Nama Pembeli:</b> <?php echo htmlspecialchars($nama_pembeli); ?></p>
        <p><b>Total Harga Barang:</b> Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></p>
        <p><b>Estimasi Berat:</b> <?php echo number_format($berat_total, 0, ',', '.'); ?> gram</p>
    </div>

    <form action="" method="post">
        <label>Kirim Ke Kota / Kabupaten mana nih?</label>
        <select name="kota_tujuan" required>
            <option value="">-- Cari Kota Tujuan --</option>
            <?php foreach ($kota_list as $kota) : ?>
                <option value="<?php echo $kota['city_id']; ?>" <?php if(isset($_POST['kota_tujuan']) && $_POST['kota_tujuan'] == $kota['city_id']) echo 'selected'; ?>>
                    <?php echo $kota['type'] . ' ' . $kota['city_name'] . ' (' . $kota['province'] . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Pilih Jasa Ekspedisi:</label>
        <select name="kurir" required>
            <option value="jne" <?php if(isset($_POST['kurir']) && $_POST['kurir'] == 'jne') echo 'selected'; ?>>JNE</option>
            <option value="pos" <?php if(isset($_POST['kurir']) && $_POST['kurir'] == 'pos') echo 'selected'; ?>>POS Indonesia</option>
            <option value="tiki" <?php if(isset($_POST['kurir']) && $_POST['kurir'] == 'tiki') echo 'selected'; ?>>TIKI</option>
        </select>

        <button type="submit" name="hitung_ongkir" class="btn-hitung">Hitung Ongkos Kirim Dulu &raquo;</button>
    </form>

    <?php if ($ongkir > 0) : ?>
        <hr style="border: 1px solid #e2ebfc; margin: 30px 0;">
        <div class="rincian" style="border-left-color: #38ef7d; background: #eaffe9;">
            <p style="font-size: 16px; color: #2b3674;"><b>Biaya Ongkir (<?php echo $layanan_terpilih; ?>):</b> Rp <?php echo number_format($ongkir, 0, ',', '.'); ?></p>
            <div class="grand-total">Total Bayar: Rp <?php echo number_format($total_belanja + $ongkir, 0, ',', '.'); ?></div>
        </div>
        
        <form action="" method="post">
            <input type="hidden" name="grand_total" value="<?php echo $total_belanja + $ongkir; ?>">
            <button type="submit" name="buat_pesanan" class="btn-bayar">Buat Pesanan Sekarang &raquo;</button>
        </form>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 25px;">
        <a href="keranjang.php" style="color: #a3aed0; text-decoration: none; font-weight: 600;">&laquo; Balik ke Keranjang</a>
    </div>
</div>
</body>
</html>
