<?php
// ================= PROSES LOGIN =================

// memulai session (wajib untuk login)
session_start();

// ambil koneksi & function log
include "../config/koneksi.php";
include "../config/log.php";

// ambil data dari form login
$user = $_POST['username'];
$pass = $_POST['password'];

// ambil data user dari database berdasarkan username
$q = mysqli_query($koneksi, "
    SELECT * FROM tb_user 
    WHERE username='$user'
");

// ubah hasil query jadi array
$data = mysqli_fetch_assoc($q);

// kalau user tidak ditemukan
if (!$data) {
    echo "<script>alert('User tidak ditemukan');history.back();</script>";
    exit;
}

// kalau user dinonaktif
if ($data['status_aktif'] == 0) {
    echo "<script>alert('User dinonaktifkan oleh admin');history.back();</script>";
    exit;
}

// cek password
if (
    password_verify($pass, $data['password']) || // kalau sudah hash
    $pass == $data['password']                  // kalau masih plain text
) {

    // simpan data user ke session
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['nama'] = $data['nama_lengkap'];
    $_SESSION['role'] = $data['role'];

    // kalau password masih belum hash → ubah ke hash
    if (!password_verify($pass, $data['password'])) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        mysqli_query($koneksi, "
            UPDATE tb_user SET password='$hash'
            WHERE id_user='{$data['id_user']}'
        ");
    }

    // simpan log login
    simpan_log($koneksi, $data['id_user'], "Login ke sistem");

    // arahkan ke halaman sesuai role
    if ($data['role'] == 'admin') {
        header("location:../admin/dashboard.php");
    } elseif ($data['role'] == 'petugas') {
        header("location:../petugas/dashboard.php");
    } else {
        header("location:../owner/dashboard.php");
    }

    exit;

} else {
    // kalau password salah
    echo "<script>alert('Password salah');history.back();</script>";
}
?>