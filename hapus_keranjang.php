<?php
session_start();
require 'koneksi.php';
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    mysqli_query($conn, "DELETE FROM keranjang WHERE id = '$id'");
}
header("Location: keranjang.php");
?>