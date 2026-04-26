==================================
-- Bagian 1: STASTISTIKA BUKU
==================================

-- Query 1: Total buku seluruhnya
-- Menghitung jumlah total record buku yang ada di tabel
SELECT COUNT(*) AS total_buku FROM buku;

-- Query 2: Total nilai inventaris (harga × stok)
-- Menjumlahkan hasil perkalian harga dan stok semua buku
SELECT SUM(harga * stok) AS total_nilai_inventaris FROM buku;

-- Query 3: Rata-rata harga buku
-- Menghitung harga rata-rata dari seluruh buku
SELECT AVG(harga) AS rata_rata_harga FROM buku;

-- Query 4: Buku termahal (tampilkan judul dan harga)
-- Mencari buku dengan harga tertinggi
SELECT judul, harga
FROM buku
ORDER BY harga DESC
LIMIT 1;

-- Query 5: Buku dengan stOk terbanyak
-- Mencari buku yang memilikii stok paling banyak
SELECT judul, stok
FROM buku
ORDER BY stok DESC
LIMIT 1;


==================================
-- BAGIAN 2: FILTER DAN PENCARIAN
==================================

-- Query 6: Buku kategori Programming dengan harga < 100.000
-- Memfilter buku berdasarkan kategori dan kondisi harga
SELECT * FROM buku
WHERE kategori = 'Programming' AND harga < 100000;

-- Query 7: Buku yang judulnya mengandung kata "PHP" atau "MySQL"
-- Menggunakan LIKE untuk pencarian pola pada judul
SELECT * FROM buku
WHERE judul LIKE '%PHP%' OR judul LIKE '%MySQL%';

-- Query 8: Buku yang terbit tahun 2024
-- Memfilter buku berdasarkan tahun terbit
SELECT * FROM buku
WHERE tahun_terbit = 2024;

-- Query 9: Buku yang stoknya antara 5-10
-- Menggunakan BETWEEN untuk rentang nilai stok
SELECT * FROM buku
WHERE stok BETWEEN 5 AND 10;

-- Query 10: Buku yang pengarangnya "Budi Raharjo"
-- Memfilter berdasarkan nama pengarang tertentu
SELECT * FROM buku
WHERE pengarang = 'Budi Raharjo';


====================================
-- BAGIAN 3: GROUPING DAN AGREGASI
====================================

-- Query 11: Jumlah buku per kategori beserta total stok per kategori
-- Mengelompokkan buku berdasarkan kategori dan menghitung jumlah dan stok
SELECT
    kategori,
    COUNT(*) AS jumlah_buku,
    SUM(stok) AS total_stok
FROM buku
GROUP BY kategori
ORDER BY jumlah_buku DESC;

-- Query 12: Rata-rata harga per kategori
-- Menghitung harga rata-rata untuk setiap kategori buku
SELECT
    kategori,
    AVG(harga) AS rata_rata_harga
FROM buku
GROUP BY kategori
ORDER BY rata_rata_harga DESC;

-- Query 13: Kategori dengan total nilai inventaris terbesar
-- Menghitung nilai inventaris (harga X stok) per kategori, diurutkan terbesar
SELECT
    kategori,
    SUM(harga * stok) AS total_nilai_inventaris
FROM buku
GROUP BY kategori
ORDER BY total_nilai_inventaris DESC
LIMIT 1;



===========================
-- BAGIAN 4: UPDATE DATA
===========================

-- Cek dulu sebelum update harga Programming
SELECT judul, kategori, harga FROM buku WHERE kategori = 'Programming';

-- Query 14: Naikkan harga semua buku kategori Programming sebesar 5%
-- Mengalikan harga dengan 1.05 (artinya naik 5%)
UPDATE buku
SET harga = harga * 1.05
WHERE kategori = 'Programming';

-- Verifikasi hasil update harga
SELECT judul, kategori, harga FROM buku WHERE kategori = 'Programming';

-- Cek dulu sebelum update stok
SELECT judul, stok FROM buku WHERE stok < 5;

-- Query 15: Tambah stok 10 untuk semua buku yang stoknya < 5
-- Menambahkan 10 ke nilai stok yang saat ini kurang dari 5
UPDATE buku
SET stok = stok + 10
WHERE stok < 5;

-- Verifikasi hasil update stok
SELECT judul, stok FROM buku WHERE stok <= 15;



-- ================================================
-- BAGIAN 5: LAPORAN KHUSUS
-- ================================================

-- Query 16: Daftar buku yang perlu restocking (stok < 5)
-- Menampilkan buku yang stoknya kritis agar segera diisi ulang
SELECT
    kode_buku,
    judul,
    pengarang,
    kategori,
    stok,
    'Perlu Restocking' AS keterangan
FROM buku
WHERE stok < 5
ORDER BY stok ASC;

-- Query 17: Top 5 buku termahal
-- Menampilkan 5 buku dengan harga tertinggi secara berurutan
SELECT
    kode_buku,
    judul,
    pengarang,
    CONCAT('Rp ', FORMAT(harga, 0)) AS harga_format
FROM buku
ORDER BY harga DESC
LIMIT 5;