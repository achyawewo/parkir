<?php
include "../config/koneksi.php";

$edit = false;

// MODE EDIT: ambil data area yang mau diedit
if (isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];
    $data_edit = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT * FROM tb_area_parkir WHERE id_area='$id_edit'"
    ));
}

// TOGGLE STATUS: ubah aktif/nonaktif area & slot-nya sekaligus
if (isset($_GET['toggle'])) {
    $id  = $_GET['toggle'];
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT status FROM tb_area_parkir WHERE id_area='$id'"
    ));

    $status_baru = ($cek['status'] == 'aktif') ? 'nonaktif' : 'aktif';

    mysqli_query($koneksi, "UPDATE tb_area_parkir SET status='$status_baru' WHERE id_area='$id'");

    // Slot ikut dinonaktifkan/diaktifkan sesuai status area
    $aktif_slot = ($status_baru == 'aktif') ? 1 : 0;
    mysqli_query($koneksi, "UPDATE tb_slot_parkir SET aktif=$aktif_slot WHERE id_area='$id'");

    header("location:area.php?msg=toggle");
    exit;
}

// Header di-include SETELAH semua redirect selesai
include "../template/header.php";
?>

<h3>Data Area Parkir</h3>

<a href="?tambah=1" class="btn-tambah">+ Tambah Area</a>

<?php if (isset($_GET['tambah']) || $edit) { ?>
    <hr>
    <form method="post" action="area_proses.php">

        <?php if ($edit) { ?>
            <input type="hidden" name="id_area" value="<?= $data_edit['id_area'] ?>">
        <?php } ?>

        <label>Nama Area</label>
        <input type="text" name="nama" value="<?= $edit ? $data_edit['nama_area'] : '' ?>" required>

        <label>Kapasitas</label>
        <input type="number" name="kapasitas" value="<?= $edit ? $data_edit['kapasitas'] : '' ?>" required>

        <label>Jenis Kendaraan</label>
        <select name="jenis_khusus" required>
            <option value="roda 2"  <?= ($edit && $data_edit['jenis_khusus'] == 'roda 2')  ? 'selected' : '' ?>>Roda 2</option>
            <option value="roda 4"  <?= ($edit && $data_edit['jenis_khusus'] == 'roda 4')  ? 'selected' : '' ?>>Roda 4</option>
            <option value="roda >4" <?= ($edit && $data_edit['jenis_khusus'] == 'roda >4') ? 'selected' : '' ?>>Roda >4</option>
        </select>

        <br>
        <?php if ($edit) { ?>
            <button name="update">💾 Update</button>
        <?php } else { ?>
            <button name="simpan">Simpan</button>
        <?php } ?>

    </form>
    <hr>
<?php } ?>

<?php
// Tampilkan alert sesuai pesan dari redirect
if (isset($_GET['msg'])) {
    $pesan = [
        'berhasil_tambah'  => 'Area & Slot berhasil dibuat',
        'berhasil_update'  => 'Area & Slot berhasil disinkronkan',
        'toggle'           => 'Status area berhasil diubah',
    ];
    if (isset($pesan[$_GET['msg']])) {
        echo "<script>alert('{$pesan[$_GET['msg']]}');</script>";
    }
}
?>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Kapasitas</th>
        <th>Terisi</th>
        <th>Jenis</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no   = 1;
    $data = mysqli_query($koneksi, "SELECT * FROM tb_area_parkir ORDER BY nama_area ASC");
    while ($d = mysqli_fetch_array($data)) {
        $badge = $d['status'] == 'aktif'
            ? "<span class='badge-aktif'>Aktif</span>"
            : "<span class='badge-nonaktif'>Nonaktif</span>";
        $label_toggle = $d['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan';
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $d['nama_area'] ?></td>
            <td><?= $d['kapasitas'] ?></td>
            <td><?= $d['terisi'] ?></td>
            <td><?= $d['jenis_khusus'] ?></td>
            <td><?= $badge ?></td>
            <td>
                <a href="?edit=<?= $d['id_area'] ?>">Edit</a> |
                <a href="?toggle=<?= $d['id_area'] ?>"
                   onclick="return confirm('Ubah status area ini?')">
                    <?= $label_toggle ?>
                </a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php include "../template/footer.php"; ?>