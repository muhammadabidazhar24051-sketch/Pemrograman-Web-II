<?php
$success = false;
$errors  = [];
$data    = [];

// Field defaults
$nama        = '';
$email       = '';
$telepon     = '';
$alamat      = '';
$jenis_kelamin = '';
$tgl_lahir   = '';
$pekerjaan   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil & sanitasi 
    $nama          = trim(htmlspecialchars($_POST['nama']          ?? ''));
    $email         = trim($_POST['email']         ?? '');
    $telepon       = trim($_POST['telepon']        ?? '');
    $alamat        = trim(htmlspecialchars($_POST['alamat']        ?? ''));
    $jenis_kelamin = trim($_POST['jenis_kelamin']  ?? '');
    $tgl_lahir     = trim($_POST['tgl_lahir']      ?? '');
    $pekerjaan     = trim($_POST['pekerjaan']      ?? '');

    // Validasi Nama 
    if (empty($nama)) {
        $errors['nama'] = 'Nama lengkap wajib diisi.';
    } elseif (strlen($nama) < 3) {
        $errors['nama'] = 'Nama minimal 3 karakter.';
    }

    // Validasi Email
    if (empty($email)) {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    }

    // Validasi Telepon
    if (empty($telepon)) {
        $errors['telepon'] = 'Nomor telepon wajib diisi.';
    } elseif (!preg_match('/^08[0-9]{8,11}$/', $telepon)) {
        $errors['telepon'] = 'Format telepon tidak valid. Harus dimulai 08 dan 10–13 digit.';
    }

    // Validasi Alamat 
    if (empty($alamat)) {
        $errors['alamat'] = 'Alamat wajib diisi.';
    } elseif (strlen($alamat) < 10) {
        $errors['alamat'] = 'Alamat minimal 10 karakter.';
    }

    // Validasi Jenis Kelamin 
    if (empty($jenis_kelamin)) {
        $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih.';
    } elseif (!in_array($jenis_kelamin, ['Laki-laki', 'Perempuan'])) {
        $errors['jenis_kelamin'] = 'Jenis kelamin tidak valid.';
    }

    // Validasi Tanggal Lahir (umur min 10 tahun)
    if (empty($tgl_lahir)) {
        $errors['tgl_lahir'] = 'Tanggal lahir wajib diisi.';
    } else {
        $lahir    = new DateTime($tgl_lahir);
        $sekarang = new DateTime();
        $umur     = $sekarang->diff($lahir)->y;
        if ($umur < 10) {
            $errors['tgl_lahir'] = 'Umur minimal 10 tahun untuk mendaftar.';
        }
    }

    // Validasi Pekerjaan
    $pekerjaan_valid = ['Pelajar', 'Mahasiswa', 'Pegawai', 'Lainnya'];
    if (empty($pekerjaan)) {
        $errors['pekerjaan'] = 'Pekerjaan wajib dipilih.';
    } elseif (!in_array($pekerjaan, $pekerjaan_valid)) {
        $errors['pekerjaan'] = 'Pekerjaan tidak valid.';
    }

    // Jika lolos semua validasi
    if (empty($errors)) {
        $success = true;
        $data = [
            'Nama Lengkap'   => $nama,
            'Email'          => $email,
            'Telepon'        => $telepon,
            'Alamat'         => $alamat,
            'Jenis Kelamin'  => $jenis_kelamin,
            'Tanggal Lahir'  => date('d F Y', strtotime($tgl_lahir)) . ' (Umur: ' . $umur . ' tahun)',
            'Pekerjaan'      => $pekerjaan,
        ];
        // Reset form setelah sukses
        $nama = $email = $telepon = $alamat = $jenis_kelamin = $tgl_lahir = $pekerjaan = '';
    }
}

