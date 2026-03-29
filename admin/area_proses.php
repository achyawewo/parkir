<?php
session_start();
include "../config/koneksi.php";
include "../config/log.php";

// SYNC SLOT: tambah slot kalau kapasitas naik, hapus kalau kapasitas turun
function syncSlot($koneksi, $id_area, $nama, $kapasitas) {

    $slot   = mysqli_query($koneksi, "SELECT * FROM tb_slot_parkir WHERE id_area='$id_area' ORDER BY id_slot ASC");
    $jumlah = mysqli_num_rows($slot);

    // Kapasitas naik: tambah slot baru
    if ($jumlah < $kapasitas) {
        for ($i = $jumlah + 1; $i <= $kapasitas; $i++) {
            $kode = strtoupper(substr($nama, -1)) . $i;
            mysqli_query($koneksi, "INSERT INTO tb_slot_parkir (id_area, kode_slot, status, aktif) VALUES ('$id_area','$kode','kosong',1)");
        }
    }

    // Kapasitas turun: hapus slot yang melebihi kapasitas baru (hanya yang kosong)
    if ($jumlah > $kapasitas) {
        mysqli_query($koneksi, "
            DELETE FROM tb_slot_parkir
            WHERE id_area='$id_area'
            AND status='kosong'
            ORDER BY id_slot DESC
            LIMIT " . ($jumlah - $kapasitas) . "
        ");
    }
}


// TAMBAH AREA
if (isset($_POST['simpan'])) {

    $nama      = $_POST['nama'];
    $kapasitas = (int)$_POST['kapasitas'];
    $jenis     = $_POST['jenis_khusus'];

    // Cek duplikat nama area
    $cek = mysqli_query($koneksi, "SELECT * FROM tb_area_parkir WHERE nama_area='$nama'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Area sudah ada!');history.back();</script>";
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO tb_area_parkir (nama_area, kapasitas, terisi, jenis_khusus, status) VALUES ('$nama','$kapasitas',0,'$jenis','aktif')");

    $id_area = mysqli_insert_id($koneksi);

    syncSlot($koneksi, $id_area, $nama, $kapasitas);

    simpan_log($koneksi, $_SESSION['id_user'], "Menambah area + slot");

    header("location:area.php?msg=berhasil_tambah");
    exit;
}


// UPDATE AREA
if (isset($_POST['update'])) {

    $id        = $_POST['id_area'];
    $nama      = $_POST['nama'];
    $kapasitas = (int)$_POST['kapasitas'];
    $jenis     = $_POST['jenis_khusus'];

    mysqli_query($koneksi, "UPDATE tb_area_parkir SET nama_area='$nama', kapasitas='$kapasitas', jenis_khusus='$jenis' WHERE id_area='$id'");

    syncSlot($koneksi, $id, $nama, $kapasitas);

    simpan_log($koneksi, $_SESSION['id_user'], "Update area + sinkron slot");

    header("location:area.php?msg=berhasil_update");
    exit;
}