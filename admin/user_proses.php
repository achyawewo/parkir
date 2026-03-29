<?php
session_start();
include "../config/koneksi.php";

// ================= TAMBAH USER =================
if (isset($_POST['tambah'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // CEK USERNAME DUPLIKAT
    $cek = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!');history.back();</script>";
        exit;
    }

    mysqli_query($koneksi, "
    INSERT INTO tb_user (nama_lengkap, username, password, role, status_aktif)
    VALUES ('$nama','$username','$password','$role',1)
    ");

    echo "<script>alert('User berhasil ditambahkan');location='user.php';</script>";
}


// ================= UPDATE USER =================
if (isset($_POST['update'])) {

    $id = $_POST['id_user'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // CEK USERNAME DUPLIKAT (selain dirinya)
    $cek = mysqli_query($koneksi, "
    SELECT * FROM tb_user 
    WHERE username='$username' AND id_user!='$id'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!');history.back();</script>";
        exit;
    }

    // UPDATE TANPA PASSWORD
    mysqli_query($koneksi, "
    UPDATE tb_user SET 
        nama_lengkap='$nama',
        username='$username',
        role='$role'
    WHERE id_user='$id'
    ");

    // UPDATE PASSWORD (kalau diisi)
    if (!empty($_POST['password'])) {
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        mysqli_query($koneksi, "
        UPDATE tb_user SET password='$pass' WHERE id_user='$id'
        ");
    }

    echo "<script>alert('Data berhasil diupdate');location='user.php';</script>";
}


// ================= TOGGLE STATUS =================
if (isset($_GET['toggle'])) {

    $id = $_GET['toggle'];
    $status = $_GET['status'];

    // ❌ CEGAH NONAKTIFKAN DIRI SENDIRI
    if ($id == $_SESSION['id_user']) {
        echo "<script>alert('Tidak bisa menonaktifkan akun sendiri!');history.back();</script>";
        exit;
    }

    mysqli_query($koneksi, "
    UPDATE tb_user SET status_aktif='$status' WHERE id_user='$id'
    ");

    header("location:user.php");
}
