<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perhitungan Diskon - Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sistem Perhitungan Diskon Bertingkat</h1>

        <?php
        // Data pembeli dan buku
        $nama_pembeli = "Budi Santoso";
        $judul_buku   = "Laravel Advanced";
        $harga_satuan = 50000;
        $jumlah_beli  = 1;
        $is_member    = false; // true atau false

        // Hitung subtotal
        $subtotal = $harga_satuan * $jumlah_beli;

        // persentase diskon berdasarkan jumlah
        if ($jumlah_beli >= 1 && $jumlah_beli <= 2) {
            $persentase_diskon = 0;
        } elseif ($jumlah_beli >= 3 && $jumlah_beli <= 5) {
            $persentase_diskon = 10;
        } elseif ($jumlah_beli >= 6 && $jumlah_beli <= 10) {
            $persentase_diskon = 15;
        } else {
            $persentase_diskon = 20;
        }

        // Hitung diskon
        $diskon = $subtotal * ($persentase_diskon / 100);

        // Total setelah diskon pertama
        $total_setelah_diskon1 = $subtotal - $diskon;

        // Hitung diskon member jika member
        $persentase_diskon_member = 0;
        $diskon_member = 0;
        if ($is_member) {
            $persentase_diskon_member = 5;
            $diskon_member = $total_setelah_diskon1 * ($persentase_diskon_member / 100);
        }

        // Total setelah semua diskon
        $total_setelah_diskon = $total_setelah_diskon1 - $diskon_member;

        // Hitung PPN 11%
        $ppn = $total_setelah_diskon * 0.11;

        // Total akhir
        $total_akhir = $total_setelah_diskon + $ppn;

        // Total penghematan
        $total_hemat = $diskon + $diskon_member;
        ?>

        <div class="row">
            <div class="col-md-8">

                <!-- Card Detail Pembelian -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Detail Pembelian</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="250">Nama Pembeli</th>
                                <td>: <?php echo $nama_pembeli; ?>
                                    <?php if ($is_member): ?>
                                        <span class="badge bg-warning text-dark ms-2">Member</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary ms-2">Non-Member</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Judul Buku</th>
                                <td>: <?php echo $judul_buku; ?></td>
                            </tr>
                            <tr>
                                <th>Harga Satuan</th>
                                <td>: Rp <?php echo number_format($harga_satuan, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th>Jumlah Beli</th>
                                <td>: <?php echo $jumlah_beli; ?> buku</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Card Rincian Perhitungan -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Rincian Perhitungan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr class="table-secondary">
                                <th width="250">Subtotal</th>
                                <td>: Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="text-success">
                                <th>
                                    Diskon
                                    <?php if ($persentase_diskon > 0): ?>
                                        <span class="badge bg-success"><?php echo $persentase_diskon; ?>%</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">0%</span>
                                    <?php endif; ?>
                                </th>
                                <td>: - Rp <?php echo number_format($diskon, 0, ',', '.'); ?></td>
                            </tr>
                            <?php if ($is_member): ?>
                            <tr class="text-warning">
                                <th>
                                    Diskon Member
                                    <span class="badge bg-warning text-dark">5%</span>
                                </th>
                                <td>: - Rp <?php echo number_format($diskon_member, 0, ',', '.'); ?>
                                    <small class="text-muted">(dari Rp <?php echo number_format($total_setelah_diskon1, 0, ',', '.'); ?>)</small>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-secondary">
                                <th>Total Setelah Diskon</th>
                                <td>: Rp <?php echo number_format($total_setelah_diskon, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th>PPN <span class="badge bg-secondary">11%</span></th>
                                <td>: + Rp <?php echo number_format($ppn, 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="table-primary fw-bold">
                                <th>TOTAL AKHIR</th>
                                <td>: Rp <?php echo number_format($total_akhir, 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <!-- Card Informasi Diskon -->
                <div class="card border-info mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">Informasi Diskon</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">Beli 1-2 buku: <span class="badge bg-secondary">Tidak ada diskon</span></li>
                            <li class="mb-2">Beli 3-5 buku: <span class="badge bg-success">Diskon 10%</span></li>
                            <li class="mb-2">Beli 6-10 buku: <span class="badge bg-primary">Diskon 15%</span></li>
                            <li class="mb-2">Beli >10 buku: <span class="badge bg-danger">Diskon 20%</span></li>
                            <li class="mt-3">Bonus Member: <span class="badge bg-warning text-dark">+5%</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Card Total Hemat -->
                <div class="card border-warning">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0">Total Hemat Anda</h6>
                    </div>
                    <div class="card-body">
                        <h4 class="text-success">Rp <?php echo number_format($total_hemat, 0, ',', '.'); ?></h4>
                        <small class="text-muted">dari harga normal Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></small>

                        <?php if ($persentase_diskon > 0): ?>
                        <hr>
                        <small>
                            Diskon <?php echo $persentase_diskon; ?>%:
                            <strong>Rp <?php echo number_format($diskon, 0, ',', '.'); ?></strong>
                        </small><br>
                        <?php endif; ?>

                        <?php if ($is_member): ?>
                        <small>
                            Diskon Member 5%:
                            <strong>Rp <?php echo number_format($diskon_member, 0, ',', '.'); ?></strong>
                        </small>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- Alert selamat -->
        <?php if ($total_hemat > 0): ?>
        <div class="alert alert-success">
            <strong>Selamat, <?php echo $nama_pembeli; ?>!</strong>
            Anda menghemat <strong>Rp <?php echo number_format($total_hemat, 0, ',', '.'); ?></strong>
            <?php if ($is_member): ?>
                berkat diskon <?php echo $persentase_diskon; ?>% pembelian + diskon member 5%.
            <?php else: ?>
                berkat diskon <?php echo $persentase_diskon; ?>% pembelian.
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            Beli 3 buku atau lebih untuk mendapatkan diskon!
        </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>