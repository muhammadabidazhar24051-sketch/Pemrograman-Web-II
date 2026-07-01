<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use Carbon\Carbon;

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
        // Ambil data transaksi yang terlambat Tugas 3 - P14
        $transaksiTerlambat = Transaksi::with(['anggota', 'buku'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now()->startOfDay())
            ->latest()
            ->get();

        $data = [
            'total_buku'       => Buku::count(),
            'buku_tersedia'    => Buku::where('stok', '>', 0)->count(),
            'buku_habis'       => Buku::where('stok', 0)->count(),
            'total_anggota'    => Anggota::count(),
            'anggota_aktif'    => Anggota::where('status', 'Aktif')->count(),
            'anggota_nonaktif' => Anggota::where('status', 'Non-Aktif')->count(),
            'buku_terbaru'     => Buku::latest()->take(5)->get(),
            'anggota_terbaru'  => Anggota::latest()->take(5)->get(),
            'jumlah_terlambat' => $transaksiTerlambat->count(),
            'list_terlambat'   => $transaksiTerlambat,
        ];

        return view('dashboard', $data);
    }
}