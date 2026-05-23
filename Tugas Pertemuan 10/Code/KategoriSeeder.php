<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_kategori' => 'Programming',
                'deskripsi'     => 'Buku-buku tentang pemrograman dan pengembangan perangkat lunak',
                'icon'          => 'code-slash',
                'warna'         => 'primary',
            ],
            [
                'nama_kategori' => 'Database',
                'deskripsi'     => 'Buku-buku tentang basis data, SQL, dan manajemen data',
                'icon'          => 'database',
                'warna'         => 'success',
            ],
            [
                'nama_kategori' => 'Web Design',
                'deskripsi'     => 'Buku-buku tentang desain web, UI/UX, dan front-end development',
                'icon'          => 'palette',
                'warna'         => 'info',
            ],
            [
                'nama_kategori' => 'Networking',
                'deskripsi'     => 'Buku-buku tentang jaringan komputer dan infrastruktur IT',
                'icon'          => 'wifi',
                'warna'         => 'warning',
            ],
            [
                'nama_kategori' => 'Data Science',
                'deskripsi'     => 'Buku-buku tentang ilmu data, machine learning, dan analitik',
                'icon'          => 'graph-up',
                'warna'         => 'danger',
            ],
        ];

        foreach ($data as $item) {
            Kategori::create($item);
        }
    }
}