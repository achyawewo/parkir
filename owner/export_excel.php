<?php
include "../config/koneksi.php";

// CEK PARAMETER
if (!isset($_GET['tgl_awal']) || !isset($_GET['tgl_akhir'])) {
    echo "Tanggal belum dipilih!";
    exit;
}

$tgl_awal = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

// HEADER EXCEL
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Parkir.xls");

// QUERY
$data = mysqli_query($koneksi, "
SELECT 
    t.id_parkir,
    k.plat_nomor,
    k.jenis_kendaraan,
    t.waktu_masuk,
    t.waktu_keluar,
    t.biaya_total
FROM tb_transaksi t
JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
WHERE t.status='keluar'
AND DATE(t.waktu_keluar) BETWEEN '$tgl_awal' AND '$tgl_akhir'
ORDER BY t.waktu_keluar DESC
");
?>

<h3>Laporan Parkir</h3>
<p>Periode: <?= $tgl_awal ?> s/d <?= $tgl_akhir ?></p>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Plat</th>
        <th>Jenis</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Total</th>
    </tr>

    <?php
    $total = 0;
    while ($d = mysqli_fetch_assoc($data)) {
        $total += $d['biaya_total'];
    ?>
        <tr>
            <td><?= $d['id_parkir'] ?></td>
            <td><?= $d['plat_nomor'] ?></td>
            <td><?= $d['jenis_kendaraan'] ?></td>
            <td><?= $d['waktu_masuk'] ?></td>
            <td><?= $d['waktu_keluar'] ?></td>
            <td><?= $d['biaya_total'] ?></td>
        </tr>
    <?php } ?>

    <tr>
        <td colspan="5"><b>Total</b></td>
        <td><b><?= $total ?></b></td>
    </tr>
</table>