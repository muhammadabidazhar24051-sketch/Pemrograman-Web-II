<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('anggota')->insert([
            ['nama' => 'Abid', 'umur' => 20, 'status' => 'Aktif', 'tanggal_daftar' => '2026-05-20'],
            ['nama' => 'Budi', 'umur' => 22, 'status' => 'Non-Aktif', 'tanggal_daftar' => '2026-05-23'],
        ]);
    }
}
