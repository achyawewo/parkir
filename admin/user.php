<?php
session_start();
include "../template/header.php";
include "../config/koneksi.php";

$edit = false;

if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_user WHERE id_user='$id'"));
}
?>

<h3>Data User</h3>

<a href="?tambah=1">+ Tambah User</a>
<hr>

<?php if (isset($_GET['tambah']) || $edit) { ?>

    <form method="POST" action="user_proses.php">

        <input type="hidden" name="id_user" value="<?= $edit ? $data['id_user'] : '' ?>">

        Nama:<br>
        <input type="text" name="nama" value="<?= $edit ? $data['nama_lengkap'] : '' ?>" required><br><br>

        Username:<br>
        <input type="text" name="username" value="<?= $edit ? $data['username'] : '' ?>" required><br><br>

        Password:<br>
        <input type="password" name="password">
        <small>Kosongkan jika tidak diubah</small><br><br>

        Role:<br>
        <select name="role">
            <option value="admin" <?= ($edit && $data['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="petugas" <?= ($edit && $data['role'] == 'petugas') ? 'selected' : '' ?>>Petugas</option>
            <option value="owner" <?= ($edit && $data['role'] == 'owner') ? 'selected' : '' ?>>Owner</option>
        </select>

        <br><br>

        <?php if ($edit) { ?>
            <button name="update">💾 Simpan Perubahan</button>
        <?php } else { ?>
            <button name="tambah">Simpan</button>
        <?php } ?>

    </form>
    <hr>

<?php } ?>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Username</th>
        <th>Role</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    $q = mysqli_query($koneksi, "SELECT * FROM tb_user");

    while ($d = mysqli_fetch_assoc($q)) {
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $d['nama_lengkap'] ?></td>
            <td><?= $d['username'] ?></td>
            <td><?= $d['role'] ?></td>

            <td>
                <?= $d['status_aktif']
                    ? "<span class='badge-aktif'>Aktif</span>"
                    : "<span class='badge-nonaktif'>Nonaktif</span>" ?>
            </td>

            <td>
                <a href="?edit=<?= $d['id_user'] ?>">Edit</a>

                <?php if ($d['id_user'] != $_SESSION['id_user']) { ?>
                    <?php if ($d['status_aktif']) { ?>
                        | <a href="user_proses.php?toggle=<?= $d['id_user'] ?>&status=0"
                            onclick="return confirm('Nonaktifkan user ini?')">
                            Nonaktifkan
                        </a>
                    <?php } else { ?>
                        | <a href="user_proses.php?toggle=<?= $d['id_user'] ?>&status=1"
                            onclick="return confirm('Aktifkan user ini?')">
                            Aktifkan
                        </a>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<?php include "../template/footer.php"; ?>