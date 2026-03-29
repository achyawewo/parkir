<?php
// Mulai session & redirect kalau user sudah login
session_start();
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Parking - Sistem Parkir Otomatis</title>

    <!-- Import font dari Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Panggil file CSS dari folder assets -->
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>

    <!-- NAVBAR: logo di kiri, menu di kanan -->
    <nav class="navbar">
        <div class="nav-container">

            <div class="logo">
                <div class="logo-icon">🏎️</div>
                <span class="logo-text">SMART <span class="parking-text">PARKING</span></span>
            </div>

            <ul class="nav-menu">
                <li><a href="#home">HOME</a></li>
                <li><a href="#" class="login-btn" onclick="openModal(event)">LOGIN</a></li>
            </ul>

            <!-- Tombol hamburger, hanya muncul di layar mobile -->
            <div class="hamburger" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>
    </nav>


    <!-- HERO SECTION: bagian utama halaman dengan background mobil -->
    <section class="hero" id="home">

        <!-- Layer background: gambar, overlay gelap, dan efek garis grid -->
        <div class="hero-background">
            <div class="ferrari-bg-image"></div>
            <div class="overlay-gradient"></div>
            <div class="racing-lines"></div>
            <div class="grid-overlay"></div>
        </div>

        <!-- Konten teks di atas background -->
        <div class="hero-content">
            <div class="hero-text">

                <div class="hero-tag">PREMIUM PARKING SYSTEM</div>

                <h1 class="hero-title">
                    <span class="title-line-1">SMART</span>
                    <span class="title-line-2">APPLICATION</span>
                    <span class="title-line-3">PARKING</span>
                </h1>

                <p class="hero-description">
                    Sistem manajemen parkir modern untuk mobil dan motor.
                    Teknologi otomatis, keamanan terjamin, efisiensi maksimal.
                </p>

                <!-- Tombol login utama -->
                <div class="hero-buttons">
                    <button class="btn btn-primary" onclick="openModal(event)">
                        <span>LOGIN SEKARANG</span>
                        <div class="btn-arrow">→</div>
                    </button>
                </div>

            </div>
        </div>

    </section>


    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <div class="logo-icon">🏎️</div>
                <span class="logo-text">APPLICATION PARKING</span>
            </div>
            <p class="footer-text">Premium Parking System © 2026</p>
        </div>
    </footer>


    <!-- MODAL LOGIN: tersembunyi default, muncul saat tombol login diklik -->
    <div id="loginModal" class="modal">
        <div class="modal-content">

            <button class="close-modal" onclick="closeModal()">&times;</button>

            <div class="modal-logo">
                <div class="modal-logo-icon">🏎️</div>
            </div>

            <h2 class="modal-title">SISTEM LOGIN</h2>

            <!-- Form dikirim ke proses_login.php via metode POST -->
            <form action="auth/proses_login.php" method="POST">

                <div class="form-group">
                    <label class="form-label" for="username">USERNAME</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="Masukkan username"
                        required
                        autocomplete="username">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">PASSWORD</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password">
                </div>

                <button type="submit" name="login" class="btn-submit">LOGIN</button>

            </form>
        </div>
    </div>


    <!-- JS dipanggil di bawah body agar HTML sudah termuat sebelum JS dijalankan -->
    <script src="assets/js/index.js"></script>

</body>

</html>