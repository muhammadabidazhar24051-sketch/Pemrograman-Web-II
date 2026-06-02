@extends('layouts.app')
 
@section('title', 'Daftar Buku')

@push('styles')
<style>
    /* Animasi fade-in untuk hasil pencarian */
    .search-results-enter {
        animation: fadeInUp 0.3s ease forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Card buku hover */
    .buku-grid .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .buku-grid .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12) !important;
    }

    /* Form search styling */
    .search-card {
        border: none;
        box-shadow: 0 2px 12px rgba(13, 110, 253, .1);
        border-top: 3px solid #0d6efd;
    }
    .search-card .form-label {
        font-size: .8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #6c757d;
    }

    /* Badge hasil pencarian */
    .filter-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        background-color: #f1f3f5;
        color: #495057;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
</style>
@endpush
 
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <a href="{{ route('buku.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Buku
    </a>
</div>

{{-- ── NOTIFIKASI BALIKAN / FLASH MESSAGE ───────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
 
{{-- ── STATISTIK CARDS (BAWAAN PRAKTIKUM) ────────────────────────────── --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Buku</h6>
                        <h2 class="mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Tersedia</h6>
                        <h2 class="mb-0 text-success">{{ $bukuTersedia }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Habis</h6>
                        <h2 class="mb-0 text-danger">{{ $bukuHabis }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── FORM SEARCH & FILTER ADVANCED (TUGAS 3) ─────────────────────── --}}
<div class="card search-card mb-4">
    <div class="card-body p-4">
        <form action="{{ route('buku.search') }}" method="GET" id="formSearch">
            <div class="row g-3">
                
                <div class="col-md-4">
                    <label for="keyword" class="form-label">Kata Kunci</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="keyword" id="keyword" class="form-control border-start-0 ps-1" 
                               placeholder="Judul, pengarang, atau penerbit..." 
                               value="{{ $searchParams['keyword'] ?? '' }}">
                        @if(!empty($searchParams['keyword']))
                            <a href="{{ route('buku.search', array_merge($searchParams, ['keyword' => ''])) }}" class="btn btn-outline-secondary border-start-0 d-flex align-items-center bg-white text-muted px-2" title="Hapus kata kunci">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach(['Programming', 'Desain', 'Networking', 'Sains'] as $cat)
                            <option value="{{ $cat }}" {{ ($searchParams['kategori'] ?? '') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <select name="tahun_terbit" id="tahun_terbit" class="form-select">
                        <option value="">Semua</option>
                        @foreach($daftarTahun as $th)
                            <option value="{{ $th }}" {{ ($searchParams['tahun_terbit'] ?? '') == $th ? 'selected' : '' }}>
                                {{ $th }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="ketersediaan" class="form-label">Ketersediaan</label>
                    <select name="ketersediaan" id="ketersediaan" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ ($searchParams['ketersediaan'] ?? '') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ ($searchParams['ketersediaan'] ?? '') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div class="filter-badges d-flex flex-wrap gap-2">
                    @if(!empty(array_filter($searchParams ?? [])) || isset($kategori))
                        <span class="small text-muted align-self-center me-1">Filter aktif:</span>
                        
                        @if(isset($kategori))
                            <span class="filter-badge">Kategori: <strong>{{ $kategori }}</strong></span>
                        @endif
                        @foreach($searchParams ?? [] as $key => $val)
                            @if(!empty($val))
                                <span class="filter-badge">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}: 
                                    <strong>{{ $val == 'tersedia' ? 'Ada Stok' : ($val == 'habis' ? 'Kosong' : $val) }}</strong>
                                </span>
                            @endif
                        @endforeach
                    @endif
                </div>
                
                <div class="d-flex gap-2">
                    @if(!empty(array_filter($searchParams ?? [])) || isset($kategori))
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-sliders me-1"></i>Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── LOGIKA RENDER DAFTAR BUKU (GRID VS LIST VIEW) ─────────────────── --}}
@if ($bukus->isNotEmpty() && (!empty(array_filter($searchParams ?? [])) || isset($kategori)))
    {{-- JIKA SEDANG FILTER/MENCARI: Tampilkan Mode Grid Menggunakan Component Tugas 2 --}}
    <h5 class="mb-3 text-secondary search-results-enter"><i class="bi bi-grid-3x3-gap me-2"></i>Hasil Pencarian Lanjutan</h5>
    <div class="row row-cols-1 row-cols-md-3 g-4 buku-grid search-results-enter">
@endif

@forelse($bukus as $buku)
    @if (!empty(array_filter($searchParams ?? [])) || isset($kategori))
        {{-- Render pakai Blade Component Tugas 2 --}}
        <div class="col">
            <x-buku-card :buku="$buku" :show-actions="true" />
        </div>
    @else
        {{-- TAMPILAN NORMAL (LIST VIEW BAWAAN PRAKTIKUM) --}}
        @if ($loop->first)
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Pengarang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
        @endif
                        <tr>
                            <td><span class="badge bg-secondary">{{ $buku->kode_buku }}</span></td>
                            <td>
                                <div class="fw-bold">{{ $buku->judul }}</div>
                                <small class="text-muted">ISBN: {{ $buku->isbn ?? '-' }}</small>
                            </td>
                            <td>
                                @if ($buku->kategori == 'Programming')
                                    <span class="badge bg-primary">{{ $buku->kategori }}</span>
                                @elseif ($buku->kategori == 'Desain')
                                    <span class="badge bg-success">{{ $buku->kategori }}</span>
                                @elseif ($buku->kategori == 'Networking')
                                    <span class="badge bg-warning text-dark">{{ $buku->kategori }}</span>
                                @else
                                    <span class="badge bg-info text-dark">{{ $buku->kategori }}</span>
                                @endif
                            </td>
                            <td>{{ $buku->pengarang }}</td>
                            <td>{{ $buku->harga_format }}</td>
                            <td>
                                @if ($buku->tersedia)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded">
                                        {{ $buku->stok }} buku
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
        @if ($loop->last)
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
@empty
    <div class="alert alert-info border-0 shadow-sm">
        <i class="bi bi-info-circle me-2"></i>
        Tidak ada data buku yang sesuai dengan filter yang dipilih. 
        <a href="{{ route('buku.index') }}" class="alert-link">Tampilkan semua buku</a>.
    </div>
@endforelse

{{-- Tutup row grid jika mode grid aktif --}}
@if ($bukus->isNotEmpty() && (!empty(array_filter($searchParams ?? [])) || isset($kategori)))
    </div>
@endif
 
{{-- Summary Jumlah Buku --}}
@if ($bukus->count() > 0)
    <div class="text-center mt-4">
        <p class="text-muted small">
            Menampilkan <strong>{{ $bukus->count() }}</strong> buku
            @isset($kategori)
                dari kategori <strong>{{ $kategori }}</strong>
            @endisset
            @if (!empty(array_filter($searchParams ?? [])))
                sesuai hasil pencarian
            @endif
        </p>
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Auto-submit form saat dropdown berubah demi kemudahan user
    document.querySelectorAll('#formSearch select').forEach(function (el) {
        el.addEventListener('change', function () {
            document.getElementById('formSearch').submit();
        });
    });
</script>
@endpush