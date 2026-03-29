<?php
include "../config/koneksi.php";

$id = $_GET['id'];

$data = mysqli_query($koneksi, "
    SELECT 
        t.*,
        k.plat_nomor,
        k.jenis_kendaraan,
        k.warna,
        k.pemilik,
        s.kode_slot,
        u.nama_lengkap AS petugas
    FROM tb_transaksi t
    JOIN tb_kendaraan k ON t.id_kendaraan = k.id_kendaraan
    JOIN tb_slot_parkir s ON t.id_slot = s.id_slot
    JOIN tb_user u ON t.id_user = u.id_user
    WHERE t.id_parkir='$id'
");

$d = mysqli_fetch_assoc($data);

$qr_text = "Parkir ID: " . $id .
    "\nPlat: " . $d['plat_nomor'] .
    "\nMasuk: " . $d['waktu_masuk'] .
    "\nKeluar: " . $d['waktu_keluar'] .
    "\nTotal: Rp " . number_format($d['biaya_total']);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Struk Parkir</title>

    <style>
        body {
            font-family: monospace;
            background: white;
        }

        .struk {
            width: 250px;
            margin: auto;
        }

        .center {
            text-align: center;
        }

        hr {
            border: none;
            border-top: 1px dashed black;
            margin: 6px 0;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="playAndPrint()">

    <div class="struk">

        <div class="center">
            <b>STRUK PARKIR</b><br>
            ACHYAWEWO PARKING SYSTEM
        </div>

        <hr>

        Plat : <?= $d['plat_nomor'] ?><br>
        Jenis : <?= $d['jenis_kendaraan'] ?><br>
        Warna : <?= $d['warna'] ?><br>
        Pemilik: <?= $d['pemilik'] ?><br>
        Slot : <?= $d['kode_slot'] ?><br>

        <hr>

        Masuk : <?= $d['waktu_masuk'] ?><br>
        Keluar : <?= $d['waktu_keluar'] ?><br>
        Durasi : <?= $d['durasi_jam'] ?> jam<br>

        <hr>

        <b>Total Bayar</b>
        <div class="center">
            <b>Rp <?= number_format($d['biaya_total']) ?></b>
        </div>

        <hr>

        <div class="center">
            Petugas: <?= $d['petugas'] ?>
        </div>

        <hr>

        <div class="center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($qr_text) ?>" alt="QR Code">
        </div>

        <div class="center">
            TERIMA KASIH<br>
            <i>HATI-HATI DI JALAN - TULUS</i>
        </div>

    </div>

</body>

</html>

<!-- SOUND -->
<audio id="strukSound">
    <source src="../assets/sound/struk.mp3" type="audio/mpeg">
</audio>

<script>
    function playAndPrint() {

        var sound = document.getElementById("strukSound");

        sound.play().then(() => {
            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    window.location = 'kendaraan_keluar.php';
                }, 1000);
            }, 500);
        }).catch(() => {
            // jika autoplay diblok, tetap print
            window.print();
            setTimeout(() => {
                window.location = 'kendaraan_keluar.php';
            }, 1000);
        });

    }
</script>