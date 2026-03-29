<?php
session_start();
include "../config/koneksi.php";
include "../template/header.php"
?>

<form action="proses_kendaraan_masuk.php" method="POST">
    Plat Nomor<br>
    <input type="text" name="plat_nomor" placeholder="Contoh: D 1234 AB" style="text-transform:uppercase;" required><br><br>

    Jenis Kendaraan<br>
    <select name="jenis_kendaraan" required>
        <option value="">-- Pilih --</option>
        <option value="roda 2">Roda 2</option>
        <option value="roda 4">Roda 4</option>
        <option value="roda >4">Roda >4</option>
    </select><br><br>

    Warna Kendaraan<br>
    <input type="text" name="warna" required><br><br>

    Nama Pemilik<br>
    <input type="text" name="pemilik" required><br><br>

    <button type="submit">Simpan</button>
</form>

<hr>

<?php
$data = mysqli_query($koneksi, "
    SELECT 
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
    ORDER BY t.waktu_masuk DESC
");
?>

<h3>Data Kendaraan Sedang Parkir</h3>

<table border="1" cellpadding="6">
    <tr>
        <th>Plat</th>
        <th>Jenis</th>
        <th>Area</th>
        <th>Slot</th>
        <th>Waktu Masuk</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?= $row['plat_nomor'] ?></td>
            <td><?= $row['jenis_kendaraan'] ?></td>
            <td><?= $row['nama_area'] ?></td>
            <td><?= $row['kode_slot'] ?></td>
            <td><?= $row['waktu_masuk'] ?></td>
        </tr>
    <?php } ?>
</table>

<hr>

<?php include "../template/footer.php"; ?>