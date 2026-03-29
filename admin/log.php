<?php
include "../template/header.php";
include "../config/koneksi.php";

$data = mysqli_query($koneksi, "
    SELECT l.*, u.nama_lengkap, u.role
    FROM tb_log_aktivitas l
    JOIN tb_user u ON l.id_user=u.id_user
    ORDER BY l.waktu DESC
");
?>

<h3>Log Aktivitas</h3>

<table border="1" cellpadding="6">
    <tr>
        <th>User</th>
        <th>Role</th>
        <th>Aktivitas</th>
        <th>Waktu</th>
    </tr>

    <?php while ($d = mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?= $d['nama_lengkap'] ?></td>
            <td><?= $d['role'] ?></td>
            <td><?= $d['aktivitas'] ?></td>
            <td><?= $d['waktu'] ?></td>
        </tr>
    <?php } ?>
</table>

<br>

<a href="log_proses.php"
    onclick="return confirm('Yakin ingin menghapus semua log?')"
    style="background:red;color:white;padding:8px 12px;text-decoration:none;border-radius:5px;">
    Bersihkan Log
</a>

<?php include "../template/footer.php"; ?>