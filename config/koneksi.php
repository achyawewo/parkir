<?php
// ================= KONEKSI DATABASE =================

// alamat server (localhost = database di komputer sendiri)
$host = "localhost";

// username database (default XAMPP = root)
$user = "root";

// password database (biasanya kosong)
$pass = "";

// nama database yang dipakai
$db   = "parkir_db";

// membuat koneksi ke database MySQL
$koneksi = mysqli_connect($host, $user, $pass, $db);

// cek apakah koneksi berhasil
if (!$koneksi) {
    // kalau gagal, tampilkan pesan error
    die("Koneksi database gagal: " . mysqli_connect_error());
}
