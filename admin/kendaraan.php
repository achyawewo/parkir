<?php include "../template/header.php";
include "../config/koneksi.php"; ?>

<h2>Kendaraan Sedang Parkir</h2>

<table border="1" cellpadding="6">
    <tr>
        <th>Plat</th>
        <th>Jenis</th>
        <th>Area</th>
        <th>Slot</th>
        <th>Waktu Masuk</th>
        <th>Aksi</th>
    </tr>

    <?php
    $aktif = mysqli_query($koneksi, "
SELECT t.id_parkir, k.plat_nomor, k.jenis_kendaraan, a.nama_area, s.kode_slot, t.waktu_masuk
FROM tb_transaksi t
JOIN tb_kendaraan k ON t.id_kendaraan=k.id_kendaraan
JOIN tb_area_parkir a ON t.id_area=a.id_area
JOIN tb_slot_parkir s ON t.id_slot=s.id_slot
WHERE t.status='masuk'
ORDER BY t.waktu_masuk DESC
");

    while ($d = mysqli_fetch_assoc($aktif)) {
    ?>
        <tr>
            <td><?= $d['plat_nomor'] ?></td>
            <td><?= $d['jenis_kendaraan'] ?></td>
            <td><?= $d['nama_area'] ?></td>
            <td><?= $d['kode_slot'] ?></td>
            <td><?= $d['waktu_masuk'] ?></td>
            <td>
                <a href="kendaraan_proses.php?keluar=<?= $d['id_parkir'] ?>" onclick="return confirm('Keluarkan kendaraan ini?')">
                    Keluarkan
                </a>
            </td>
        </tr>
    <?php } ?>
</table>

<br><br>

<h2>Riwayat Parkir</h2>

<table border="1" cellpadding="6">
    <tr>
        <th>Plat</th>
        <th>Jenis</th>
        <th>Area</th>
        <th>Slot</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Total Bayar</th>
    </tr>

    <?php
    $riwayat = mysqli_query($koneksi, "
SELECT k.plat_nomor, k.jenis_kendaraan, a.nama_area, s.kode_slot,
t.waktu_masuk, t.waktu_keluar, t.biaya_total
FROM tb_transaksi t
JOIN tb_kendaraan k ON t.id_kendaraan=k.id_kendaraan
JOIN tb_area_parkir a ON t.id_area=a.id_area
JOIN tb_slot_parkir s ON t.id_slot=s.id_slot
WHERE t.status='keluar'
ORDER BY t.id_parkir DESC
LIMIT 50
");

    while ($r = mysqli_fetch_assoc($riwayat)) {
    ?>
        <tr>
            <td><?= $r['plat_nomor'] ?></td>
            <td><?= $r['jenis_kendaraan'] ?></td>
            <td><?= $r['nama_area'] ?></td>
            <td><?= $r['kode_slot'] ?></td>
            <td><?= $r['waktu_masuk'] ?></td>
            <td><?= $r['waktu_keluar'] ?></td>
            <td>Rp <?= number_format($r['biaya_total'], 0, ',', '.') ?></td>
        </tr>
    <?php } ?>
</table>

<?php include "../template/footer.php"; ?>