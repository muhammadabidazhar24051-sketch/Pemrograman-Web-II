===============================
--CREATE DATABASE
===============================
-- mambuat database baru
CREATE DATABASE IF NOT EXISTS perpustakaan_uin
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE perpustakaan_uin;


==============================
-- CREATE TABLE semua tabel
==============================

==============
-- Tabel buku 
==============
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    kode_buku VARCHAR(20) UNIQUE NOT NULL,
    judul VARCHAR(200) NOT NULL,
    id_kategori INT NOT NULL,
    pengarang VARCHAR(100) NOT NULL,
    id_penerbit INT NOT NULL,
    tahun_terbit INT NOT NULL,
    isbn VARCHAR(20),
    harga DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_buku(id_kategori),
    FOREIGN KEY (id_penerbit) REFERENCES penerbit(id_penerbit)
);
   
=================
-- Kategori_buku
=================
-- Tabel untuk menyimpan kategori buku secara terpisah
CREATE TABLE kategori_buku (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- insert 5 >= kategori
INSERT INTO kategori_buku (nama_kategori, deskripsi) VALUES
('Programming', 'Buku-buku tentang pemrograman dan pengembangan software'),
('Database', 'Buku-buku tentang manajemen dan perancangan database'),
('Web Design', 'Buku-buku tentang desain dan tampilan website'),
('Networking', 'Buku-buku tentang jaringan komputer dan keamanan'),
('Mobile Development', 'Buku-buku tentang pengembangan aplikasi mobile');

============
-- penerbit
============
-- Tabel untuk menyimpan data penerbit buku
CREATE TABLE penerbit (
    id_penerbit INT AUTO_INCREMENT PRIMARY KEY,
    nama_penerbit VARCHAR(100) NOT NULL,
    alamat TEXT,
    telepon VARCHAR(15),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- insert >=5 penerbit
INSERT INTO penerbit (nama_penerbit, alamat, telepon, email) VALUES
('Informatika', 'Jl. Dr. Djunjunan No. 202, Bandung', '022-2005253', 'info@penerbitinformatika.com'),
('Graha Ilmu', 'Jl. Anggrek Raya No. 33, Yogyakarta', '0274-882262', 'cs@grahailmu.co.id'),
('Andi Publisher', 'Jl. Beo No. 38-40, Yogyakarta', '0274-561881', 'info@andipublisher.com'),
('Erlangga', 'Jl. H. Baping Raya No. 100, Jakarta', '021-4720971', 'cs@erlangga.co.id'),
('Elex Media', 'Jl. Palmerah Barat No. 29-37, Jakarta', '021-5306263', 'info@elexmedia.co.id');

=============================
-- INSERT data sample
=============================
-- insert >= 15 Buku
-- id_kategori: 1=Programming, 2=Database, 3=Web Design, 4=Networking, 5=Mobile Dev
-- id_penerbit: 1=Informatika, 2=Graha Ilmu, 3=Andi, 4=Erlangga, 5=Elex Media

INSERT INTO buku (kode_buku, judul, id_kategori, pengarang, id_penerbit, tahun_terbit, isbn, harga, stok, deskripsi) VALUES
('BK-001', 'Pemrograman PHP untuk Pemula', 1, 'Budi Raharjo', 1, 2023, '978-602-1234-56-1', 75000, 10, 'Panduan PHP dari dasar'),
('BK-002', 'Mastering MySQL Database', 2, 'Andi Nugroho', 2, 2022, '978-602-1234-56-2', 95000, 5, 'Panduan MySQL komprehensif'),
('BK-003', 'Laravel Framework Advanced', 1, 'Siti Aminah', 1, 2024, '978-602-1234-56-3', 125000, 8, 'Advanced Laravel'),
('BK-004', 'Web Design Principles', 3, 'Dedi Santoso', 3, 2023, '978-602-1234-56-4', 85000, 15, 'Prinsip desain web modern'),
('BK-005', 'Network Security Fundamentals', 4, 'Rina Wijaya', 4, 2023, '978-602-1234-56-5', 110000, 3, 'Dasar keamanan jaringan'),
('BK-006', 'PHP Web Services', 1, 'Budi Raharjo', 1, 2024, '978-602-1234-56-6', 90000, 12, 'RESTful API dengan PHP'),
('BK-007', 'PostgreSQL Advanced', 2, 'Ahmad Yani', 2, 2024, '978-602-1234-56-7', 115000, 7, 'Advanced PostgreSQL'),
('BK-008', 'JavaScript Modern', 1, 'Siti Aminah', 1, 2023, '978-602-1234-56-8', 80000, 4, 'JavaScript ES6+'),
('BK-009', 'React Native Development', 5, 'Ahmad Yani', 1, 2024, '978-602-1234-56-9', 135000, 10, 'Pengembangan app mobile React Native'),
('BK-010', 'CSS Flexbox & Grid', 3, 'Dewi Lestari', 5, 2023, '978-602-1234-57-0', 70000, 20, 'Teknik layout CSS modern'),
('BK-011', 'Cisco Networking Essentials', 4, 'Budi Oetomo', 4, 2022, '978-602-1234-57-1', 130000, 6, 'Jaringan Cisco untuk pemula'),
('BK-012', 'Python untuk Data Science', 1, 'Reza Kurniawan', 3, 2024, '978-602-1234-57-2', 145000, 9, 'Python dan analisis data'),
('BK-013', 'MongoDB NoSQL Database', 2, 'Yoga Pratama', 5, 2023, '978-602-1234-57-3', 88000, 11, 'Database NoSQL dengan MongoDB'),
('BK-014', 'Flutter Mobile App', 5, 'Nadia Permata', 1, 2024, '978-602-1234-57-4', 120000, 5, 'Pengembangan app dengan Flutter'),
('BK-015', 'Vue.js Framework', 3, 'Hendra Kusuma', 3, 2023, '978-602-1234-57-5', 95000, 8, 'Frontend development dengan Vue.js');

============================
-- Query JOIN
============================
-- Tampilkan buku dengan nama kategori dan nama penerbit
-- Menggabungkan 3 tabel: buku, kategori_buku, dan penerbit
SELECT
    b.kode_buku,
    b.judul,
    k.nama_kategori AS kategori,
    b.pengarang,
    p.nama_penerbit AS penerbit,
    b.tahun_terbit,
    b.harga,
    b.stok
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
ORDER BY b.judul ASC;

-- Jumlah buku per kategori
-- Menghitung berapa judul buku ada di setiap kategori
SELECT
    k.nama_kategori,
    COUNT(b.id_buku) AS jumlah_buku,
    SUM(b.stok) AS total_stok
FROM kategori_buku k
LEFT JOIN buku b ON k.id_kategori = b.id_kategori
GROUP BY k.id_kategori, k.nama_kategori
ORDER BY jumlah_buku DESC;

-- Jumlah buku per penerbit
-- Menghitung berapa buku yang diterbitkan oleh masing-masing penerbit
SELECT
    p.nama_penerbit,
    COUNT(b.id_buku) AS jumlah_buku
FROM penerbit p
LEFT JOIN buku b ON p.id_penerbit = b.id_penerbit
GROUP BY p.id_penerbit, p.nama_penerbit
ORDER BY jumlah_buku DESC;

-- Detail lengkap semua buku (kategori + penerbit)
-- menggabungkan semua informasi buku
SELECT
    b.kode_buku AS 'Kode',
    b.judul AS 'Judul Buku',
    k.nama_kategori AS 'Kategori',
    k.deskripsi AS 'Deskripsi Kategori',
    b.pengarang AS 'Pengarang',
    p.nama_penerbit AS 'Penerbit',
    p.telepon AS 'Telp Penerbit',
    b.tahun_terbit AS 'Tahun',
    CONCAT('Rp ', FORMAT(b.harga, 0)) AS 'Harga',
    b.stok AS 'Stok'
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
ORDER BY k.nama_kategori, b.judul;

======================================================
-- Bonus: tabel rak, soft delete dan stored procedure
======================================================

================
-- Tambah Tabel
================
-- Tabel rak buku dengan relasi ke buku
CREATE TABLE rak (
    id_rak INT AUTO_INCREMENT PRIMARY KEY,
    kode_rak VARCHAR(10) NOT NULL UNIQUE,
    lokasi VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL DEFAULT 50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tambah kolom id_rak ke tabel buku
ALTER TABLE buku ADD COLUMN id_rak INT NULL;
ALTER TABLE buku ADD FOREIGN KEY (id_rak) REFERENCES rak(id_rak);

-- Insert data rak
INSERT INTO rak (kode_rak, lokasi, kapasitas) VALUES
('RAK-A', 'Lantai 1 - Sisi Kiri', 60),
('RAK-B', 'Lantai 1 - Sisi Kanan', 60),
('RAK-C', 'Lantai 2 - Tengah', 80),
('RAK-D', 'Lantai 2 - Sudut', 40);

-- Assign buku ke rak
UPDATE buku SET id_rak = 1 WHERE id_kategori = 1;        -- Programmimg --> RAK-A
UPDATE buku SET id_rak = 2 WHERE id_kategori = 2;        -- Database --> RAK-B
UPDATE buku SET id_rak = 3 WHERE id_kategori = 3;        -- Web Design --> RAK-C
UPDATE buku SET id_rak = 4 WHERE id_kategori IN (4, 5);  -- Networking & Mobile --> RAK-D

===============
-- Soft Delate
===============
-- Tambah kolom is_deleted di setiap tabel
ALTER TABLE buku ADD COLUMN is_deleted BOOLEAN DEFAULT FALSE;
ALTER TABLE kategori_buku ADD COLUMN is_deleted BOOLEAN DEFAULT FALSE;
ALTER TABLE penerbit ADD COLUMN is_deleted BOOLEAN DEFAULT FALSE;
ALTER TABLE rak ADD COLUMN is_deleted BOOLEAN DEFAULT FALSE;

-- Contoh soft delete (hapus tanpa benar-benar hapus)
UPDATE buku SET is_deleted = TRUE WHERE kode_buku = 'BK-008';

-- Query aktif (tidak deleted)
SELECT * FROM buku WHERE is_deleted = FALSE;

====================
-- Stored Procedure
====================
-- Stored Procedure 1: Cari buku berdasarkan kategori

DELIMITER //
CREATE PROCEDURE CariPerKategori(IN nama_kat VARCHAR(50))
BEGIN
    SELECT b.kode_buku, b.judul, b.pengarang, b.harga, b.stok
    FROM buku b
    JOIN kategori_buku k ON b.id_kategori = k.id_kategori
    WHERE k.nama_kategori = nama_kat AND b.is_deleted = FALSE;
END //
DELIMITER ;

-- contoh penggunaanya:
CALL CariPerKategori('Programming');
CALL CariPerKategori('Web Design');
-- dst...

