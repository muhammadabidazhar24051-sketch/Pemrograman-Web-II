<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\KategoriController;
use App\Models\Buku;
use App\Models\Anggota;



// =========================================================================
// ===== pertemuan 11
// ========================================================================
Route::get('/', function () {
    return view('home');
})->name('home');

// ==========================================================================
// Tugas 3: Search & Filter Advanced
// ==========================================================================
Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');

// Bulk Delete dan Export CSV
Route::post('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulk-delete');
Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');

// Custom route untuk filter kategori (Praktikum 11)
// PINDAH KE ATAS RESOURCE AGAR PARAMETER {kategori} TIDAK TERTUKAR DENGAN ID BUKU
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])
     ->name('buku.kategori');

Route::resource('/buku', BukuController::class);
Route::resource('/anggota', \App\Http\Controllers\AnggotaController::class);

// ==========================================================================
// Dashboard (Tugas - 1 Pertemuan 11)
// ==========================================================================
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// ==========================================================================
// ======
// ==========================================================================

Route::get('/hello', function () {
    return 'Hello dari Laravel!';
});

Route::get('/info', function () {
    return '<h1>Sistem Perpustakaan</h1><p>Selamat datang!</p>';
});

Route::get('/buku-lama', function () {
    return [
        'judul' => 'Laravel Programming',
        'pengarang' => 'John Doe',
        'harga' => 150000
    ];
});

Route::get('/buku-detail/{id}', [PerpustakaanController::class, 'show']);

Route::get('/kategori/{nama?}', function ($nama = 'Semua Kategori') {
    return "Menampilkan kategori: " . $nama;
});

Route::get('/search/{kategori}/{keyword}', function ($kategori, $keyword) {
    return "Cari buku kategori: $kategori dengan keyword: $keyword";
});

Route::get('/perpustakaan', [PerpustakaanController::class, 'index'])->name('perpus.home');
Route::get('/about', [PerpustakaanController::class, 'about']);

Route::get('/test-route', function () {
    $url = route('perpus.home');
    return "URL perpustakaan: " . $url;
});

Route::get('/kategori/search/{keyword}', [KategoriController::class, 'search'])->name('kategori.search');
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('kategori.show');
// CORE TESTING ROUTES (DATABASE, ELOQUENT QUERY, ACCESSOR & SCOPE)
// =========================================================================

// Uji Koneksi Database (Praktikum 11)
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();
        return "Koneksi database berhasil!<br />Database: <strong>{$dbName}</strong>";
    } catch (\Exception $e) {
        return "Koneksi database gagal!<br />Error: " . $e->getMessage();
    }
});
 
// Testing Scope & Query Sederhana (Praktikum 11)
Route::get('/test-query', function () {
    $html = '<h1>Testing Query Eloquent</h1>';
    
    // Buku tersedia
    if (method_exists(Buku::class, 'scopeTersedia')) {
        $tersedia = Buku::tersedia()->get();
        $html .= '<h3>Buku Tersedia (Stok > 0): ' . $tersedia->count() . '</h3><ul>';
        foreach ($tersedia as $buku) { $html .= '<li>' . $buku->judul . ' (Stok: ' . $buku->stok . ')</li>'; }
        $html .= '</ul>';
    }
    
    // Buku Programming
    if (method_exists(Buku::class, 'scopeKategori')) {
        $programming = Buku::kategori('Programming')->get();
        $html .= '<h3>Buku Programming: ' . $programming->count() . '</h3><ul>';
        foreach ($programming as $buku) { $html .= '<li>' . $buku->judul . '</li>'; }
        $html .= '</ul>';
    }
    
    // Anggota Aktif
    $aktif = Anggota::aktif()->get();
    $html .= '<h3>Anggota Aktif: ' . $aktif->count() . '</h3><ul>';
    foreach ($aktif as $anggota) {
        $html .= '<li>' . $anggota->nama . ' (' . $anggota->email . ')</li>';
    }
    $html .= '</ul>';
    
    return $html;
});

// =====================================================================================
// Testing Comprehensive Accessor & Scope (Tugas Pertemuan 10)
// =====================================================================================

Route::get('/test-accessor-scope', function () {

    // 1. Semua Buku: accessor status_stok_badge & tahun_label
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

    // 2. Scope terbaru() : tahun_terbit >= 2024
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

    // 3. Scope stokMenipis() : stok < 5
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

    // 4. Scope hargaRange() : Rp 100.000 - Rp 200.000
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

    // 5. Semua Anggota: accessor status_badge & kategori_usia
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

    // 6. Scope terdaftarBulanIni()
    $anggotaBulanIni = Anggota::terdaftarBulanIni()->get();
    $htmlAnggotaBulanIni = '';
    if ($anggotaBulanIni->isEmpty()) {
        $htmlAnggotaBulanIni = '<tr><td colspan="4" class="text-center text-muted">Tidak ada anggota baru bulan ini.</td></tr>';
    } else {
        foreach ($anggotaBulanIni as $anggota) {
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

    // Render Tampilan Menggunakan Bootstrap
    return '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Testing Accessor & Scope - Pertemuan 10 (Terintegrasi P11)</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
    <div class="container py-4">

        <h1 class="mb-4 text-center fw-bold">Testing Accessor &amp; Scope</h1>
        <p class="text-center text-muted mb-5">Gabungan Tugas Pertemuan 10 di dalam Struktur Proyek Praktikum 11</p>

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