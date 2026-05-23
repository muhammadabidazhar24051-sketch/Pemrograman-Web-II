<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\KategoriController;
use App\Models\Buku;
use App\Models\Anggota;


Route::get('/', function () {
    return view('welcome');
});

// Rute Utama Perpustakaan (Menggunakan Controller dan Named Route)
Route::get('/perpustakaan', [PerpustakaanController::class, 'index'])->name('perpus.home');
Route::get('/buku/{id}', [PerpustakaanController::class, 'show']);
Route::get('/about', [PerpustakaanController::class, 'about']);

Route::get('/hello', function () {
    return 'Hello dari Laravel!';
});

Route::get('/info', function () {
    return '<h1>Sistem Perpustakaan</h1><p>Selamat datang!</p>';
});

Route::get('/test-route', function () {
    $url = route('perpus.home');
    return "URL perpustakaan: " . $url;
});

// Rute Dummy Buku (JSON)
Route::get('/buku-json-test', function () {
    return [
        'judul' => 'Laravel Programming',
        'pengarang' => 'John Doe',
        'harga' => 150000
    ];
});

// Rute Latihan Multiple Parameter
Route::get('/search/{kategori}/{keyword}', function ($kategori, $keyword) {
    return "Cari buku kategori: $kategori dengan keyword: $keyword";
});

// ------------------------------------------------------------------
// Rute Anggota (Tugas 1 - Pertemuan 9)
// ------------------------------------------------------------------
Route::get('/anggota', function () {
    $anggota_list = [
        ['id' => 1, 'kode' => 'AGT-001', 'nama' => 'Budi Santoso', 'email' => 'budi@email.com', 'telepon' => '081234567890', 'alamat' => 'Jakarta', 'status' => 'Aktif'],
        ['id' => 2, 'kode' => 'AGT-002', 'nama' => 'Siti Rahayu', 'email' => 'siti@email.com', 'telepon' => '082345678901', 'alamat' => 'Bandung', 'status' => 'Aktif'],
        ['id' => 3, 'kode' => 'AGT-003', 'nama' => 'Ahmad Fauzi', 'email' => 'ahmad@email.com', 'telepon' => '083456789012', 'alamat' => 'Surabaya', 'status' => 'Non-Aktif'],
        ['id' => 4, 'kode' => 'AGT-004', 'nama' => 'Dewi Lestari', 'email' => 'dewi@email.com', 'telepon' => '084567890123', 'alamat' => 'Yogyakarta', 'status' => 'Aktif'],
        ['id' => 5, 'kode' => 'AGT-005', 'nama' => 'Rizky Pratama', 'email' => 'rizky@email.com', 'telepon' => '085678901234', 'alamat' => 'Semarang', 'status' => 'Aktif'],
    ];
    return view('anggota.index', ['anggota_list' => $anggota_list]);
});

Route::get('/anggota/{id}', function ($id) {
    $anggota_list = [
        ['id' => 1, 'kode' => 'AGT-001', 'nama' => 'Budi Santoso', 'email' => 'budi@email.com', 'telepon' => '081234567890', 'alamat' => 'Jakarta', 'status' => 'Aktif'],
        ['id' => 2, 'kode' => 'AGT-002', 'nama' => 'Siti Rahayu', 'email' => 'siti@email.com', 'telepon' => '082345678901', 'alamat' => 'Bandung', 'status' => 'Aktif'],
        ['id' => 3, 'kode' => 'AGT-003', 'nama' => 'Ahmad Fauzi', 'email' => 'ahmad@email.com', 'telepon' => '083456789012', 'alamat' => 'Surabaya', 'status' => 'Non-Aktif'],
        ['id' => 4, 'kode' => 'AGT-004', 'nama' => 'Dewi Lestari', 'email' => 'dewi@email.com', 'telepon' => '084567890123', 'alamat' => 'Yogyakarta', 'status' => 'Aktif'],
        ['id' => 5, 'kode' => 'AGT-005', 'nama' => 'Rizky Pratama', 'email' => 'rizky@email.com', 'telepon' => '085678901234', 'alamat' => 'Semarang', 'status' => 'Aktif'],
    ];

    $anggota = null;
    foreach ($anggota_list as $item) {
        if ($item['id'] == $id) {
            $anggota = $item;
            break;
        }
    }

    if (!$anggota) {
        abort(404, 'Anggota tidak ditemukan.');
    }
    return view('anggota.show', ['anggota' => $anggota]);
});
// ------------------------------------------------------------------
// Rute Kategori Buku (Tugas 2 - Pertemuan 9)
// ------------------------------------------------------------------
Route::get('/kategori/search/{keyword}', [KategoriController::class, 'search'])->name('kategori.search');
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('kategori.show');


