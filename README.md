Smart Parking System

Sistem manajemen parkir berbasis web yang dibuat untuk mempermudah pengelolaan kendaraan masuk dan keluar secara otomatis, aman, dan efisien.

---

## Fitur Utama

- Login multi-role (Admin, Petugas, Owner)
- Manajemen area parkir & slot otomatis
- Sistem kendaraan masuk & keluar
- Perhitungan tarif otomatis
- Monitoring kendaraan real-time
- Log aktivitas user
- Export laporan (Owner)

---

## Role Pengguna

### Admin
- Kelola user
- Kelola area parkir
- Kelola slot parkir
- Kelola tarif
- Monitoring kendaraan
- Melihat log aktivitas

### Petugas
- Input kendaraan masuk
- Proses kendaraan keluar
- Cetak struk

### Owner
- Melihat laporan
- Export data ke Excel

---

## Teknologi yang Digunakan

- PHP Native
- MySQL
- HTML, CSS, JavaScript
- XAMPP

---

## Struktur Folder

📁 admin → kontrol penuh sistem
📁 petugas → operasional parkir
📁 owner → laporan & analisis
📁 config → koneksi database
📁 template → UI layout
📁 database → file SQL
📁 assets → styling & JS

---

## Cara Menjalankan

1. Install XAMPP  
2. Pindahkan folder ke: C:/xampp/htdocs/
3. Import database:
- buka phpMyAdmin  
- import `parkir_db.sql`  
4. Jalankan di browser: http://localhost/aplikasi_parkir

  
---

## Akun Login (Contoh)

| Role             | Username | Password |
|------------------|----------|----------|
| Administrator    | admin    | admin    |
| Petugas Parkir   | petugas  | petugas  |
| Owner            | owner    | owner    |

---

## Catatan

Project ini dibuat untuk kebutuhan Uji Kompetensi (Ujikom) dengan tujuan mengimplementasikan sistem parkir berbasis web secara real-time + syarat kelulusann :D

---

## Author

**Nama:** Salsa Zahra Tussyta
**Kelas:** XII RPL 2
**Sekolah:** SMKN 2 Bandung
