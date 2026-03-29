<?php 
session_start();
include '../config/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("location:../index.php");
    exit;
}

// hapus semua log
mysqli_query($koneksi, "TRUNCATE TABLE tb_log_aktivitas");

header("location:log.php?msg=hapus");
exit;