<?php
session_start(); 

include "../config/koneksi.php";
include "../config/log.php";

if (!isset($_SESSION['id_user'])) {
    die("User belum login");
}

$id_user = $_SESSION['id_user'];


if(isset($_GET['id'])){

    $id = $_GET['id'];

    // ambil data kendaraan + slot
    $data = mysqli_query($koneksi, "
        SELECT 
            t.waktu_masuk,
            t.id_area,
            t.id_slot,
            tr.tarif_per_jam,
            k.plat_nomor,
            s.kode_slot
        FROM tb_transaksi t
        JOIN tb_tarif tr ON t.id_tarif = tr.id_tarif
        JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
        JOIN tb_slot_parkir s ON t.id_slot = s.id_slot
        WHERE t.id_parkir='$id'
        AND t.status='masuk'
    ");

    $d = mysqli_fetch_assoc($data);

    if(!$d){
        die("Data tidak ditemukan");
    }

    $plat      = $d['plat_nomor'];
    $kode_slot = $d['kode_slot'];

    $masuk   = strtotime($d['waktu_masuk']);
    $keluar  = time();
    $durasi  = ceil(($keluar - $masuk) / 3600);
    if($durasi < 1) $durasi = 1;

    $total   = $durasi * $d['tarif_per_jam'];
    $id_area = $d['id_area'];
    $id_slot = $d['id_slot'];

    // update transaksi
    mysqli_query($koneksi, "
        UPDATE tb_transaksi SET
        waktu_keluar = NOW(),
        durasi_jam = '$durasi',
        biaya_total = '$total',
        status='keluar'
        WHERE id_parkir='$id'
    ");

    // update area
    mysqli_query($koneksi, "
        UPDATE tb_area_parkir
        SET terisi = terisi - 1
        WHERE id_area='$id_area'
    ");

    // kosongkan slot
    mysqli_query($koneksi, "
        UPDATE tb_slot_parkir
        SET status='kosong'
        WHERE id_slot='$id_slot'
    ");

    //  simpan log SETELAH data tersedia
    simpan_log($koneksi, $id_user, "Kendaraan keluar: $plat dari Slot $kode_slot");

    echo "<script>
    window.location='cetak_struk.php?id=$id';
    </script>";

    exit;
}

$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

$data = mysqli_query($koneksi, "
    SELECT 
        t.id_parkir,
        k.plat_nomor,
        k.jenis_kendaraan,
        s.kode_slot,
        a.nama_area,
        t.waktu_masuk
    FROM tb_transaksi t
    JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
    JOIN tb_slot_parkir s ON t.id_slot = s.id_slot
    JOIN tb_area_parkir a ON t.id_area = a.id_area
    WHERE t.status='masuk'
    AND k.plat_nomor LIKE '%$cari%'
    ORDER BY t.waktu_masuk ASC
");

echo "<table border='1' cellpadding='6'>
<tr>
<th>Plat</th>
<th>Jenis</th>
<th>Area</th>
<th>Slot</th>
<th>Waktu Masuk</th>
<th>Aksi</th>
</tr>";

while($row = mysqli_fetch_assoc($data)){
    echo "<tr>
        <td>{$row['plat_nomor']}</td>
        <td>{$row['jenis_kendaraan']}</td>
        <td>{$row['nama_area']}</td>
        <td>{$row['kode_slot']}</td>
        <td>{$row['waktu_masuk']}</td>
        <td><a href='proses_kendaraan_keluar.php?id={$row['id_parkir']}'>Keluar</a></td>
    </tr>";
}

echo "</table>";
?>