// Helper: is-invalid class
function inv($field, $errors) {
    return isset($errors[$field]) ? 'is-invalid' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1a3c6e;
            --accent:  #e8a020;
        }
        body { background: #f0f4f8; }
        .navbar { background: var(--primary) !important; }
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary) 0%, #2d5fa3 100%);
            color: #fff;
        }
        .badge-no {
            background: var(--accent);
            color: #fff;
            width: 28px; height: 28px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%; font-weight: 700; font-size: .8rem;
            margin-right: 8px;
        }
        .success-card { border-left: 5px solid #198754; }
        .form-label { font-weight: 600; font-size: .9rem; color: #344767; }
        .required-star { color: #dc3545; }
        .section-title {
            font-size: .75rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #6c757d;
            border-bottom: 2px solid #e9ecef; padding-bottom: 6px; margin-bottom: 16px;
        }
        .data-row th { width: 40%; color: #6c757d; font-weight: 500; }
        .data-row td { font-weight: 600; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-book-half me-2"></i>Perpustakaan Digital
        </a>
        <span class="text-white-50 small">Registrasi Anggota</span>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <!-- SUCCESS CARD -->
            <?php if ($success): ?>
            <div class="card success-card shadow mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width:48px;height:48px;font-size:1.4rem;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-success">Registrasi Berhasil!</h5>
                            <small class="text-muted">Data anggota telah tersimpan</small>
                        </div>
                    </div>
                    <table class="table table-sm table-borderless data-row mb-0">
                        <?php foreach ($data as $key => $val): ?>
                        <tr>
                            <th><?php echo $key; ?></th>
                            <td>: <?php echo htmlspecialchars($val); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- FORM CARD -->
            <div class="card shadow">
                <div class="card-header card-header-custom py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus-fill me-2"></i>Form Registrasi Anggota
                    </h5>
                    <small class="opacity-75">Semua field bertanda <strong>*</strong> wajib diisi</small>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="" novalidate>

                        <!-- INFORMASI PRIBADI -->
                        <p class="section-title"><i class="bi bi-person me-1"></i>Informasi Pribadi</p>

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                <span class="badge-no">1</span>Nama Lengkap <span class="required-star">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?= inv('nama', $errors) ?>"
                                   id="nama" name="nama"
                                   value="<?= htmlspecialchars($nama) ?>"
                                   placeholder="Masukkan nama lengkap Anda">
                            <?php if (isset($errors['nama'])): ?>
                                <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <span class="badge-no">2</span>Email <span class="required-star">*</span>
                            </label>
                            <input type="email"
                                   class="form-control <?= inv('email', $errors) ?>"
                                   id="email" name="email"
                                   value="<?= htmlspecialchars($email) ?>"
                                   placeholder="contoh@email.com">
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Telepon -->
                        <div class="mb-3">
                            <label for="telepon" class="form-label">
                                <span class="badge-no">3</span>Nomor Telepon <span class="required-star">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?= inv('telepon', $errors) ?>"
                                   id="telepon" name="telepon"
                                   value="<?= htmlspecialchars($telepon) ?>"
                                   placeholder="08xxxxxxxxxx">
                            <div class="form-text text-muted">Format: 08xxxxxxxxxx (10–13 digit)</div>
                            <?php if (isset($errors['telepon'])): ?>
                                <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="alamat" class="form-label">
                                <span class="badge-no">4</span>Alamat <span class="required-star">*</span>
                            </label>
                            <textarea class="form-control <?= inv('alamat', $errors) ?>"
                                      id="alamat" name="alamat" rows="3"
                                      placeholder="Masukkan alamat lengkap (minimal 10 karakter)"><?= htmlspecialchars($alamat) ?></textarea>
                            <div class="form-text text-muted">Minimal 10 karakter</div>
                            <?php if (isset($errors['alamat'])): ?>
                                <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- INFORMASI TAMBAHAN -->
                        <p class="section-title"><i class="bi bi-card-list me-1"></i>Informasi Tambahan</p>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label class="form-label d-block">
                                <span class="badge-no">5</span>Jenis Kelamin <span class="required-star">*</span>
                            </label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input <?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>"
                                           type="radio" name="jenis_kelamin" id="laki"
                                           value="Laki-laki"
                                           <?= ($jenis_kelamin === 'Laki-laki') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="laki">
                                        <i class="bi bi-gender-male text-primary me-1"></i>Laki-laki
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input <?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>"
                                           type="radio" name="jenis_kelamin" id="perempuan"
                                           value="Perempuan"
                                           <?= ($jenis_kelamin === 'Perempuan') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="perempuan">
                                        <i class="bi bi-gender-female text-danger me-1"></i>Perempuan
                                    </label>
                                    <?php if (isset($errors['jenis_kelamin'])): ?>
                                        <div class="invalid-feedback"><?= $errors['jenis_kelamin'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <label for="tgl_lahir" class="form-label">
                                    <span class="badge-no">6</span>Tanggal Lahir <span class="required-star">*</span>
                                </label>
                                <input type="date"
                                       class="form-control <?= inv('tgl_lahir', $errors) ?>"
                                       id="tgl_lahir" name="tgl_lahir"
                                       value="<?= htmlspecialchars($tgl_lahir) ?>"
                                       max="<?= date('Y-m-d', strtotime('-10 years')) ?>">
                                <div class="form-text text-muted">Umur minimal 10 tahun</div>
                                <?php if (isset($errors['tgl_lahir'])): ?>
                                    <div class="invalid-feedback"><?= $errors['tgl_lahir'] ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Pekerjaan -->
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan" class="form-label">
                                    <span class="badge-no">7</span>Pekerjaan <span class="required-star">*</span>
                                </label>
                                <select class="form-select <?= inv('pekerjaan', $errors) ?>"
                                        id="pekerjaan" name="pekerjaan">
                                    <option value="">-- Pilih Pekerjaan --</option>
                                    <?php foreach (['Pelajar', 'Mahasiswa', 'Pegawai', 'Lainnya'] as $p): ?>
                                        <option value="<?= $p ?>" <?= ($pekerjaan === $p) ? 'selected' : '' ?>>
                                            <?= $p ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['pekerjaan'])): ?>
                                    <div class="invalid-feedback"><?= $errors['pekerjaan'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Error summary (global) -->
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger py-2 mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <strong><?= count($errors) ?> kesalahan</strong> ditemukan. Periksa field yang ditandai merah.
                        </div>
                        <?php endif; ?>

                        <!-- Submit -->
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-lg" style="background:var(--primary);border-color:var(--primary)">
                                <i class="bi bi-send-fill me-2"></i>Daftar Sekarang
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Form
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>