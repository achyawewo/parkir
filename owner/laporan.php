<?php
// Cek role dulu sebelum include header (hindari headers already sent)
session_start();
if ($_SESSION['role'] != 'owner') {
    header("location:../index.php");
    exit;
}

include "../config/koneksi.php";
include "../template/header.php";

$tgl_awal  = $_GET['tgl_awal']  ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';
?>

<h2>Laporan Pendapatan Parkir</h2>

<form method="GET">
    <label>Tanggal Awal</label>
    <input type="date" name="tgl_awal" value="<?= $tgl_awal ?>" required>

    <label>Tanggal Akhir</label>
    <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>" required>

    <br>
    <button type="submit">Tampilkan</button>
</form>

<hr>

<?php if ($tgl_awal && $tgl_akhir) { ?>

    <?php
    $data = mysqli_query($koneksi, "
        SELECT t.id_parkir, k.plat_nomor, k.jenis_kendaraan,
               t.waktu_masuk, t.waktu_keluar, t.biaya_total
        FROM tb_transaksi t
        JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
        WHERE t.status='keluar'
        AND DATE(t.waktu_keluar) BETWEEN '$tgl_awal' AND '$tgl_akhir'
        ORDER BY t.waktu_keluar DESC
    ");
    ?>

    <h3>Periode: <?= $tgl_awal ?> s/d <?= $tgl_akhir ?></h3>

    <?php if (mysqli_num_rows($data) == 0) { ?>
        <p>Tidak ada data pada periode ini.</p>

    <?php } else {
        $total_semua = $total_roda2 = $total_roda4 = $total_besar = 0;
        $rows = mysqli_fetch_all($data, MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $total_semua += $row['biaya_total'];
            if ($row['jenis_kendaraan'] == 'roda 2')  $total_roda2 += $row['biaya_total'];
            if ($row['jenis_kendaraan'] == 'roda 4')  $total_roda4 += $row['biaya_total'];
            if ($row['jenis_kendaraan'] == 'roda >4') $total_besar += $row['biaya_total'];
        }
    ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Plat</th>
                <th>Jenis</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Total</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td><?= $row['id_parkir'] ?></td>
                    <td><?= $row['plat_nomor'] ?></td>
                    <td><?= $row['jenis_kendaraan'] ?></td>
                    <td><?= $row['waktu_masuk'] ?></td>
                    <td><?= $row['waktu_keluar'] ?></td>
                    <td>Rp <?= number_format($row['biaya_total']) ?></td>
                </tr>
            <?php } ?>
        </table>

        <h3>Ringkasan Pendapatan</h3>
        <p>Total Roda 2 &nbsp;: Rp <?= number_format($total_roda2) ?></p>
        <p>Total Roda 4 &nbsp;: Rp <?= number_format($total_roda4) ?></p>
        <p>Total Roda &gt;4 : Rp <?= number_format($total_besar) ?></p>
        <hr>
        <h2>Total Keseluruhan : Rp <?= number_format($total_semua) ?></h2>

        <!-- Tombol export hanya muncul kalau data ada, tanpa confirm -->
        <a href="export_excel.php?tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>"
            class="btn-tambah">Export Excel</a>

    <?php } ?>

<?php } ?>

<?php include "../template/footer.php"; ?>