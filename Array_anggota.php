<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4"><i class="bi bi-people"></i> Array Anggota Perpustakaan</h1>

        <?php
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

        // ========== HITUNG STATISTIK ==========
        $total_anggota   = count($anggota_list);
        $total_aktif     = 0;
        $total_nonaktif  = 0;
        $total_pinjaman  = 0;
        $teraktif        = $anggota_list[0];

        foreach ($anggota_list as $anggota) {
            if ($anggota["status"] == "Aktif") {
                $total_aktif++;
            } else {
                $total_nonaktif++;
            }
            $total_pinjaman += $anggota["total_pinjaman"];
            if ($anggota["total_pinjaman"] > $teraktif["total_pinjaman"]) {
                $teraktif = $anggota;
            }
        }

        $persen_aktif    = ($total_aktif    / $total_anggota) * 100;
        $persen_nonaktif = ($total_nonaktif / $total_anggota) * 100;
        $rata_pinjaman   = $total_pinjaman  / $total_anggota;

        // Filter berdasarkan status
        $anggota_aktif    = [];
        $anggota_nonaktif = [];
        foreach ($anggota_list as $anggota) {
            if ($anggota["status"] == "Aktif") {
                $anggota_aktif[] = $anggota;
            } else {
                $anggota_nonaktif[] = $anggota;
            }
        }
        ?>

        <!-- ===== STATISTIK CARDS ===== -->
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
                        <p class="text-muted mb-0">Non-Aktif</p>
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

        <!-- Anggota Teraktif Banner -->
        <div class="alert alert-success d-flex align-items-center mb-4">
            <i class="bi bi-trophy-fill me-2" style="font-size:1.5rem;"></i>
            <div>
                <strong>Anggota Teraktif:</strong>
                <?php echo $teraktif["nama"]; ?> —
                <span class="badge bg-success"><?php echo $teraktif["total_pinjaman"]; ?> pinjaman</span>
                <small class="text-muted ms-2">(<?php echo $teraktif["id"]; ?> · <?php echo $teraktif["alamat"]; ?>)</small>
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
                                <th>Tgl Daftar</th>
                                <th>Status</th>
                                <th>Total Pinjaman</th>
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
                                <td><?php echo $anggota["tanggal_daftar"]; ?></td>
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
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($anggota_aktif as $anggota): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $anggota["nama"]; ?></strong>
                                    <small class="text-muted d-block"><?php echo $anggota["id"]; ?> · <?php echo $anggota["alamat"]; ?></small>
                                </div>
                                <span class="badge bg-primary"><?php echo $anggota["total_pinjaman"]; ?> pinjaman</span>
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
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($anggota_nonaktif as $anggota): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $anggota["nama"]; ?></strong>
                                    <small class="text-muted d-block"><?php echo $anggota["id"]; ?> · <?php echo $anggota["alamat"]; ?></small>
                                </div>
                                <span class="badge bg-secondary"><?php echo $anggota["total_pinjaman"]; ?> pinjaman</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== STATISTIK ARRAY INFO ===== -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Info Array</h5>
            </div>
            <div class="card-body">
                <ul>
                    <li>Jumlah elemen array: <strong><?php echo count($anggota_list); ?></strong></li>
                    <li>Keys tiap elemen: <code><?php echo implode(", ", array_keys($anggota_list[0])); ?></code></li>
                    <li>Total pinjaman seluruh anggota: <strong><?php echo $total_pinjaman; ?></strong></li>
                </ul>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>