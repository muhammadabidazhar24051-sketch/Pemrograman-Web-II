<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('buku')->insert([
            ['judul' => 'Belajar Laravel', 'pengarang' => 'Budi', 'harga' => 100000, 'stok' => 10, 'tahun_terbit' => 2025],
            ['judul' => 'Pemrograman Web', 'pengarang' => 'Ani', 'harga' => 150000, 'stok' => 3, 'tahun_terbit' => 2026],
        ]);
    }
}
