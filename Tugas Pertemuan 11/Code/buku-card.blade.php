<div class="card border-0 shadow-sm h-100" style="transition: transform 0.2s;"
     onmouseover="this.style.transform='translateY(-4px)'"
     onmouseout="this.style.transform='translateY(0)'">

    {{-- ── HEADER CARD: Judul & Badge Kategori ─────────────────────── --}}
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-start pt-3 pb-2">
        <div class="me-2">
            <h6 class="card-title fw-bold mb-1 text-dark lh-sm">{{ $buku->judul }}</h6>
            <small class="text-muted">{{ $buku->pengarang }}</small>
        </div>

        {{-- Badge Kategori --}}
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill text-nowrap">
            {{ $buku->kategori ?? 'Umum' }}
        </span>
    </div>

    {{-- ── BODY CARD: Detail Data ────────────────────────────────────── --}}
    <div class="card-body">

        {{-- Harga --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted small"><i class="bi bi-tag me-1"></i>Harga</span>
            <span class="fw-semibold text-dark">{{ $buku->harga_format }}</span>
        </div>

        {{-- Stok --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small"><i class="bi bi-boxes me-1"></i>Stok</span>
            <span class="fw-semibold text-dark">{{ $buku->stok }} buku</span>
        </div>

        {{-- Divider --}}
        <hr class="my-2">

        {{-- Badge Status Ketersediaan --}}
        <div class="text-center">
            @if ($buku->tersedia)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                    <i class="bi bi-check-circle-fill me-1"></i>Tersedia
                </span>
            @else
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                    <i class="bi bi-x-circle-fill me-1"></i>Habis
                </span>
            @endif
        </div>
    </div>

    {{-- ── FOOTER CARD: Tombol Aksi (kondisional) ───────────────────── --}}
    @if ($showActions)
        <div class="card-footer bg-white border-top-0 pb-3 d-flex gap-2">
            <a href="{{ route('buku.show', $buku->id) }}"
               class="btn btn-outline-primary btn-sm flex-fill">
                <i class="bi bi-eye me-1"></i>Detail
            </a>
            <a href="{{ route('buku.edit', $buku->id) }}"
               class="btn btn-outline-secondary btn-sm flex-fill">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
        </div>
    @endif

</div>