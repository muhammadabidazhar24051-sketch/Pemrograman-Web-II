<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-people-fill"></i> Sistem Anggota Perpustakaan
            </a>
        </div>
    </nav>

    <?php
    // Include functions
    require_once 'functions_anggota.php';

    // ========== DATA ANGGOTA ==========
    $anggota_list = [
        [
            "id"             => "AGT-001",
            "nama"           => "Budiono Siregar",
            "email"          => "budiono@email.com",
            "telepon"        => "081234567890",
            "alamat"         => "Medan",
            "tanggal_daftar" => "2024-01-15",
            "status"         => "Aktif",
            "total_pinjaman" => 5
        ],
        [
            "id"             => "AGT-002",
            "nama"           => "Muhammad Abid Azhar",
            "email"          => "Abid@email.com",
            "telepon"        => "082345678901",
            "alamat"         => "Mbojong",
            "tanggal_daftar" => "2024-02-20",
            "status"         => "Aktif",
            "total_pinjaman" => 12
        ],
        [
            "id"             => "AGT-003",
            "nama"           => "Lingardinho",
            "email"          => "Lingardinho@email.com",
            "telepon"        => "083456789012",
            "alamat"         => "Mbojong pinggir",
            "tanggal_daftar" => "2023-11-05",
            "status"         => "Non-Aktif",
            "total_pinjaman" => 3
        ],
        [
            "id"             => "AGT-004",
            "nama"           => "Anthonydinho",
            "email"          => "Anthonydinho@email.com",
            "telepon"        => "084567890123",
            "alamat"         => "Mbojong Wetan",
            "tanggal_daftar" => "2024-03-10",
            "status"         => "Aktif",
            "total_pinjaman" => 8
        ],
        [
            "id"             => "AGT-005",
            "nama"           => "Braithwaitedinho",
            "email"          => "Braithwaitedinho@email.com",
            "telepon"        => "085678901234",
            "alamat"         => "Rowolaku",
            "tanggal_daftar" => "2023-09-01",
            "status"         => "Non-Aktif",
            "total_pinjaman" => 1
        ]
    ];

    // ========== PANGGIL FUNCTIONS ==========
    $total_anggota   = hitung_total_anggota($anggota_list);
    $total_aktif     = hitung_anggota_aktif($anggota_list);
    $total_nonaktif  = hitung_anggota_nonaktif($anggota_list);
    $persen_aktif    = hitung_persen_aktif($anggota_list);
    $persen_nonaktif = hitung_persen_nonaktif($anggota_list);
    $rata_pinjaman   = hitung_rata_rata_pinjaman($anggota_list);
    $teraktif        = cari_anggota_teraktif($anggota_list);
    $anggota_aktif   = filter_by_status($anggota_list, "Aktif");
    $anggota_nonaktif= filter_by_status($anggota_list, "Non-Aktif");

    // BONUS: Sort & Search
    $anggota_sorted  = sort_by_nama($anggota_list);
    $keyword_search  = "a";  // contoh keyword pencarian
    $hasil_search    = search_by_nama($anggota_list, $keyword_search);
    ?>

    <div class="container mt-4">
        <h2 class="mb-1"><i class="bi bi-speedometer2"></i> Dashboard Anggota</h2>
        <p class="text-muted mb-4">Sistem manajemen anggota perpustakaan menggunakan Array & Function PHP</p>

        <!-- ===== DASHBOARD STATISTIK ===== -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-primary text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-people-fill text-primary" style="font-size:2rem;"></i>
                        <h2 class="mt-2 text-primary"><?php echo $total_anggota; ?></h2>
                        <p class="text-muted mb-0">Total Anggota</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-success text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person-check-fill text-success" style="font-size:2rem;"></i>
                        <h2 class="mt-2 text-success"><?php echo $total_aktif; ?></h2>
                        <p class="text-muted mb-0">Anggota Aktif</p>
                        <span class="badge bg-success"><?php echo number_format($persen_aktif, 1); ?>%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-danger text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-person-x-fill text-danger" style="font-size:2rem;"></i>
                        <h2 class="mt-2 text-danger"><?php echo $total_nonaktif; ?></h2>
                        <p class="text-muted mb-0">Anggota Non-Aktif</p>
                        <span class="badge bg-danger"><?php echo number_format($persen_nonaktif, 1); ?>%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-warning text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-book-fill text-warning" style="font-size:2rem;"></i>
                        <h2 class="mt-2 text-warning"><?php echo number_format($rata_pinjaman, 1); ?></h2>
                        <p class="text-muted mb-0">Rata-rata Pinjaman</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== TABEL SEMUA ANGGOTA ===== -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Semua Anggota</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Total Pinjaman</th>
                                <th>Email Valid?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($anggota_list as $anggota): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><code><?php echo $anggota["id"]; ?></code></td>
                                <td><?php echo $anggota["nama"]; ?></td>
                                <td><?php echo $anggota["email"]; ?></td>
                                <td><?php echo $anggota["telepon"]; ?></td>
                                <td><?php echo $anggota["alamat"]; ?></td>
                                <td><?php echo format_tanggal_indo($anggota["tanggal_daftar"]); ?></td>
                                <td>
                                    <?php if ($anggota["status"] == "Aktif"): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Non-Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary"><?php echo $anggota["total_pinjaman"]; ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if (validasi_email($anggota["email"])): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Valid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="bi bi-x-lg"></i> Tidak Valid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ===== ANGGOTA TERAKTIF ===== -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-trophy-fill"></i> Anggota Teraktif</h5>
            </div>
            <div class="card-body">
                <?php if ($teraktif): ?>
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="bi bi-person-circle text-success" style="font-size:5rem;"></i>
                    </div>
                    <div class="col-md-10">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th width="160">ID</th>
                                <td>: <code><?php echo $teraktif["id"]; ?></code></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>: <strong><?php echo $teraktif["nama"]; ?></strong></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: <?php echo $teraktif["email"]; ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>: <?php echo $teraktif["alamat"]; ?></td>
                            </tr>
                            <tr>
                                <th>Tgl Daftar</th>
                                <td>: <?php echo format_tanggal_indo($teraktif["tanggal_daftar"]); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: <span class="badge bg-success"><?php echo $teraktif["status"]; ?></span></td>
                            </tr>
                            <tr>
                                <th>Total Pinjaman</th>
                                <td>: <span class="badge bg-warning text-dark fs-6"><?php echo $teraktif["total_pinjaman"]; ?> buku</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ===== FILTER: AKTIF vs NON-AKTIF ===== -->
        <div class="row mb-4">
            <!-- Anggota Aktif -->
            <div class="col-md-6 mb-3">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-person-check"></i> Anggota Aktif (<?php echo $total_aktif; ?>)</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($anggota_aktif as $anggota): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $anggota["nama"]; ?></strong>
                                    <small class="text-muted d-block"><?php echo $anggota["id"]; ?> · <?php echo $anggota["alamat"]; ?></small>
                                    <small class="text-muted"><?php echo format_tanggal_indo($anggota["tanggal_daftar"]); ?></small>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?php echo $anggota["total_pinjaman"]; ?> pinjaman</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Anggota Non-Aktif -->
            <div class="col-md-6 mb-3">
                <div class="card border-danger h-100">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-person-x"></i> Anggota Non-Aktif (<?php echo $total_nonaktif; ?>)</h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($anggota_nonaktif) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($anggota_nonaktif as $anggota): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $anggota["nama"]; ?></strong>
                                    <small class="text-muted d-block"><?php echo $anggota["id"]; ?> · <?php echo $anggota["alamat"]; ?></small>
                                    <small class="text-muted"><?php echo format_tanggal_indo($anggota["tanggal_daftar"]); ?></small>
                                </div>
                                <span class="badge bg-secondary rounded-pill"><?php echo $anggota["total_pinjaman"]; ?> pinjaman</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <div class="p-3">
                            <div class="alert alert-success mb-0"><i class="bi bi-check-circle"></i> Semua anggota aktif!</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== BONUS: SORT A-Z ===== -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-sort-alpha-down"></i> BONUS: Anggota Diurutkan A-Z (sort_by_nama)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>ID</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Total Pinjaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($anggota_sorted as $anggota): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo $anggota["nama"]; ?></strong></td>
                                <td><code><?php echo $anggota["id"]; ?></code></td>
                                <td><?php echo $anggota["alamat"]; ?></td>
                                <td>
                                    <?php if ($anggota["status"] == "Aktif"): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Non-Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-primary"><?php echo $anggota["total_pinjaman"]; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ===== BONUS: SEARCH BY NAMA ===== -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-search"></i> BONUS: Hasil Pencarian Nama (search_by_nama)</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    Mencari anggota dengan keyword: <strong>"<?php echo $keyword_search; ?>"</strong>
                    — Ditemukan <span class="badge bg-primary"><?php echo count($hasil_search); ?></span> anggota
                </div>
                <?php if (count($hasil_search) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($hasil_search as $anggota): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo $anggota["nama"]; ?></strong>
                            <small class="text-muted ms-2"><?php echo $anggota["id"]; ?></small>
                        </div>
                        <span class="badge bg-<?php echo $anggota["status"] == "Aktif" ? "success" : "danger"; ?>">
                            <?php echo $anggota["status"]; ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <div class="alert alert-warning mb-0">Tidak ada anggota ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ===== CEK FUNGSI cari_anggota_by_id ===== -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Contoh: cari_anggota_by_id("AGT-003")</h5>
            </div>
            <div class="card-body">
                <?php
                $cari = cari_anggota_by_id($anggota_list, "AGT-003");
                if ($cari): ?>
                <div class="alert alert-success">
                    Anggota ditemukan: <strong><?php echo $cari["nama"]; ?></strong>
                    — <?php echo $cari["alamat"]; ?>
                    — <span class="badge bg-<?php echo $cari["status"] == "Aktif" ? "success" : "danger"; ?>">
                        <?php echo $cari["status"]; ?>
                      </span>
                </div>
                <?php else: ?>
                <div class="alert alert-warning">Anggota tidak ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p class="mb-0">&copy; 2024 Sistem Anggota Perpustakaan — PHP Array & Functions</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>