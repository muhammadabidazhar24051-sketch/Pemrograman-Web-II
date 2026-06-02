<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;

class DashboardController extends Controller
{
    public function home()
    {
        $data = [
            'total_buku'       => Buku::count(),
            'buku_tersedia'    => Buku::where('stok', '>', 0)->count(),
            'buku_habis'       => Buku::where('stok', 0)->count(),
            'total_anggota'    => Anggota::count(),
            'anggota_aktif'    => Anggota::where('status', 'Aktif')->count(),
            'anggota_nonaktif' => Anggota::where('status', 'Non-Aktif')->count(),
            'buku_terbaru'     => Buku::latest()->take(5)->get(),
            'anggota_terbaru'  => Anggota::latest()->take(5)->get(),
        ];

        return view('home', $data);
    }

    // ── TAMBAH METHOD INI ─────────────────────────────────────────
    public function index()
    {
        $data = [
            'total_buku'       => Buku::count(),
            'buku_tersedia'    => Buku::where('stok', '>', 0)->count(),
            'buku_habis'       => Buku::where('stok', 0)->count(),
            'total_anggota'    => Anggota::count(),
            'anggota_aktif'    => Anggota::where('status', 'Aktif')->count(),
            'anggota_nonaktif' => Anggota::where('status', 'Non-Aktif')->count(),
            'buku_terbaru'     => Buku::latest()->take(5)->get(),
            'anggota_terbaru'  => Anggota::latest()->take(5)->get(),
        ];

        return view('dashboard.index', $data);
    }
}