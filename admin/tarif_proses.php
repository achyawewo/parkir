<?php 
include "../config/koneksi.php";

if (isset($_POST['update'])) {
    mysqli_query($koneksi, "
        UPDATE tb_tarif 
        SET tarif_per_jam='$_POST[tarif]'
        WHERE id_tarif='$_POST[id]'
    ");
}

header("location:tarif.php");