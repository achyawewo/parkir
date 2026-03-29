<?php
include "../template/header.php";
?>

<h2>Kendaraan Keluar</h2>

<input type="text" id="search" placeholder="Cari plat nomor...">

<div id="tabelData"></div>

<script>
    function loadData(keyword = "") {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "proses_kendaraan_keluar.php?cari=" + keyword, true);
        xhr.onload = function() {
            document.getElementById("tabelData").innerHTML = this.responseText;
        }
        xhr.send();
    }

    loadData();

    document.getElementById("search").addEventListener("keyup", function() {
        loadData(this.value);
    });
</script>

<?php include "../template/footer.php"; ?>