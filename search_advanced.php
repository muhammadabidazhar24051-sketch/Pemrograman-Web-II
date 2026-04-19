<?php
// ============================================
// TUGAS 2: Sistem Pencarian Buku Lanjutan
// File: search_advanced.php
// Fitur: Filter, Sorting, Pagination, Highlight,
//        Export CSV (BONUS), Recent Searches (BONUS)
// ============================================

session_start();

// ── DATA BUKU (15 buku) ────────────────────────────────────────────────────
$buku_list = [
    ['kode'=>'BK-001','judul'=>'Pemrograman PHP untuk Pemula','kategori'=>'Programming',
     'pengarang'=>'Budi Raharjo','penerbit'=>'Informatika','tahun'=>2020,'harga'=>75000,'stok'=>10],
    ['kode'=>'BK-002','judul'=>'Mastering MySQL Database','kategori'=>'Database',
     'pengarang'=>'Andi Nugroho','penerbit'=>'Elex Media','tahun'=>2019,'harga'=>95000,'stok'=>5],
    ['kode'=>'BK-003','judul'=>'Desain Web Modern dengan CSS3','kategori'=>'Web Design',
     'pengarang'=>'Siti Rahayu','penerbit'=>'Gramedia','tahun'=>2021,'harga'=>88000,'stok'=>0],
    ['kode'=>'BK-004','judul'=>'JavaScript: The Complete Guide','kategori'=>'Programming',
     'pengarang'=>'David Flanagan','penerbit'=>"O'Reilly",'tahun'=>2020,'harga'=>195000,'stok'=>3],
    ['kode'=>'BK-005','judul'=>'Cisco Networking Essentials','kategori'=>'Networking',
     'pengarang'=>'Troy McMillan','penerbit'=>'Sybex','tahun'=>2018,'harga'=>145000,'stok'=>7],
    ['kode'=>'BK-006','judul'=>'Laravel: Framework PHP Terkini','kategori'=>'Programming',
     'pengarang'=>'Wawan Setiawan','penerbit'=>'Informatika','tahun'=>2022,'harga'=>115000,'stok'=>12],
    ['kode'=>'BK-007','judul'=>'PostgreSQL Administration Cookbook','kategori'=>'Database',
     'pengarang'=>'Simon Riggs','penerbit'=>'Packt','tahun'=>2021,'harga'=>165000,'stok'=>0],
    ['kode'=>'BK-008','judul'=>'Responsive Web Design dengan Bootstrap 5','kategori'=>'Web Design',
     'pengarang'=>'Agus Kurniawan','penerbit'=>'Elex Media','tahun'=>2022,'harga'=>79000,'stok'=>15],
    ['kode'=>'BK-009','judul'=>'Python untuk Data Science','kategori'=>'Programming',
     'pengarang'=>'Hadley Wickham','penerbit'=>"O'Reilly",'tahun'=>2023,'harga'=>210000,'stok'=>6],
    ['kode'=>'BK-010','judul'=>'Network Security Fundamentals','kategori'=>'Networking',
     'pengarang'=>'William Stallings','penerbit'=>'Pearson','tahun'=>2020,'harga'=>175000,'stok'=>4],
    ['kode'=>'BK-011','judul'=>'Vue.js 3: Building Modern UI','kategori'=>'Web Design',
     'pengarang'=>'Evan You','penerbit'=>'Packt','tahun'=>2023,'harga'=>135000,'stok'=>8],
    ['kode'=>'BK-012','judul'=>'NoSQL Database dengan MongoDB','kategori'=>'Database',
     'pengarang'=>'Kristina Chodorow','penerbit'=>"O'Reilly",'tahun'=>2019,'harga'=>125000,'stok'=>2],
    ['kode'=>'BK-013','judul'=>'Algoritma dan Struktur Data','kategori'=>'Programming',
     'pengarang'=>'Thomas Cormen','penerbit'=>'MIT Press','tahun'=>2017,'harga'=>250000,'stok'=>9],
    ['kode'=>'BK-014','judul'=>'Keamanan Jaringan Komputer','kategori'=>'Networking',
     'pengarang'=>'Rahmat Rafiudin','penerbit'=>'Andi Publisher','tahun'=>2018,'harga'=>98000,'stok'=>0],
    ['kode'=>'BK-015','judul'=>'React.js: Membangun Aplikasi Modern','kategori'=>'Web Design',
     'pengarang'=>'Robin Wieruch','penerbit'=>'Leanpub','tahun'=>2022,'harga'=>155000,'stok'=>11],
];

