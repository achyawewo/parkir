<?php
session_start();
include "../config/koneksi.php";
include "../config/log.php";

// pastikan admin login
if (!isset($_SESSION['id_user'])) {
    header("location:../index.php");
    exit;
}

$id_user = $_SESSION['id_user'];

if (isset($_GET['keluar'])) {

    $id = $_GET['keluar'];

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT t.waktu_masuk, t.id_area, t.id_slot, 
               k.plat_nomor, s.kode_slot, tr.tarif_per_jam
        FROM tb_transaksi t
        JOIN tb_kendaraan k ON t.id_kendaraan=k.id_kendaraan
        JOIN tb_slot_parkir s ON t.id_slot=s.id_slot
        JOIN tb_tarif tr ON t.id_tarif=tr.id_tarif
        WHERE t.id_parkir='$id' AND t.status='masuk'
    "));

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan');window.location='kendaraan.php';</script>";
        exit;
    }

    $plat = $data['plat_nomor'];
    $kode_slot = $data['kode_slot'];

    $masuk = strtotime($data['waktu_masuk']);
    $keluar = time();

    $durasi = ceil(($keluar - $masuk) / 3600);
    if ($durasi < 1) $durasi = 1;

    $total = $durasi * $data['tarif_per_jam'];

    mysqli_query($koneksi, "
        UPDATE tb_transaksi 
        SET waktu_keluar = NOW(),
            durasi_jam = '$durasi',
            biaya_total = '$total',
            status='keluar'
        WHERE id_parkir='$id'
    ");

    mysqli_query($koneksi, "
        UPDATE tb_slot_parkir 
        SET status='kosong'
        WHERE id_slot='{$data['id_slot']}'
    ");

    mysqli_query($koneksi, "
        UPDATE tb_area_parkir 
        SET terisi = terisi - 1
        WHERE id_area='{$data['id_area']}'
    ");

    simpan_log(
        $koneksi,
        $id_user,
        "Mengeluarkan kendaraan $plat dari slot $kode_slot"
    );

    echo "<script>
        alert('Kendaraan keluar. Total bayar: Rp " . number_format($total, 0, ',', '.') . "');
        window.location='kendaraan.php';
    </script>";
}
