<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Informasi Buku</h1>
        
        <?php
        // Data buku

        // BUKU 1
        $judul1 = "Pemrograman PHP Modern";
        $kategori1 = "Programming";
        $bahasa1 = "Indonesia";
        $halaman1 = 450;
        $pengarang1 = "Muhammad Abid Azhar";
        $penerbit1 = "Informatika";
        $tahun_terbit1 = 2023;
        $harga1 = 125000;
        $stok1 = 8;
        $isbn1 = "978-602-1234-56-7";
        $berat1 = 450;
        $badge1 = "success";

        // BUKU 2
        $judul2 = "MySQL Database Administration";
        $kategori2 = "Database";
        $bahasa2 = "Indonesia";
        $halaman2 = 280;
        $pengarang2 = "Steven D. Kurniawan";
        $penerbit2 = "Svn Media";
        $tahun_terbit2 = 2022;
        $harga2 = 98000;
        $stok2 = 5;
        $isbn2 = "978-602-9876-54-3";
        $berat2 = 380;
        $badge2 = "primary";

        // BUKU 3
        $judul3 = "HTML & CSS Design";
        $kategori3 = "Web Design";
        $bahasa3 = "Inggris";
        $halaman3 = 490;
        $pengarang3 = "Jon Duckett";
        $penerbit3 = "Wiley";
        $tahun_terbit3 = 2021;
        $harga3 = 175000;
        $stok3 = 2;
        $isbn3 = "978-1-118-00818-8";
        $berat3 = 620;
        $badge3 = "warning";

        // BUKU 4
        $judul4 = "Learning Python";
        $kategori4 = "Database";
        $bahasa4 = "Inggris";
        $halaman4 = 320;
        $pengarang4 = "Abraham";
        $penerbit4 = "Abraham Corp";
        $tahun_terbit4 = 2022;
        $harga4 = 210000;
        $stok4 = 6;
        $isbn4 = "978-1-492-08032-0";
        $berat4 = 410;
        $badge4 = "danger";
        ?>
        
        <!-- CARD BUKU 1 -->
        <div class="card mb-4">
            <div class="card-header bg-<?php echo $badge1; ?> text-white">
                <h5 class="mb-0"><?php echo $judul1; ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Pengarang</th>
                        <td>: <?php echo $pengarang1; ?></td>
                    </tr>
                    <tr>
                        <th>Penerbit</th>
                        <td>: <?php echo $penerbit1; ?></td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>: <?php echo $tahun_terbit1; ?></td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>: <?php echo $isbn1; ?></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>: Rp <?php echo number_format($harga1, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>: <?php echo $stok1; ?> buku</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: <span class="badge bg-<?php echo $badge1; ?>"><?php echo $kategori1; ?></span></td>
                    </tr>
                    <tr>
                        <th>Bahasa</th>
                        <td>: <?php echo $bahasa1; ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Halaman</th>
                        <td>: <?php echo $halaman1; ?> halaman</td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td>: <?php echo $berat1; ?> gram</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- CARD BUKU 2 -->
        <div class="card mb-4">
            <div class="card-header bg-<?php echo $badge2; ?> text-white">
                <h5 class="mb-0"><?php echo $judul2; ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Pengarang</th>
                        <td>: <?php echo $pengarang2; ?></td>
                    </tr>
                    <tr>
                        <th>Penerbit</th>
                        <td>: <?php echo $penerbit2; ?></td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>: <?php echo $tahun_terbit2; ?></td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>: <?php echo $isbn2; ?></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>: Rp <?php echo number_format($harga2, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>: <?php echo $stok2; ?> buku</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: <span class="badge bg-<?php echo $badge2; ?>"><?php echo $kategori2; ?></span></td>
                    </tr>
                    <tr>
                        <th>Bahasa</th>
                        <td>: <?php echo $bahasa2; ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Halaman</th>
                        <td>: <?php echo $halaman2; ?> halaman</td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td>: <?php echo $berat2; ?> gram</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- CARD BUKU 3 -->
        <div class="card mb-4">
            <div class="card-header bg-<?php echo $badge3; ?> text-white">
                <h5 class="mb-0"><?php echo $judul3; ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Pengarang</th>
                        <td>: <?php echo $pengarang3; ?></td>
                    </tr>
                    <tr>
                        <th>Penerbit</th>
                        <td>: <?php echo $penerbit3; ?></td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>: <?php echo $tahun_terbit3; ?></td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>: <?php echo $isbn3; ?></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>: Rp <?php echo number_format($harga3, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>: <?php echo $stok3; ?> buku</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: <span class="badge bg-<?php echo $badge3; ?>"><?php echo $kategori3; ?></span></td>
                    </tr>
                    <tr>
                        <th>Bahasa</th>
                        <td>: <?php echo $bahasa3; ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Halaman</th>
                        <td>: <?php echo $halaman3; ?> halaman</td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td>: <?php echo $berat3; ?> gram</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- CARD BUKU 4 -->
        <div class="card mb-4">
            <div class="card-header bg-<?php echo $badge4; ?> text-white">
                <h5 class="mb-0"><?php echo $judul4; ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Pengarang</th>
                        <td>: <?php echo $pengarang4; ?></td>
                    </tr>
                    <tr>
                        <th>Penerbit</th>
                        <td>: <?php echo $penerbit4; ?></td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>: <?php echo $tahun_terbit4; ?></td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>: <?php echo $isbn4; ?></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>: Rp <?php echo number_format($harga4, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>: <?php echo $stok4; ?> buku</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: <span class="badge bg-<?php echo $badge4; ?>"><?php echo $kategori4; ?></span></td>
                    </tr>
                    <tr>
                        <th>Bahasa</th>
                        <td>: <?php echo $bahasa4; ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Halaman</th>
                        <td>: <?php echo $halaman4; ?> halaman</td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td>: <?php echo $berat4; ?> gram</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>