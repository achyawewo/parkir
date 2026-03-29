<?php
function simpan_log($koneksi, $id_user, $aktivitas)
{
    if (empty($id_user)) return;

    $aktivitas = mysqli_real_escape_string($koneksi, $aktivitas);

    mysqli_query($koneksi, "
        INSERT INTO tb_log_aktivitas (id_user, aktivitas)
        VALUES ('$id_user','$aktivitas')
    ");
}
