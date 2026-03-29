<?php
include "../template/header.php";
include "../config/koneksi.php";
?>

<h3>Data Tarif Parkir</h3>

<table border="1" cellpadding="6">
    <tr>
        <th>Jenis Kendaraan</th>
        <th>Tarif/Jam</th>
        <th>Aksi</th>
    </tr>

    <?php
    $data = mysqli_query($koneksi, "SELECT * FROM tb_tarif");

    while ($d = mysqli_fetch_array($data)) {
    ?>
        <tr>
            <td><?= $d['jenis_kendaraan'] ?></td>
            <td>Rp <?= number_format($d['tarif_per_jam']) ?></td>
            <td>
                <a href="tarif.php?edit=<?= $d['id_tarif'] ?>">Edit</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $e = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM tb_tarif WHERE id_tarif='$id'
"));
?>

    <hr>

    <h3>Edit Tarif</h3>

    <form method="post" action="tarif_proses.php">
        <input type="hidden" name="id" value="<?= $e['id_tarif'] ?>">

        Jenis Kendaraan:<br>
        <b><?= $e['jenis_kendaraan'] ?></b><br><br>

        Tarif per jam:<br>
        <input type="number" name="tarif" value="<?= $e['tarif_per_jam'] ?>" required>
        <br><br>

        <button name="update">Simpan</button>
    </form>

<?php } ?>

<?php include "../template/footer.php"; ?>