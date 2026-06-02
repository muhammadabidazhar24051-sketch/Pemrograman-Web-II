<?php
 
namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use Illuminate\Http\Request;
use App\Models\Buku;
 
class BukuController extends Controller
{
    public function index()
    {
        // Ambil semua data buku dari database
        $bukus = Buku::latest()->get();
        
        // Statistik untuk card (selalu dihitung dari SELURUH data)
        $totalBuku    = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis    = Buku::where('stok', 0)->count();

        // Ambil daftar tahun unik untuk dropdown filter
        $daftarTahun = Buku::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');
        
        // Return view dengan data
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'daftarTahun'
        ));
    }

    /**
     * Search & Filter Advanced (Tugas 3 - Pertemuan 11)
     * Route: GET /buku/search
     */
    public function search(Request $request)
    {
        $query = Buku::query();

        // ── Filter 1: Keyword (judul, pengarang, atau penerbit) ──────────────
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('pengarang', 'like', "%{$keyword}%")
                  ->orWhere('penerbit', 'like', "%{$keyword}%");
            });
        }

        // ── Filter 2: Tahun Terbit ───────────────────────────────────────────
        if ($request->filled('tahun_terbit')) {
            $query->where('tahun_terbit', $request->tahun_terbit);
        }

        // ── Filter 3: Status Stok (Menggunakan scope model yang sudah ada) ────
        if ($request->filled('status_stok')) {
            if ($request->status_stok === 'tersedia') {
                $query->tersedia(); // memanggil scopeTersedia()
            } elseif ($request->status_stok === 'habis') {
                $query->where('stok', 0);
            }
        }

        // Eksekusi data hasil filter/pencarian
        $bukus = $query->latest()->get();

        // Statistik card tetap global dari database
        $totalBuku    = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis    = Buku::where('stok', 0)->count();

        // Ambil daftar tahun untuk dropdown pencarian agar tidak kosong
        $daftarTahun = Buku::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');

        // Pertahankan input pencarian agar form tidak reset setelah submit
        $searchParams = $request->only(['keyword', 'tahun_terbit', 'status_stok']);

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'daftarTahun',
            'searchParams'
        ));
    }
 
    public function create()
    {
        // Implementasi di pertemuan 12
        return view('buku.create');
    }
    
    // ==================================================================
    // Update BukuController - Method store()
    // ==================================================================
    public function store(StoreBukuRequest $request)
    {
        try {
            // Create buku baru dengan validated data
            Buku::create($request->validated());
            
            // Redirect dengan success message
            return redirect()->route('buku.index')
                             ->with('success', 'Buku berhasil ditambahkan');

        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }
 
    public function show(string $id)
    {
        // Find buku by ID, throw 404 if not found
        $buku = Buku::findOrFail($id);
        
        // Return view detail buku
        return view('buku.show', compact('buku'));
    }
 
    public function edit(string $id)
    {
        // Akan diimplementasi di pertemuan 12
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }
 
    public function update(Request $request, string $id)
    {
        // Akan diimplementasi di pertemuan 12
    }
 
    public function destroy(string $id)
    {
        // Akan diimplementasi di pertemuan 12
    }
    
    public function filterKategori($kategori)
    {
        $bukus = Buku::where('kategori', $kategori)->latest()->get();
        
        $totalBuku    = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis    = $bukus->where('stok', 0)->count();

        $daftarTahun = Buku::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');
        
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'daftarTahun',
            'kategori'
        ));
    }
}