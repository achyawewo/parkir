<?php 
include "../config/koneksi.php";

// =======================
// TOGGLE SLOT
// =======================
if (isset($_GET['toggle'])) {

    $id = $_GET['toggle'];
    $set = $_GET['set'];

    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT a.status
        FROM tb_slot_parkir s
        JOIN tb_area_parkir a ON s.id_area = a.id_area
        WHERE s.id_slot = '$id'
    "));

    if ($cek['status'] == 'nonaktif') {
        echo "<script>alert('Area sedang nonaktif! Slot tidak bisa diubah');history.back();</script>";
        exit;
    }

    mysqli_query($koneksi, "
        UPDATE tb_slot_parkir SET aktif = '$set' WHERE id_slot = '$id'
    ");

    header("location:slot.php");
    exit;
}