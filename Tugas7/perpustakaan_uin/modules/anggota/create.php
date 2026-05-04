<?php
require_once '../../config/db.php';

$errors = [];
$data = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama          = trim($_POST['nama'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $telepon       = trim($_POST['telepon'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $pekerjaan     = trim($_POST['pekerjaan'] ?? '');
    $status        = $_POST['status'] ?? 'Aktif';
    $tanggal_daftar = date('Y-m-d'); // default: hari ini

    $data = compact('nama','email','telepon','alamat','tanggal_lahir',
                    'jenis_kelamin','pekerjaan','status');

    // === VALIDASI ===
    // Required fields
    if ($nama === '')          $errors['nama'] = 'Nama wajib diisi.';
    if ($email === '')         $errors['email'] = 'Email wajib diisi.';
    if ($telepon === '')       $errors['telepon'] = 'Telepon wajib diisi.';
    if ($alamat === '')        $errors['alamat'] = 'Alamat wajib diisi.';
    if ($tanggal_lahir === '') $errors['tanggal_lahir'] = 'Tanggal lahir wajib diisi.';
    if ($jenis_kelamin === '') $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih.';
 
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    }

    if ($telepon !== '' && !preg_match('/^08[0-9]{8,11}$/', $telepon)) {
        $errors['telepon'] = 'Telepon harus diawali 08 dan 10-13 digit.';
    }

    // Validasi umur minimal 10 tahun
    if ($tanggal_lahir !== '') {
        $lahir = new DateTime($tanggal_lahir);
        $sekarang = new DateTime();
        $umur = $sekarang->diff($lahir)->y;
        if ($umur < 10) {
            $errors['tanggal_lahir'] = 'Umur minimal 10 tahun.';
        }
    }

    // Cek email unik
    if (empty($errors['email'])) {
        $cek = $conn->prepare("SELECT id_anggota FROM anggota WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        if ($cek->get_result()->num_rows > 0) {
            $errors['email'] = 'Email sudah terdaftar.';
        }
    }

    // === UPLOAD FOTO ===
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $ext_allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_allowed)) {
            $errors['foto'] = 'Format foto harus JPG/PNG/GIF.';
        } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $errors['foto'] = 'Ukuran foto maksimal 2MB.';
        } else {
            // Nama file unik agar tidak tabrakan
            $foto = uniqid('foto_') . '.' . $ext;
        }
    }

    // === GENERATE KODE ANGGOTA OTOMATIS ===
    // Format: ANG + tahun + nomor urut 
    if (empty($errors)) {
        $tahun = date('Y');
        $res = $conn->query("SELECT COUNT(*) as total FROM anggota WHERE YEAR(created_at) = $tahun");
        $urut = $res->fetch_assoc()['total'] + 1;
        $kode_anggota = 'ANG' . $tahun . str_pad($urut, 3, '0', STR_PAD_LEFT);

        if ($foto !== null) {
            move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/$foto");
        }

        $stmt = $conn->prepare("
            INSERT INTO anggota 
            (kode_anggota, nama, email, telepon, alamat, tanggal_lahir, 
             jenis_kelamin, pekerjaan, tanggal_daftar, status, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssssssss",
            $kode_anggota, $nama, $email, $telepon, $alamat, $tanggal_lahir,
            $jenis_kelamin, $pekerjaan, $tanggal_daftar, $status, $foto
        );

        if ($stmt->execute()) {
            header("Location: index.php?msg=created");
            exit;
        } else {
            $errors['db'] = 'Gagal menyimpan data: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:700px">
    <h4>Tambah Anggota Baru</h4>
    <a href="index.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

    <?php if (isset($errors['db'])): ?>
        <div class="alert alert-danger"><?= $errors['db'] ?></div>
    <?php endif; ?>

    <!-- enctype multipart wajib untuk upload file agar gambar muncul bukam hanya nama file -->
    <form method="POST" enctype="multipart/form-data" class="card p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control <?= isset($errors['nama'])?'is-invalid':'' ?>" 
                       value="<?= htmlspecialchars($data['nama'] ?? '') ?>">
                <?php if (isset($errors['nama'])): ?>
                    <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control <?= isset($errors['email'])?'is-invalid':'' ?>"
                       value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Telepon * (08xxxxxxxxxx)</label>
                <input type="text" name="telepon" class="form-control <?= isset($errors['telepon'])?'is-invalid':'' ?>"
                       value="<?= htmlspecialchars($data['telepon'] ?? '') ?>">
                <?php if (isset($errors['telepon'])): ?>
                    <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Lahir * (min. 10 tahun)</label>
                <input type="date" name="tanggal_lahir" class="form-control <?= isset($errors['tanggal_lahir'])?'is-invalid':'' ?>"
                       value="<?= $data['tanggal_lahir'] ?? '' ?>"
                       max="<?= date('Y-m-d', strtotime('-10 years')) ?>">
                <?php if (isset($errors['tanggal_lahir'])): ?>
                    <div class="invalid-feedback"><?= $errors['tanggal_lahir'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenis Kelamin *</label>
                <select name="jenis_kelamin" class="form-select <?= isset($errors['jenis_kelamin'])?'is-invalid':'' ?>">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" <?= ($data['jenis_kelamin']??'')==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($data['jenis_kelamin']??'')==='Perempuan'?'selected':'' ?>>Perempuan</option>
                </select>
                <?php if (isset($errors['jenis_kelamin'])): ?>
                    <div class="invalid-feedback"><?= $errors['jenis_kelamin'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pekerjaan</label>
                <input type="text" name="pekerjaan" class="form-control"
                       value="<?= htmlspecialchars($data['pekerjaan'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat *</label>
                <textarea name="alamat" rows="3" class="form-control <?= isset($errors['alamat'])?'is-invalid':'' ?>"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
                <?php if (isset($errors['alamat'])): ?>
                    <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Aktif" <?= ($data['status']??'Aktif')==='Aktif'?'selected':'' ?>>Aktif</option>
                    <option value="Nonaktif" <?= ($data['status']??'')==='Nonaktif'?'selected':'' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto (opsional, maks 2MB)</label>
                <input type="file" name="foto" accept=".jpg,.jpeg,.png,.gif"
                       class="form-control <?= isset($errors['foto'])?'is-invalid':'' ?>">
                <?php if (isset($errors['foto'])): ?>
                    <div class="invalid-feedback"><?= $errors['foto'] ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan Anggota</button>
            <a href="index.php" class="btn btn-secondary ms-2">Batal</a>
        </div>
    </form>
</div>
</body>
</html>