// ── PARAMETER GET ──────────────────────────────────────────────────────────
$keyword   = trim($_GET['keyword']   ?? '');
$kategori  = trim($_GET['kategori']  ?? '');
$min_harga = trim($_GET['min_harga'] ?? '');
$max_harga = trim($_GET['max_harga'] ?? '');
$tahun     = trim($_GET['tahun']     ?? '');
$status    = $_GET['status']  ?? 'semua';
$sort      = $_GET['sort']    ?? 'judul';
$page      = max(1, (int)($_GET['page'] ?? 1));
$per_page  = 10;

// ── EXPORT CSV (BONUS) ─────────────────────────────────────────────────────
$is_export = isset($_GET['export']) && $_GET['export'] === 'csv';

// ── VALIDASI ───────────────────────────────────────────────────────────────
$errors = [];
if ($min_harga !== '' && $max_harga !== '') {
    if ((float)$min_harga > (float)$max_harga) {
        $errors[] = 'Harga minimum tidak boleh lebih besar dari harga maksimum.';
    }
}
if ($tahun !== '') {
    $tahun_int = (int)$tahun;
    if ($tahun_int < 1900 || $tahun_int > (int)date('Y')) {
        $errors[] = 'Tahun tidak valid (1900 – ' . date('Y') . ').';
    }
}

// ── RECENT SEARCHES (BONUS) ────────────────────────────────────────────────
if (isset($_GET['search_submit']) && empty($errors) && $keyword !== '') {
    if (!isset($_SESSION['recent_searches'])) {
        $_SESSION['recent_searches'] = [];
    }
    // Tambah & jaga maks 5 unik
    $_SESSION['recent_searches'] = array_values(
        array_unique(array_merge([$keyword], $_SESSION['recent_searches']))
    );
    if (count($_SESSION['recent_searches']) > 5) {
        $_SESSION['recent_searches'] = array_slice($_SESSION['recent_searches'], 0, 5);
    }
}
// Hapus recent search tertentu
if (isset($_GET['clear_recent'])) {
    $idx = (int)$_GET['clear_recent'];
    if (isset($_SESSION['recent_searches'][$idx])) {
        array_splice($_SESSION['recent_searches'], $idx, 1);
    }
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// ── FILTER ────────────────────────────────────────────────────────────────
$hasil = [];
if (empty($errors)) {
    foreach ($buku_list as $buku) {
        // Keyword (judul atau pengarang)
        if ($keyword !== '') {
            $kw = strtolower($keyword);
            if (strpos(strtolower($buku['judul']), $kw) === false &&
                strpos(strtolower($buku['pengarang']), $kw) === false) {
                continue;
            }
        }
        // Kategori
        if ($kategori !== '' && $buku['kategori'] !== $kategori) continue;
        // Min harga
        if ($min_harga !== '' && $buku['harga'] < (int)$min_harga) continue;
        // Max harga
        if ($max_harga !== '' && $buku['harga'] > (int)$max_harga) continue;
        // Tahun
        if ($tahun !== '' && $buku['tahun'] != (int)$tahun) continue;
        // Status
        if ($status === 'tersedia' && $buku['stok'] <= 0) continue;
        if ($status === 'habis'    && $buku['stok'] >  0) continue;

        $hasil[] = $buku;
    }

    // ── SORTING ─────────────────────────────────────────────────────────
    usort($hasil, function($a, $b) use ($sort) {
        switch ($sort) {
            case 'harga_asc':  return $a['harga']  - $b['harga'];
            case 'harga_desc': return $b['harga']  - $a['harga'];
            case 'tahun_desc': return $b['tahun']  - $a['tahun'];
            case 'tahun_asc':  return $a['tahun']  - $b['tahun'];
            default:           return strcmp($a['judul'], $b['judul']); // judul A-Z
        }
    });
}

$total        = count($hasil);
$total_pages  = $total > 0 ? (int)ceil($total / $per_page) : 1;
$page         = min($page, $total_pages);
$offset       = ($page - 1) * $per_page;
$hasil_page   = array_slice($hasil, $offset, $per_page);

// ── EXPORT CSV ────────────────────────────────────────────────────────────
if ($is_export && $total > 0) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="hasil_pencarian_buku.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Kode','Judul','Kategori','Pengarang','Penerbit','Tahun','Harga','Stok']);
    foreach ($hasil as $row) {
        fputcsv($out, [
            $row['kode'], $row['judul'], $row['kategori'], $row['pengarang'],
            $row['penerbit'], $row['tahun'], $row['harga'], $row['stok']
        ]);
    }
    fclose($out);
    exit;
}

