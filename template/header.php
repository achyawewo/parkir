<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include __DIR__ . "/../config/koneksi.php";

// CEK LOGIN: kalau belum login, arahkan ke halaman utama
if (!isset($_SESSION['id_user'])) {
    header("Location: /aplikasi_parkir/index.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// CEK STATUS USER REAL-TIME: kalau dinonaktifkan, paksa logout
$user = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT status_aktif FROM tb_user WHERE id_user='$id_user'"
));

if (!$user || $user['status_aktif'] == 0) {
    session_destroy();
    echo "<script>alert('Akun Anda dinonaktifkan!'); location='/aplikasi_parkir/index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Parking</title>

    <!-- Font: Orbitron untuk judul, Rajdhani untuk teks biasa -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">

    <style>
        /* Variabel warna tema, ubah di sini ngaruh ke seluruh halaman */
        :root {
            --red:    #DC0000;
            --black:  #0A0A0A;
            --dark:   #141414;
            --card:   #1C1C1C;
            --border: rgba(220,0,0,0.25);
            --silver: #A0A0A0;
            --white:  #FFFFFF;
            --font-title: 'Orbitron', sans-serif;
            --font-body:  'Rajdhani', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background: var(--black);
            color: var(--white);
            font-size: 1rem;
        }

        /* --- NAVBAR ATAS --- */
        .navbar {
            background: var(--dark);
            border-bottom: 2px solid var(--red);
            padding: 0.9rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-family: var(--font-title);
            font-size: 1.2rem;
            letter-spacing: 2px;
            color: var(--white);
        }

        /* Kata "PARKING" berwarna merah */
        .navbar-brand span { color: var(--red); }

        .navbar-user {
            font-size: 0.9rem;
            color: var(--silver);
        }

        /* --- MENU NAVIGASI --- */
        .menu {
            background: #111;
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 0.2rem;
        }

        .menu a {
            color: var(--silver);
            text-decoration: none;
            font-family: var(--font-title);
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            padding: 0.9rem 1rem;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        /* Garis merah muncul di bawah menu saat hover */
        .menu a:hover {
            color: var(--white);
            border-bottom-color: var(--red);
        }

        /* Tombol logout di kanan menu */
        .menu .logout { margin-left: auto; color: var(--red); }
        .menu .logout:hover { color: #ff4444; border-bottom-color: #ff4444; }

        /* --- CONTAINER KONTEN --- */
        .container {
            padding: 2rem;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* Judul halaman */
        h2, h3 {
            font-family: var(--font-title);
            letter-spacing: 2px;
            color: var(--white);
            margin-bottom: 1.2rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        /* --- TABEL --- */
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        th {
            background: #1a0000;
            color: var(--red);
            font-family: var(--font-title);
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            padding: 0.9rem 1rem;
            text-align: left;
            border-bottom: 2px solid var(--red);
        }

        td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: #ddd;
            font-size: 0.95rem;
        }

        /* Baris genap sedikit lebih terang, hover efek merah samar */
        tr:nth-child(even) td { background: rgba(255,255,255,0.02); }
        tr:hover td { background: rgba(220,0,0,0.05); }

        /* --- FORM INPUT --- */
        input[type="text"],
        input[type="number"],
        input[type="password"],
        input[type="date"],
        select {
            background: #222;
            border: 1px solid var(--border);
            color: var(--white);
            padding: 0.6rem 0.9rem;
            border-radius: 4px;
            font-family: var(--font-body);
            font-size: 0.95rem;
            width: 260px;
            margin-top: 4px;
            transition: border 0.2s;
        }

        /* Border merah muncul saat input aktif/diklik */
        input:focus, select:focus {
            outline: none;
            border-color: var(--red);
            box-shadow: 0 0 8px rgba(220,0,0,0.2);
        }

        label {
            font-size: 0.85rem;
            color: var(--silver);
            letter-spacing: 1px;
            display: block;
            margin-top: 0.8rem;
        }

        /* --- TOMBOL --- */
        button {
            background: var(--red);
            color: var(--white);
            border: none;
            padding: 0.6rem 1.4rem;
            border-radius: 4px;
            font-family: var(--font-title);
            font-size: 0.8rem;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.8rem;
        }

        button:hover { background: #ff1a1a; transform: translateY(-1px); }

        /* --- LINK --- */
        a { color: var(--red); text-decoration: none; transition: color 0.2s; }
        a:hover { color: #ff4444; }

        /* Link tombol outline (untuk + Tambah ...) */
        .btn-tambah {
            display: inline-block;
            border: 1px solid var(--red);
            color: var(--red);
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            font-family: var(--font-title);
            font-size: 0.75rem;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }

        .btn-tambah:hover { background: var(--red); color: var(--white); }

        /* Badge status */
        .badge-aktif    { color: #4caf50; font-weight: 600; }
        .badge-nonaktif { color: var(--red); font-weight: 600; }

        /* --- LAIN-LAIN --- */
        hr { border: none; border-top: 1px solid var(--border); margin: 1.2rem 0; }
        p  { color: var(--silver); font-size: 0.95rem; margin-bottom: 0.8rem; }
        ul { color: var(--silver); padding-left: 1.5rem; line-height: 2; }

        /* --- FOOTER --- */
        .footer {
            text-align: center;
            padding: 1.5rem;
            border-top: 1px solid var(--border);
            color: var(--silver);
            font-size: 0.85rem;
            margin-top: 3rem;
        }
    </style>
</head>
<body>

<!-- Navbar atas: logo & info user yang login -->
<div class="navbar">
    <div class="navbar-brand">SMART <span>PARKING</span></div>
    <div class="navbar-user">
        Login: <b><?= $_SESSION['nama'] ?></b> &nbsp;|&nbsp; <?= strtoupper($_SESSION['role']) ?>
    </div>
</div>

<!-- Menu navigasi: isi link berbeda tergantung role user -->
<div class="menu">

    <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="/aplikasi_parkir/admin/dashboard.php">Dashboard</a>
        <a href="/aplikasi_parkir/admin/user.php">User</a>
        <a href="/aplikasi_parkir/admin/area.php">Area</a>
        <a href="/aplikasi_parkir/admin/slot.php">Slot</a>
        <a href="/aplikasi_parkir/admin/tarif.php">Tarif</a>
        <a href="/aplikasi_parkir/admin/kendaraan.php">Kendaraan</a>
        <a href="/aplikasi_parkir/admin/log.php">Log</a>

    <?php } elseif ($_SESSION['role'] == 'petugas') { ?>
        <a href="/aplikasi_parkir/petugas/dashboard.php">Dashboard</a>
        <a href="/aplikasi_parkir/petugas/kendaraan_masuk.php">Masuk</a>
        <a href="/aplikasi_parkir/petugas/kendaraan_keluar.php">Keluar</a>

    <?php } elseif ($_SESSION['role'] == 'owner') { ?>
        <a href="/aplikasi_parkir/owner/dashboard.php">Dashboard</a>
        <a href="/aplikasi_parkir/owner/laporan.php">Laporan</a>
    <?php } ?>

    <!-- Logout selalu di ujung kanan -->
    <a class="logout" href="/aplikasi_parkir/auth/logout.php"
       onclick="return confirm('Yakin? Anda akan diarahkan ke halaman login.')">Logout</a>
</div>

<!-- Semua konten halaman masuk ke dalam div.container ini -->
<div class="container">