<?php
session_start();
include "../config/koneksi.php";
include "../config/log.php";

// ambil id user dari session
$id_user = $_SESSION['id_user'];

// ================= AMBIL INPUT =================
// strtoupper → biar huruf besar semua (standar plat Indonesia)
// trim → hapus spasi berlebih
$plat  = strtoupper(trim($_POST['plat_nomor']));
$jenis = $_POST['jenis_kendaraan'];
$warna = $_POST['warna'];
$pemilik = $_POST['pemilik'];


// ================= VALIDASI FORMAT PLAT =================
// Regex ini memastikan format seperti: D 1234 AB / B1234CD
if (!preg_match("/^[A-Z]{1,2}\s?[0-9]{1,4}\s?[A-Z]{0,3}$/", $plat)) {
    echo "<script>alert('Format plat tidak valid! Contoh: D 1234 AB');history.back();</script>";
    exit;
}


// ================= CEK DUPLIKAT PLAT =================
// 🔥 LOGIKA PENTING:
// Cek apakah plat ini masih ADA di parkiran (status = masuk)
// Kalau iya → tidak boleh masuk lagi

$cek_plat = mysqli_query($koneksi, "
    SELECT t.id_parkir 
    FROM tb_transaksi t
    JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
    WHERE k.plat_nomor = '$plat'
    AND t.status = 'masuk'
");

if (mysqli_num_rows($cek_plat) > 0) {
    echo "<script>alert('Kendaraan dengan plat ini masih berada di dalam parkiran!');history.back();</script>";
    exit;
}


// ================= CARI AREA + SLOT =================
// ambil semua area yang sesuai jenis kendaraan & aktif
$area_query = mysqli_query($koneksi, "
    SELECT * FROM tb_area_parkir
    WHERE jenis_khusus='$jenis'
    AND status='aktif'
    ORDER BY nama_area ASC
");

$found = false;

// looping semua area untuk cari slot kosong
while ($area = mysqli_fetch_assoc($area_query)) {

    // ambil slot kosong pertama (urutan kecil dulu)
    $slot = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT id_slot,kode_slot
        FROM tb_slot_parkir
        WHERE id_area='{$area['id_area']}'
        AND status='kosong'
        AND aktif=1
        ORDER BY CAST(SUBSTRING(kode_slot,2) AS UNSIGNED) ASC
        LIMIT 1
    "));

    // kalau ketemu slot kosong → pakai
    if ($slot) {
        $found = true;
        break;
    }
}


// ================= CEK JIKA TIDAK ADA SLOT =================
if (!$found) {
    echo "<script>alert('Semua area penuh atau tidak tersedia!');history.back();</script>";
    exit;
}


// ================= SIMPAN DATA =================
$id_area = $area['id_area'];
$id_slot = $slot['id_slot'];


// ================= SIMPAN KENDARAAN =================
// tetap simpan walaupun plat sama pernah masuk sebelumnya
// karena histori parkir boleh sama
mysqli_query($koneksi, "
INSERT INTO tb_kendaraan
(plat_nomor,jenis_kendaraan,warna,pemilik,id_user)
VALUES('$plat','$jenis','$warna','$pemilik','$id_user')
");

$id_kendaraan = mysqli_insert_id($koneksi);


// ================= AMBIL TARIF =================
$tarif = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT id_tarif FROM tb_tarif
    WHERE jenis_kendaraan='$jenis' LIMIT 1
"));


// ================= SIMPAN TRANSAKSI =================
mysqli_query($koneksi, "
INSERT INTO tb_transaksi
(id_kendaraan,waktu_masuk,id_tarif,status,id_user,id_area,id_slot)
VALUES('$id_kendaraan',NOW(),'{$tarif['id_tarif']}','masuk','$id_user','$id_area','$id_slot')
");


// ================= UPDATE SLOT =================
// slot jadi terisi
mysqli_query($koneksi, "
UPDATE tb_slot_parkir SET status='terisi'
WHERE id_slot='$id_slot'
");


// ================= UPDATE AREA =================
// jumlah terisi bertambah
mysqli_query($koneksi, "
UPDATE tb_area_parkir SET terisi=terisi+1
WHERE id_area='$id_area'
");


// ================= SIMPAN LOG =================
// catat aktivitas petugas
simpan_log(
    $koneksi,
    $id_user,
    "Kendaraan masuk: $plat → {$area['nama_area']} ({$slot['kode_slot']})"
);


// ================= OUTPUT =================
echo "<script>
alert('Masuk - {$area['nama_area']} ({$slot['kode_slot']})');
window.location='kendaraan_masuk.php';
</script>";