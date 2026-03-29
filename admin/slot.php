<?php
include "../template/header.php";
include "../config/koneksi.php";
?>

<h3>Data Slot Parkir</h3>

<p style="color:gray;">
    Slot dibuat otomatis berdasarkan kapasitas area.
</p>

<hr>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Kode Slot</th>
        <th>Area</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;

    $data = mysqli_query($koneksi, "
    SELECT s.*, a.nama_area, a.status as status_area
    FROM tb_slot_parkir s
    JOIN tb_area_parkir a ON s.id_area=a.id_area
    ORDER BY a.nama_area ASC, CAST(SUBSTRING(s.kode_slot,2) AS UNSIGNED) ASC
");

    while ($d = mysqli_fetch_assoc($data)) {
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $d['kode_slot'] ?></td>
            <td><?= $d['nama_area'] ?></td>
            <td><?= $d['status'] ?></td>
            <td>

                <?php if ($d['status_area'] == 'nonaktif') { ?>
                    <span style="color:red;">Area Nonaktif</span>

                <?php } else { ?>

                    <?php if ($d['aktif'] == 1) { ?>
                        <a href="slot_proses.php?toggle=<?= $d['id_slot'] ?>&set=0"
                            onclick="return confirm('Nonaktifkan slot ini?')">
                            Nonaktifkan
                        </a>
                    <?php } else { ?>
                        <a href="slot_proses.php?toggle=<?= $d['id_slot'] ?>&set=1"
                            onclick="return confirm('Aktifkan slot ini?')">
                            Aktifkan
                        </a>
                    <?php } ?>

                <?php } ?>

            </td>
        </tr>
    <?php } ?>
</table>

<?php include "../template/footer.php"; ?>