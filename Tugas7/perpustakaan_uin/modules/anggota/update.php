<?php
require_once '../../config/db.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) { header("Location: index.php"); exit; }

// mengambil data yang akan diedit 
$stmt = $conn->prepare("SELECT * FROM anggota WHERE id_anggota = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$anggota = $stmt->get_result()->fetch_assoc();
if (!$anggota) { header("Location: index.php"); exit; }

$errors = [];
$data = $anggota; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama          = trim($_POST['nama'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $telepon       = trim($_POST['telepon'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $pekerjaan     = trim($_POST['pekerjaan'] ?? '');
    $status        = $_POST['status'] ?? 'Aktif';

    $data = compact('nama','email','telepon','alamat','tanggal_lahir',
                    'jenis_kelamin','pekerjaan','status');

    // Validasi 
    if ($nama === '')          $errors['nama'] = 'Nama wajib diisi.';
    if ($email === '')         $errors['email'] = 'Email wajib diisi.';
    if ($telepon === '')       $errors['telepon'] = 'Telepon wajib diisi.';
    if ($alamat === '')        $errors['alamat'] = 'Alamat wajib diisi.';
    if ($tanggal_lahir === '') $errors['tanggal_lahir'] = 'Tanggal lahir wajib diisi.';
    if ($jenis_kelamin === '') $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih.';

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Format email tidak valid.';

    if ($telepon !== '' && !preg_match('/^08[0-9]{8,11}$/', $telepon))
        $errors['telepon'] = 'Telepon harus diawali 08 dan 10-13 digit.';

    if ($tanggal_lahir !== '') {
        $umur = (new DateTime())->diff(new DateTime($tanggal_lahir))->y;
        if ($umur < 10) $errors['tanggal_lahir'] = 'Umur minimal 10 tahun.';
    }

    // Cek email unik kecuali untuk data sendiri
    if (empty($errors['email'])) {
        $cek = $conn->prepare("SELECT id_anggota FROM anggota WHERE email = ? AND id_anggota != ?");
        $cek->bind_param("si", $email, $id);
        $cek->execute();
        if ($cek->get_result()->num_rows > 0)
            $errors['email'] = 'Email sudah digunakan anggota lain.';
    }

    // upload foto baru (opsional)
    $foto = $anggota['foto']; // default: pakai foto lama
    if (!empty($_FILES['foto']['name'])) {
        $ext_allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_allowed)) {
            $errors['foto'] = 'Format foto harus JPG/PNG/GIF.';
        } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $errors['foto'] = 'Ukuran foto maksimal 2MB.';
        } else {
            $foto_baru = uniqid('foto_') . '.' . $ext;
            // hapus foto lama jika ada
            if ($anggota['foto'] && file_exists("uploads/" . $anggota['foto'])) {
                unlink("uploads/" . $anggota['foto']);
            }
            move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/$foto_baru");
            $foto = $foto_baru;
        }
    }

    if (empty($errors)) {
        // update ke database
        $stmt = $conn->prepare("
            UPDATE anggota SET
                nama=?, email=?, telepon=?, alamat=?, tanggal_lahir=?,
                jenis_kelamin=?, pekerjaan=?, status=?, foto=?
            WHERE id_anggota=?
        ");
        $stmt->bind_param(
            "sssssssssi",
            $nama, $email, $telepon, $alamat, $tanggal_lahir,
            $jenis_kelamin, $pekerjaan, $status, $foto, $id
        );

        if ($stmt->execute()) {
            header("Location: index.php?msg=updated");
            exit;
        } else {
            $errors['db'] = 'Gagal update: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:700px">
    <h4>Edit Anggota: <?= htmlspecialchars($anggota['nama']) ?></h4>
    <a href="index.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

    <form method="POST" enctype="multipart/form-data" class="card p-4">
        <div class="row g-3">
            <!-- Kode anggota (readonly, hanya bisa melihat tanpa bisa merubah... :) -->
            <div class="col-12">
                <label class="form-label">Kode Anggota</label>
                <input type="text" class="form-control" value="<?= $anggota['kode_anggota'] ?>" readonly>
            </div>
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
                <label class="form-label">Telepon *</label>
                <input type="text" name="telepon" class="form-control <?= isset($errors['telepon'])?'is-invalid':'' ?>"
                       value="<?= htmlspecialchars($data['telepon'] ?? '') ?>">
                <?php if (isset($errors['telepon'])): ?>
                    <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Lahir *</label>
                <input type="date" name="tanggal_lahir" class="form-control <?= isset($errors['tanggal_lahir'])?'is-invalid':'' ?>"
                       value="<?= $data['tanggal_lahir'] ?? '' ?>"
                       max="<?= date('Y-m-d', strtotime('-10 years')) ?>">
                <?php if (isset($errors['tanggal_lahir'])): ?>
                    <div class="invalid-feedback"><?= $errors['tanggal_lahir'] ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenis Kelamin *</label>
                <select name="jenis_kelamin" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" <?= ($data['jenis_kelamin']??'')==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($data['jenis_kelamin']??'')==='Perempuan'?'selected':'' ?>>Perempuan</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pekerjaan</label>
                <input type="text" name="pekerjaan" class="form-control"
                       value="<?= htmlspecialchars($data['pekerjaan'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat *</label>
                <textarea name="alamat" rows="3" class="form-control"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Aktif" <?= ($data['status']??'')==='Aktif'?'selected':'' ?>>Aktif</option>
                    <option value="Nonaktif" <?= ($data['status']??'')==='Nonaktif'?'selected':'' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto Baru (kosongkan jika tidak diubah)</label>
                <?php if ($anggota['foto']): ?>
                    <div class="mb-1">
                        <img src="uploads/<?= htmlspecialchars($anggota['foto']) ?>" 
                             height="60" style="border-radius:8px"> <small>Foto saat ini</small>
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" accept=".jpg,.jpeg,.png,.gif" class="form-control <?= isset($errors['foto'])?'is-invalid':'' ?>">
                <?php if (isset($errors['foto'])): ?>
                    <div class="invalid-feedback"><?= $errors['foto'] ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-warning">Update Anggota</button>
            <a href="index.php" class="btn btn-secondary ms-2">Batal</a>
        </div>
    </form>
</div>
</body>
</html>