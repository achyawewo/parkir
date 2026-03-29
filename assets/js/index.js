// Hamburger menu toggle
// Saat tombol ☰ diklik, tambah/hapus class 'active' agar menu muncul/hilang di mobile
function toggleMenu() {
    const navMenu   = document.querySelector('.nav-menu');
    const hamburger = document.querySelector('.hamburger');
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
}

// Tutup menu mobile otomatis saat salah satu link diklik
document.querySelectorAll('.nav-menu a:not(.login-btn)').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.nav-menu').classList.remove('active');
        document.querySelector('.hamburger').classList.remove('active');
    });
});


// Buka modal login
// e.preventDefault() mencegah link href="#" scroll ke atas halaman
function openModal(e) {
    e.preventDefault();
    document.getElementById('loginModal').classList.add('active');
    document.body.style.overflow = 'hidden'; // Nonaktifkan scroll halaman saat modal terbuka
}

// Tutup modal login
function closeModal() {
    document.getElementById('loginModal').classList.remove('active');
    document.body.style.overflow = 'auto'; // Aktifkan kembali scroll halaman
}

// Tutup modal saat user klik di luar kotak modal
window.onclick = function(event) {
    const modal = document.getElementById('loginModal');
    if (event.target === modal) closeModal();
};

// Tutup modal saat user tekan tombol Escape di keyboard
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
});


// Smooth scroll
// Saat klik link yang mengarah ke #id tertentu, scroll halus ke elemen tersebut
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});


// Navbar scroll effect
// Saat halaman di-scroll lebih dari 100px, tambah bayangan merah pada navbar
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    if (window.pageYOffset > 100) {
        navbar.style.background = 'rgba(10, 10, 10, 0.98)';
        navbar.style.boxShadow  = '0 4px 20px rgba(220, 0, 0, 0.3)';
    } else {
        navbar.style.background = 'rgba(10, 10, 10, 0.95)';
        navbar.style.boxShadow  = 'none';
    }
});


// Tahun otomatis di footer
// Mengambil tahun dari sistem, tidak perlu diubah manual tiap tahun baru
const footerText = document.querySelector('.footer-text');
if (footerText) {
    footerText.textContent = `Premium Parking System © ${new Date().getFullYear()}`;
}