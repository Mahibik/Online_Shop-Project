<?php
session_start();
require 'koneksi.php';

// Pastikan yang checkout adalah pembeli
if (!isset($_SESSION["login"]) || $_SESSION["role"] != 'pembeli') {
    header("Location: index.php");
    exit;
}

$nama_pembeli = $_SESSION["user_nama"];

// Hitung total harga dari semua barang di keranjang
$query_total = mysqli_query($conn, "SELECT SUM(katalog.harga * keranjang.jumlah) AS total FROM keranjang JOIN katalog ON keranjang.produk_id = katalog.id");
$row_total = mysqli_fetch_assoc($query_total);
$total_belanja = $row_total['total'];

// Jika keranjang tidak kosong
if ($total_belanja > 0) {
    // 1. Simpan ke tabel pesanan
    mysqli_query($conn, "INSERT INTO pesanan (nama_pembeli, total_belanja) VALUES ('$nama_pembeli', '$total_belanja')");
    
    // 2. Kosongkan keranjang setelah berhasil dipesan
    mysqli_query($conn, "TRUNCATE TABLE keranjang");
    
    echo "<script>
            alert('🎉 Pesanan Berhasil Dibuat! Silakan tunggu konfirmasi dari Seller.'); 
            window.location.href='index.php';
          </script>";
} else {
    echo "<script>
            alert('Keranjang kamu masih kosong cuy! Belanja dulu gih.'); 
            window.location.href='index.php';
          </script>";
}
?>