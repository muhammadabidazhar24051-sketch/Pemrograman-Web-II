<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     *
     * @var string
     */
    protected $table = 'anggota';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_anggota',
        'nama',
        'email',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'tanggal_daftar',
        'status',
    ];

    /**
     * agar bisa memanggil ->format() dan Carbon methods tanpa error
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_daftar' => 'date',
    ];
    // ===========================================================================================================================================
    // ACCESSOR 
    // ===========================================================================================================================================

    /**
     * Menghitung umur berdasarkan tanggal_lahir
     */
    public function getUmurAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Menghitung lama menjadi anggota
     */
    public function getLamaAnggotaAttribute(): int
    {
        return Carbon::parse($this->tanggal_daftar)->diffInDays(now());
    }

    /**
     * Accessor untuk badge status anggota (TUGAS 2 - P10)
     * Aktif    : badge success   "Aktif"
     * Nonaktif : badge secondary "Nonaktif"
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status === 'Aktif') {
            return '<span class="badge bg-success">Aktif</span>';
        }

        return '<span class="badge bg-secondary">Nonaktif</span>';
    }

    /**
     * Accessor untuk kategori usia berdasarkan umur (TUGAS 2 - P10).
     * umur < 20       → "Remaja"
     * umur 20–50      → "Dewasa"
     * umur > 50       → "Senior"
     * 
     */
    public function getKategoriUsiaAttribute(): string
{
    if ($this->umur < 20) {
        return 'Remaja';
    } elseif ($this->umur <= 50) {
        return 'Dewasa';
    } else {
        return 'Senior';
    }
}
    // ===========================================================================================================================================
    // SCOPE
    // ===========================================================================================================================================

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Scope (TUGAS 2 - P10) 
     * Filter berdasarkan jenis kelamin
     */
    public function scopeJenisKelamin($query, $jk)
    {
        return $query->where('jenis_kelamin', $jk);
    }

    /**
     * Scope (TUGAS 2 - P10)
     * Filter anggota yang mendaftar di bulan & tahun berjalan sekarang
     */
    public function scopeTerdaftarBulanIni($query)
    {
        return $query->whereMonth('tanggal_daftar', now()->month)
                     ->whereYear('tanggal_daftar', now()->year);
    }
}