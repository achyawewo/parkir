<?php
// ================= LOGOUT =================

// mulai session
session_start();

// ambil koneksi & log
include "../config/koneksi.php";
include "../config/log.php";

// ambil id user dari session (kalau ada)
$id_user = $_SESSION['id_user'] ?? null;

// simpan log logout sebelum keluar
if ($id_user) {
    simpan_log($koneksi, $id_user, "Logout dari sistem");
}

// hapus semua session
session_unset();
session_destroy();

// kembali ke halaman login
header("Location: /aplikasi_parkir/index.php");
exit;
?>