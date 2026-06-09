<?php
 
namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
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
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }
 
    /** 
    * Update the specified resource in storage.
    */
    public function update(UpdateBukuRequest $request, string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            
            // Update buku dengan validated data
            $buku->update($request->validated());
            
            // Redirect dengan success message
            return redirect()->route('buku.show', $buku->id)
                            ->with('success', 'Buku berhasil diupdate!');
                            
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
        }
    }
 
    public function destroy(string $id)
    {
        try {
        $buku = Buku::findOrFail($id);
        $judulBuku = $buku->judul;
        
        // Delete buku
        $buku->delete();
        
        // Redirect dengan success message
        return redirect()->route('buku.index')
                         ->with('success', "Buku '{$judulBuku}' berhasil dihapus!");
                         
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                            ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    // ==================================================================
    // Tugas 2: bulk Delete 
    // ==================================================================
    public function bulkDelete(Request $request)
    {
        $ids = $request->buku_ids;
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu buku untuk dihapus!');
        }

        Buku::whereIn('id', $ids)->delete();
        
        return redirect()->route('buku.index')
                         ->with('success', count($ids) . ' buku berhasil dihapus!');
    }

    // ==================================================================
    // Tugas 3: Export CSV
    // ==================================================================
    public function export()
    {
        $bukus = Buku::all();
        $filename = 'buku_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($bukus) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Kode Buku', 'Judul', 'Kategori', 'Pengarang', 
                'Penerbit', 'Tahun', 'ISBN', 'Harga', 'Stok'
            ]);
            
            // Isi Data
            foreach ($bukus as $buku) {
                fputcsv($file, [
                    $buku->kode_buku, $buku->judul, $buku->kategori, $buku->pengarang,
                    $buku->penerbit, $buku->tahun_terbit, $buku->isbn, $buku->harga, $buku->stok,
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
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