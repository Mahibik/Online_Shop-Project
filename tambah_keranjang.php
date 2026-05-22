<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET["id"])) {
    $produk_id = $_GET["id"];
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE produk_id = '$produk_id'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE produk_id = '$produk_id'");
    } else {
        mysqli_query($conn, "INSERT INTO keranjang (produk_id, jumlah) VALUES ('$produk_id', 1)");
    }
    echo "<script>alert('Produk berhasil masuk ke keranjang!'); window.location.href='index.php';</script>";
} else {
    header("Location: index.php");
}
?>