// =====================================================================
// PERTEMUAN 10 - TUGAS 2: TESTING ACCESSOR & SCOPE 
// =====================================================================
Route::get('/test-accessor-scope', function () {

    // ------------------------------------------------------------------
    //  Semua Buku: accessor status_stok_badge & tahun_label
    // ------------------------------------------------------------------
    $semuaBuku = Buku::all();

    $htmlBukuAccessor = '';
    foreach ($semuaBuku as $buku) {
        $htmlBukuAccessor .= "
            <tr>
                <td>{$buku->judul}</td>
                <td>{$buku->pengarang}</td>
                <td>Rp " . number_format($buku->harga, 0, ',', '.') . "</td>
                <td>{$buku->stok}</td>
                <td>{!! $buku->status_stok_badge !!}</td>
                <td>{$buku->tahun_label}</td>
            </tr>
        ";
    }

    // ------------------------------------------------------------------
    //  Scope terbaru() : tahun_terbit >= 2024
    // ------------------------------------------------------------------
    $bukuTerbaru = Buku::terbaru()->get();

    $htmlBukuTerbaru = '';
    foreach ($bukuTerbaru as $buku) {
        $htmlBukuTerbaru .= "
            <tr>
                <td>{$buku->judul}</td>
                <td>{$buku->pengarang}</td>
                <td>{$buku->tahun_terbit}</td>
                <td>{!! $buku->status_stok_badge !!}</td>
            </tr>
        ";
    }

    // ------------------------------------------------------------------
    //  Scope stokMenipis() : stok < 5
    // ------------------------------------------------------------------
    $bukuMenipis = Buku::stokMenipis()->get();

    $htmlBukuMenipis = '';
    if ($bukuMenipis->isEmpty()) {
        $htmlBukuMenipis = '<tr><td colspan="4" class="text-center text-muted">Tidak ada buku dengan stok menipis.</td></tr>';
    } else {
        foreach ($bukuMenipis as $buku) {
            $htmlBukuMenipis .= "
                <tr>
                    <td>{$buku->judul}</td>
                    <td>{$buku->pengarang}</td>
                    <td>{$buku->stok}</td>
                    <td>{!! $buku->status_stok_badge !!}</td>
                </tr>
            ";
        }
    }

    // ------------------------------------------------------------------
    //  Scope hargaRange() : Rp 100.000 - Rp 200.000
    // ------------------------------------------------------------------
    $bukuHargaRange = Buku::hargaRange(100000, 200000)->get();

    $htmlHargaRange = '';
    if ($bukuHargaRange->isEmpty()) {
        $htmlHargaRange = '<tr><td colspan="4" class="text-center text-muted">Tidak ada buku dalam rentang harga ini.</td></tr>';
    } else {
        foreach ($bukuHargaRange as $buku) {
            $htmlHargaRange .= "
                <tr>
                    <td>{$buku->judul}</td>
                    <td>{$buku->pengarang}</td>
                    <td>Rp " . number_format($buku->harga, 0, ',', '.') . "</td>
                    <td>{!! $buku->status_stok_badge !!}</td>
                </tr>
            ";
        }
    }

    // ------------------------------------------------------------------
    //  Semua Anggota: accessor status_badge & kategori_usia
    // ------------------------------------------------------------------
    $semuaAnggota = Anggota::all();

    $htmlAnggotaAccessor = '';
    foreach ($semuaAnggota as $anggota) {
        $htmlAnggotaAccessor .= "
            <tr>
                <td>{$anggota->nama}</td>
                <td>{$anggota->email}</td>
                <td>{$anggota->umur} tahun</td>
                <td>{!! $anggota->status_badge !!}</td>
                <td>{$anggota->kategori_usia}</td>
            </tr>
        ";
    }

    // ------------------------------------------------------------------
    //  Scope terdaftarBulanIni()
    // ------------------------------------------------------------------
    $anggotaBulanIni = Anggota::terdaftarBulanIni()->get();

    $htmlAnggotaBulanIni = '';
    if ($anggotaBulanIni->isEmpty()) {
        $htmlAnggotaBulanIni = '<tr><td colspan="4" class="text-center text-muted">Tidak ada anggota baru bulan ini.</td></tr>';
    } else {
        foreach ($anggotaBulanIni as $anggota) {
            // Memastikan data tanggal aman dan tidak memicu crash
            $tanggal = ($anggota->tanggal_daftar instanceof \Carbon\Carbon) 
                ? $anggota->tanggal_daftar->format('d M Y') 
                : date('d M Y', strtotime($anggota->tanggal_daftar));

            $htmlAnggotaBulanIni .= "
                <tr>
                    <td>{$anggota->nama}</td>
                    <td>{$anggota->email}</td>
                    <td>{$tanggal}</td>
                    <td>{!! $anggota->status_badge !!}</td>
                </tr>
            ";
        }
    }

    // ------------------------------------------------------------------
    // Render halaman html & bootstrap
    // ------------------------------------------------------------------
    return '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Testing Accessor & Scope - Pertemuan 10</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
    <div class="container py-4">

        <h1 class="mb-4 text-center fw-bold">Testing Accessor &amp; Scope</h1>
        <p class="text-center text-muted mb-5">Database dengan Migration &amp; Model</p>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Semua Buku - Accessor: <code>status_stok_badge</code> &amp; <code>tahun_label</code></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul</th><th>Pengarang</th><th>Harga</th>
                            <th>Stok</th><th>Status Stok</th><th>Tahun Label</th>
                        </tr>
                    </thead>
                    <tbody>' . $htmlBukuAccessor . '</tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Scope <code>terbaru()</code> - tahun_terbit &ge; 2024</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr><th>Judul</th><th>Pengarang</th><th>Tahun Terbit</th><th>Status Stok</th></tr>
                    </thead>
                    <tbody>' . $htmlBukuTerbaru . '</tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Scope <code>stokMenipis()</code> - stok &lt; 5</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr><th>Judul</th><th>Pengarang</th><th>Stok</th><th>Status Stok</th></tr>
                    </thead>
                    <tbody>' . $htmlBukuMenipis . '</tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Scope <code>hargaRange(100000, 200000)</code></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr><th>Judul</th><th>Pengarang</th><th>Harga</th><th>Status Stok</th></tr>
                    </thead>
                    <tbody>' . $htmlHargaRange . '</tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Semua Anggota - Accessor: <code>status_badge</code> &amp; <code>kategori_usia</code></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr><th>Nama</th><th>Email</th><th>Umur</th><th>Status</th><th>Kategori Usia</th></tr>
                    </thead>
                    <tbody>' . $htmlAnggotaAccessor . '</tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Scope <code>terdaftarBulanIni()</code></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr><th>Nama</th><th>Email</th><th>Tanggal Daftar</th><th>Status</th></tr>
                    </thead>
                    <tbody>' . $htmlAnggotaBulanIni . '</tbody>
                </table>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>';
});