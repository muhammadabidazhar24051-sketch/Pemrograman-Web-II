<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

// --- search ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$filter_gender = isset($_GET['jk']) ? $_GET['jk'] : '';

// --- pagination ---
$per_page = 10; // data per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// --- build query dengan kondisi ---
$where = "WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $where .= " AND (nama LIKE ? OR email LIKE ? OR telepon LIKE ?)";
    $search_like = "%$search%";
    $params = array_merge($params, [$search_like, $search_like, $search_like]);
    $types .= "sss";
}
if ($filter_status !== '') {
    $where .= " AND status = ?";
    $params[] = $filter_status;
    $types .= "s";
}
if ($filter_gender !== '') {
    $where .= " AND jenis_kelamin = ?";
    $params[] = $filter_gender;
    $types .= "s";
}

// export excel
if (isset($_GET['export']) && $_GET['export'] == 1) {
    // header buat download excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="anggota_' . date('Ymd') . '.xls"');
    
    $sql_export = "SELECT kode_anggota, nama, email, telepon, alamat, 
                          tanggal_lahir, jenis_kelamin, pekerjaan, 
                          tanggal_daftar, status 
                   FROM anggota $where ORDER BY nama";
    $stmt_exp = $conn->prepare($sql_export);
    if (!empty($params)) $stmt_exp->bind_param($types, ...$params);
    $stmt_exp->execute();
    $res_exp = $stmt_exp->get_result();
    
    // agar output sebagai HTML table, excel bisa baca format ini
    echo '<table border="1">';
    echo '<tr>
        <th>Kode</th><th>Nama</th><th>Email</th><th>Telepon</th>
        <th>Alamat</th><th>Tgl Lahir</th><th>JK</th><th>Pekerjaan</th>
        <th>Tgl Daftar</th><th>Status</th>
    </tr>';
    while ($r = $res_exp->fetch_assoc()) {
        echo '<tr>
            <td>'.$r['kode_anggota'].'</td>
            <td>'.$r['nama'].'</td>
            <td>'.$r['email'].'</td>
            <td style="mso-number-format:\'\@\'">'.$r['telepon'].'</td>
            <td>'.$r['alamat'].'</td>
            <td>'.$r['tanggal_lahir'].'</td>
            <td>'.$r['jenis_kelamin'].'</td>
            <td>'.$r['pekerjaan'].'</td>
            <td>'.$r['tanggal_daftar'].'</td>
            <td>'.$r['status'].'</td>
        </tr>';
    }
    echo '</table>';
    exit; 
}

// statistik dashboard
$stat = $conn->query("
    SELECT 
        COUNT(*) as total,
        SUM(status = 'Aktif') as aktif,
        SUM(status = 'Nonaktif') as nonaktif,
        SUM(jenis_kelamin = 'Laki-laki') as laki,
        SUM(jenis_kelamin = 'Perempuan') as perempuan
    FROM anggota
")->fetch_assoc();

// hitung total data untuk pagination
$count_sql = "SELECT COUNT(*) as total FROM anggota $where";
$stmt_count = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$total = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $per_page);

// query data dengan limit
$sql = "SELECT * FROM anggota $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$all_params = array_merge($params, [$per_page, $offset]);
$all_types = $types . "ii";
$stmt->bind_param($all_types, ...$all_params);
$stmt->execute();
$result = $stmt->get_result();

// success messages dari redirect 
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

    <!-- pesan sukses/error -->
    <?php if ($msg === 'created'): ?>
        <div class="alert alert-success">Anggota berhasil ditambahkan!</div>
    <?php elseif ($msg === 'updated'): ?>
        <div class="alert alert-success">Data anggota berhasil diperbarui!</div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="alert alert-warning">Anggota berhasil dihapus.</div>
    <?php endif; ?>

    <!-- statistik dashboard -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Anggota</h6>
                    <h2><?= $stat['total'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Aktif</h6>
                    <h2><?= $stat['aktif'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h6 class="card-title">Nonaktif</h6>
                    <h2><?= $stat['nonaktif'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Laki-laki / Perempuan</h6>
                    <h2><?= $stat['laki'] ?> / <?= $stat['perempuan'] ?></h2>
                </div>
            </div>
        </div>
    </div>


    <!-- judul dan menambah angota -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Anggota Perpustakaan</h4>
        <a href="create.php" class="btn btn-primary">+ Tambah Anggota</a>
    </div>

    <!-- from search + filter -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" 
                   placeholder="Cari nama / email / telepon..."
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Aktif" <?= $filter_status==='Aktif'?'selected':'' ?>>Aktif</option>
                <option value="Nonaktif" <?= $filter_status==='Nonaktif'?'selected':'' ?>>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="jk" class="form-select">
                <option value="">Semua JK</option>
                <option value="Laki-laki" <?= $filter_gender==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $filter_gender==='Perempuan'?'selected':'' ?>>Perempuan</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-secondary w-100">Cari</button>
        </div>
        <div class="col-md-1">
            <a href="index.php" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        <div class="col-md-1">
            <a href="index.php?export=1&search=<?= urlencode($search) ?>&status=<?= $filter_status ?>&jk=<?= $filter_gender ?>" 
               class="btn btn-success w-100">Excel</a>
        </div>
    </form>

    <!-- tabel data -->
    <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Foto</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>JK</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $no = $offset + 1;
        while ($row = $result->fetch_assoc()): 
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <?php if ($row['foto']): ?>
                        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" 
                             width="45" height="45" 
                             style="object-fit:cover;border-radius:50%">
                    <?php else: ?>
                        <div style="width:45px;height:45px;background:#ddd;border-radius:50%;
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:18px;">👤</div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['kode_anggota']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['telepon']) ?></td>
                <td>
                    <!-- badge jenis kelamin -->
                    <span class="badge <?= $row['jenis_kelamin']==='Laki-laki' ? 'bg-primary' : 'bg-danger' ?>">
                        <?= $row['jenis_kelamin'] ?>
                    </span>
                </td>
                <td>
                    <!-- badge status dengan warna berbeda -->
                    <span class="badge <?= $row['status']==='Aktif' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td>
                    <a href="update.php?id=<?= $row['id_anggota'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $row['id_anggota'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Apakah anda yakin ingin menghapus anggota <?= addslashes($row['nama']) ?>?')">
                       Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if ($total === 0): ?>
            <tr><td colspan="9" class="text-center text-muted">Tidak ada data yang ditemukan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>

    <!-- pagination -->
    <?php if ($total_pages > 1): ?>
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" 
                       href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= $filter_status ?>&jk=<?= $filter_gender ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

    <p class="text-muted">Total: <?= $total ?> anggota</p>
</div>
</body>
</html>

<?php
require_once '../../includes/footer.php';
?>