// ── HELPER: Highlight keyword ─────────────────────────────────────────────
function highlight($text, $keyword) {
    if (empty($keyword)) return htmlspecialchars($text);
    $safe_kw = preg_quote(htmlspecialchars($keyword), '/');
    $safe_text = htmlspecialchars($text);
    return preg_replace(
        '/(' . $safe_kw . ')/i',
        '<mark class="hl">$1</mark>',
        $safe_text
    );
}

// ── HELPER: Build URL dengan parameter (ganti satu param) ─────────────────
function build_url($override = []) {
    $params = [
        'keyword'   => $_GET['keyword']   ?? '',
        'kategori'  => $_GET['kategori']  ?? '',
        'min_harga' => $_GET['min_harga'] ?? '',
        'max_harga' => $_GET['max_harga'] ?? '',
        'tahun'     => $_GET['tahun']     ?? '',
        'status'    => $_GET['status']    ?? 'semua',
        'sort'      => $_GET['sort']      ?? 'judul',
        'page'      => $_GET['page']      ?? 1,
        'search_submit' => '1',
    ];
    $params = array_merge($params, $override);
    return '?' . http_build_query(array_filter($params, fn($v) => $v !== ''));
}

$kategori_list = ['Programming', 'Database', 'Web Design', 'Networking'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Buku Lanjutan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1a3c6e;
            --accent:  #e8a020;
            --light-bg: #f0f4f8;
        }
        body { background: var(--light-bg); }
        .navbar { background: var(--primary) !important; }
        .sidebar-card .card-header { background: var(--primary); color: #fff; }
        mark.hl { background: #fff176; padding: 0 2px; border-radius: 3px; font-weight: 600; }
        .sort-label { font-size: .8rem; color: #6c757d; }
        .badge-cat { font-size: .72rem; }
        .stok-badge-ok   { background: #d1fae5; color: #065f46; }
        .stok-badge-habis{ background: #fee2e2; color: #991b1b; }
        .table thead th  { background: var(--primary); color: #fff; border: none; font-size: .85rem; }
        .table tbody tr:hover { background: #e8f0fe; }
        .result-info { font-size: .88rem; }
        .page-link { color: var(--primary); }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
        .recent-chip {
            display: inline-block; padding: 3px 10px;
            background: #e8f0fe; border-radius: 20px; font-size: .8rem;
            color: var(--primary); cursor: pointer; margin: 2px;
        }
        .recent-chip:hover { background: #c7d7fa; }
        .section-title {
            font-size: .75rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #6c757d;
            border-bottom: 2px solid #e9ecef; padding-bottom: 6px;
        }
        .filter-active { border-left: 4px solid var(--accent); }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="search_advanced.php">
            <i class="bi bi-search me-2"></i>Pencarian Buku Perpustakaan
        </a>
        <span class="text-white-50 small d-none d-md-block">
            Total koleksi: <?= count($buku_list) ?> buku
        </span>
    </div>
</nav>

<div class="container-fluid py-4">
<div class="row g-3">

    <!-- ══════════ SIDEBAR FILTER ══════════════════════════════════════════ -->
    <div class="col-lg-3 col-md-4">
        <div class="card sidebar-card shadow-sm">
            <div class="card-header py-2">
                <h6 class="mb-0"><i class="bi bi-funnel-fill me-2"></i>Filter Pencarian</h6>
            </div>
            <div class="card-body p-3">

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 mb-3 small">
                    <?php foreach ($errors as $e): ?>
                        <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form method="GET" action="">
                    <input type="hidden" name="search_submit" value="1">

                    <!-- Keyword -->
                    <div class="mb-3">
                        <label class="form-label section-title d-block mb-2">
                            <i class="bi bi-type me-1"></i>Kata Kunci
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="keyword"
                                   value="<?= htmlspecialchars($keyword) ?>"
                                   placeholder="Judul / pengarang...">
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label section-title d-block mb-2">
                            <i class="bi bi-tag me-1"></i>Kategori
                        </label>
                        <select class="form-select form-select-sm" name="kategori">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($kategori_list as $kat): ?>
                            <option value="<?= $kat ?>" <?= ($kategori === $kat) ? 'selected' : '' ?>>
                                <?= $kat ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Range Harga -->
                    <div class="mb-3">
                        <label class="form-label section-title d-block mb-2">
                            <i class="bi bi-cash me-1"></i>Range Harga (Rp)
                        </label>
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm"
                                       name="min_harga" placeholder="Min"
                                       min="0" step="1000"
                                       value="<?= htmlspecialchars($min_harga) ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm"
                                       name="max_harga" placeholder="Max"
                                       min="0" step="1000"
                                       value="<?= htmlspecialchars($max_harga) ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Tahun -->
                    <div class="mb-3">
                        <label class="form-label section-title d-block mb-2">
                            <i class="bi bi-calendar me-1"></i>Tahun Terbit
                        </label>
                        <input type="number" class="form-control form-control-sm"
                               name="tahun" placeholder="cth: 2021"
                               min="1900" max="<?= date('Y') ?>"
                               value="<?= htmlspecialchars($tahun) ?>">
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label section-title d-block mb-2">
                            <i class="bi bi-archive me-1"></i>Ketersediaan
                        </label>
                        <?php foreach (['semua'=>'Semua', 'tersedia'=>'Tersedia', 'habis'=>'Stok Habis'] as $val => $lbl): ?>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="radio" name="status"
                                   id="status_<?= $val ?>" value="<?= $val ?>"
                                   <?= ($status === $val) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="status_<?= $val ?>">
                                <?= $lbl ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Sort -->
                    <div class="mb-3">
                        <label class="form-label section-title d-block mb-2">
                            <i class="bi bi-sort-alpha-down me-1"></i>Urutkan
                        </label>
                        <select class="form-select form-select-sm" name="sort">
                            <option value="judul"      <?= ($sort==='judul')      ?'selected':'' ?>>Judul A–Z</option>
                            <option value="harga_asc"  <?= ($sort==='harga_asc')  ?'selected':'' ?>>Harga Termurah</option>
                            <option value="harga_desc" <?= ($sort==='harga_desc') ?'selected':'' ?>>Harga Termahal</option>
                            <option value="tahun_desc" <?= ($sort==='tahun_desc') ?'selected':'' ?>>Terbaru</option>
                            <option value="tahun_asc"  <?= ($sort==='tahun_asc')  ?'selected':'' ?>>Terlama</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-sm"
                                style="background:var(--primary);border-color:var(--primary)">
                            <i class="bi bi-search me-1"></i>Cari Buku
                        </button>
                        <a href="search_advanced.php" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Reset Filter
                        </a>
                    </div>
                </form>

                <!-- Recent Searches (BONUS) -->
                <?php if (!empty($_SESSION['recent_searches'])): ?>
                <hr class="my-3">
                <div>
                    <p class="section-title mb-2"><i class="bi bi-clock-history me-1"></i>Pencarian Terakhir</p>
                    <?php foreach ($_SESSION['recent_searches'] as $i => $rs): ?>
                    <a href="?keyword=<?= urlencode($rs) ?>&search_submit=1" class="recent-chip text-decoration-none">
                        <i class="bi bi-search me-1" style="font-size:.7rem"></i><?= htmlspecialchars($rs) ?>
                    </a>
                    <a href="?clear_recent=<?= $i ?>" class="text-muted" title="Hapus">
                        <i class="bi bi-x" style="font-size:.75rem"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div><!-- /card-body -->
        </div><!-- /card -->
    </div><!-- /col sidebar -->

    <!-- ══════════ HASIL PENCARIAN ═════════════════════════════════════════ -->
    <div class="col-lg-9 col-md-8">

        <!-- Info bar -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="result-info">
                <?php if (isset($_GET['search_submit'])): ?>
                    <?php if ($total > 0): ?>
                    <span class="fw-semibold text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Ditemukan <strong><?= $total ?></strong> buku
                    </span>
                    <span class="text-muted ms-2">
                        · Menampilkan <?= $offset+1 ?>–<?= min($offset+$per_page, $total) ?>
                        (Halaman <?= $page ?>/<?= $total_pages ?>)
                    </span>
                    <?php else: ?>
                    <span class="fw-semibold text-danger">
                        <i class="bi bi-x-circle me-1"></i>Tidak ada buku yang sesuai kriteria.
                    </span>
                    <?php endif; ?>
                <?php else: ?>
                <span class="text-muted"><i class="bi bi-info-circle me-1"></i>Gunakan filter di kiri untuk mencari buku.</span>
                <?php endif; ?>
            </div>

            <!-- Export CSV (BONUS) -->
            <?php if ($total > 0): ?>
            <a href="<?= build_url(['export'=>'csv','page'=>'']) ?>"
               class="btn btn-sm btn-outline-success">
                <i class="bi bi-download me-1"></i>Export CSV
            </a>
            <?php endif; ?>
        </div>

        <!-- Active filters chips -->
        <?php
        $active_filters = [];
        if ($keyword)   $active_filters[] = "Kata Kunci: \"$keyword\"";
        if ($kategori)  $active_filters[] = "Kategori: $kategori";
        if ($min_harga) $active_filters[] = "Harga ≥ Rp " . number_format($min_harga,0,',','.');
        if ($max_harga) $active_filters[] = "Harga ≤ Rp " . number_format($max_harga,0,',','.');
        if ($tahun)     $active_filters[] = "Tahun: $tahun";
        if ($status !== 'semua') $active_filters[] = "Status: " . ucfirst($status);
        ?>
        <?php if (!empty($active_filters)): ?>
        <div class="mb-3">
            <?php foreach ($active_filters as $af): ?>
            <span class="badge bg-primary bg-opacity-10 text-primary me-1 mb-1 py-1 px-2">
                <i class="bi bi-funnel-fill me-1" style="font-size:.7rem"></i><?= htmlspecialchars($af) ?>
            </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Tabel Hasil -->
        <?php if ($total > 0 && empty($errors)): ?>
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Judul / Pengarang</th>
                            <th>Kategori</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Harga</th>
                            <th class="text-center">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($hasil_page as $i => $buku): ?>
                    <tr>
                        <td class="text-muted small"><?= $offset + $i + 1 ?></td>
                        <td><code class="small"><?= htmlspecialchars($buku['kode']) ?></code></td>
                        <td>
                            <div class="fw-semibold"><?= highlight($buku['judul'], $keyword) ?></div>
                            <small class="text-muted"><?= highlight($buku['pengarang'], $keyword) ?></small>
                        </td>
                        <td>
                            <?php
                            $cat_colors = [
                                'Programming'=>'primary','Database'=>'success',
                                'Web Design'=>'info','Networking'=>'warning'
                            ];
                            $cc = $cat_colors[$buku['kategori']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $cc ?> badge-cat"><?= $buku['kategori'] ?></span>
                        </td>
                        <td class="small"><?= htmlspecialchars($buku['penerbit']) ?></td>
                        <td class="small"><?= $buku['tahun'] ?></td>
                        <td class="fw-semibold small">Rp <?= number_format($buku['harga'],0,',','.') ?></td>
                        <td class="text-center">
                            <?php if ($buku['stok'] > 0): ?>
                            <span class="badge stok-badge-ok"><?= $buku['stok'] ?></span>
                            <?php else: ?>
                            <span class="badge stok-badge-habis">Habis</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                <small class="text-muted">Halaman <?= $page ?> dari <?= $total_pages ?></small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Prev -->
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= build_url(['page'=> $page-1]) ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <!-- Pages -->
                        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                            <?php if ($p == 1 || $p == $total_pages || abs($p - $page) <= 2): ?>
                            <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= build_url(['page'=> $p]) ?>"><?= $p ?></a>
                            </li>
                            <?php elseif (abs($p - $page) == 3): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <!-- Next -->
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= build_url(['page'=> $page+1]) ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

        </div>

        <?php elseif (isset($_GET['search_submit']) && $total === 0 && empty($errors)): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-search text-muted" style="font-size:3rem"></i>
                <p class="mt-3 text-muted">Tidak ada buku yang cocok dengan kriteria Anda.</p>
                <a href="search_advanced.php" class="btn btn-outline-primary btn-sm">Reset Pencarian</a>
            </div>
        </div>

        <?php else: ?>
        <!-- State awal (belum search) -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-book-half text-primary" style="font-size:3.5rem;opacity:.4"></i>
                <h6 class="mt-3 text-muted">Temukan Buku Koleksi Anda</h6>
                <p class="text-muted small">Gunakan filter di sebelah kiri untuk mencari buku berdasarkan kata kunci, kategori, harga, tahun, dan ketersediaan.</p>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>