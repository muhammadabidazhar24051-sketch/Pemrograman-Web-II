-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Bulan Mei 2026 pada 09.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan_uin`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `kode_anggota` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `tanggal_daftar` date NOT NULL,
  `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `kode_anggota`, `nama`, `email`, `telepon`, `alamat`, `tanggal_lahir`, `jenis_kelamin`, `pekerjaan`, `tanggal_daftar`, `status`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'ANG001', 'Budi Santoso', 'budi@email.com', '081234567890', 'Jl. Merdeka No.1, Pekalongan', '1995-05-15', 'Laki-laki', 'Mahasiswa', '2026-05-03', 'Nonaktif', NULL, '2026-05-03 02:48:36', '2026-05-03 06:03:23'),
(2, 'ANG002', 'Siti Rahayu', 'siti@email.com', '082345678901', 'Jl. Sudirman No.5, Pekalongan', '2000-08-22', 'Perempuan', 'Pelajar', '2026-05-03', 'Aktif', NULL, '2026-05-03 02:48:36', '2026-05-03 02:48:36'),
(3, 'ANG003', 'Ahmad Fauzi', 'ahmad@email.com', '083456789012', 'Jl. Diponegoro No.10, Pekalongan', '1988-03-10', 'Laki-laki', 'Guru', '2026-05-03', 'Nonaktif', NULL, '2026-05-03 02:48:36', '2026-05-03 02:48:36'),
(4, 'ANG2026004', 'Muhammad Abid Azhar', 'har@gmail.com', '083327341926', 'Dk. Mbogo kec. Mbojong', '2006-02-16', 'Laki-laki', 'Mahasiswa', '2026-05-03', 'Aktif', 'foto_69f6d99b85878.jpg', '2026-05-03 04:20:27', '2026-05-03 05:14:03'),
(5, 'ANG2026005', 'Steven', 'steven@gmail.com', '084323451562', 'Ds. Kalipancur Kec. Mbojong', '2005-01-12', 'Laki-laki', 'Mahasiswa', '2026-05-03', 'Aktif', NULL, '2026-05-03 05:10:38', '2026-05-03 05:10:38'),
(6, 'ANG2026006', 'Liaka', 'liaka@gmail.com', '081526275588', 'Pekalongan', '2006-03-17', 'Perempuan', 'Mahasiswa', '2026-05-03', 'Aktif', NULL, '2026-05-03 06:03:08', '2026-05-03 06:03:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `kode_buku` varchar(20) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `id_penerbit` int(11) NOT NULL,
  `tahun_terbit` int(11) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_rak` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `kode_buku`, `judul`, `id_kategori`, `pengarang`, `id_penerbit`, `tahun_terbit`, `isbn`, `harga`, `stok`, `deskripsi`, `created_at`, `updated_at`, `id_rak`, `is_deleted`) VALUES
(1, 'BK-001', 'Pemrograman PHP untuk Pemula', 1, 'Budi Raharjo', 1, 2023, '978-602-1234-56-1', 75000.00, 10, 'Panduan PHP dari dasar', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 1, 0),
(2, 'BK-002', 'Mastering MySQL Database', 2, 'Andi Nugroho', 2, 2022, '978-602-1234-56-2', 95000.00, 5, 'Panduan MySQL komprehensif', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 2, 0),
(3, 'BK-003', 'Laravel Framework Advanced', 1, 'Siti Aminah', 1, 2024, '978-602-1234-56-3', 125000.00, 8, 'Advanced Laravel', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 1, 0),
(4, 'BK-004', 'Web Design Principles', 3, 'Dedi Santoso', 3, 2023, '978-602-1234-56-4', 85000.00, 15, 'Prinsip desain web modern', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 3, 0),
(5, 'BK-005', 'Network Security Fundamentals', 4, 'Rina Wijaya', 4, 2023, '978-602-1234-56-5', 110000.00, 3, 'Dasar keamanan jaringan', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 4, 0),
(6, 'BK-006', 'PHP Web Services', 1, 'Budi Raharjo', 1, 2024, '978-602-1234-56-6', 90000.00, 12, 'RESTful API dengan PHP', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 1, 0),
(7, 'BK-007', 'PostgreSQL Advanced', 2, 'Ahmad Yani', 2, 2024, '978-602-1234-56-7', 115000.00, 7, 'Advanced PostgreSQL', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 2, 0),
(8, 'BK-008', 'JavaScript Modern', 1, 'Siti Aminah', 1, 2023, '978-602-1234-56-8', 80000.00, 4, 'JavaScript ES6+', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 1, 0),
(9, 'BK-009', 'React Native Development', 5, 'Ahmad Yani', 1, 2024, '978-602-1234-56-9', 135000.00, 10, 'Pengembangan app mobile React Native', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 4, 0),
(10, 'BK-010', 'CSS Flexbox & Grid', 3, 'Dewi Lestari', 5, 2023, '978-602-1234-57-0', 70000.00, 20, 'Teknik layout CSS modern', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 3, 0),
(11, 'BK-011', 'Cisco Networking Essentials', 4, 'Budi Oetomo', 4, 2022, '978-602-1234-57-1', 130000.00, 6, 'Jaringan Cisco untuk pemula', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 4, 0),
(12, 'BK-012', 'Python untuk Data Science', 1, 'Reza Kurniawan', 3, 2024, '978-602-1234-57-2', 145000.00, 9, 'Python dan analisis data', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 1, 0),
(13, 'BK-013', 'MongoDB NoSQL Database', 2, 'Yoga Pratama', 5, 2023, '978-602-1234-57-3', 88000.00, 11, 'Database NoSQL dengan MongoDB', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 2, 0),
(14, 'BK-014', 'Flutter Mobile App', 5, 'Nadia Permata', 1, 2024, '978-602-1234-57-4', 120000.00, 5, 'Pengembangan app dengan Flutter', '2026-04-24 08:18:32', '2026-04-24 10:00:02', 4, 0),
(15, 'BK-015', 'Vue.js Framework', 3, 'Hendra Kusuma', 3, 2023, '978-602-1234-57-5', 95000.00, 8, 'Frontend development dengan Vue.js', '2026-04-24 08:18:32', '2026-04-24 10:19:01', 3, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori_buku`
--

INSERT INTO `kategori_buku` (`id_kategori`, `nama_kategori`, `deskripsi`, `created_at`, `is_deleted`) VALUES
(1, 'Programming', 'Buku-buku tentang pemrograman dan pengembangan software', '2026-04-24 08:06:36', 0),
(2, 'Database', 'Buku-buku tentang manajemen dan perancangan database', '2026-04-24 08:06:36', 0),
(3, 'Web Design', 'Buku-buku tentang desain dan tampilan website', '2026-04-24 08:06:36', 0),
(4, 'Networking', 'Buku-buku tentang jaringan komputer dan keamanan', '2026-04-24 08:06:36', 0),
(5, 'Mobile Development', 'Buku-buku tentang pengembangan aplikasi mobile', '2026-04-24 08:06:36', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbit`
--

CREATE TABLE `penerbit` (
  `id_penerbit` int(11) NOT NULL,
  `nama_penerbit` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penerbit`
--

INSERT INTO `penerbit` (`id_penerbit`, `nama_penerbit`, `alamat`, `telepon`, `email`, `created_at`, `is_deleted`) VALUES
(1, 'Informatika', 'Jl. Dr. Djunjunan No. 202, Bandung', '022-2005253', 'info@penerbitinformatika.com', '2026-04-24 08:09:32', 0),
(2, 'Graha Ilmu', 'Jl. Anggrek Raya No. 33, Yogyakarta', '0274-882262', 'cs@grahailmu.co.id', '2026-04-24 08:09:32', 0),
(3, 'Andi Publisher', 'Jl. Beo No. 38-40, Yogyakarta', '0274-561881', 'info@andipublisher.com', '2026-04-24 08:09:32', 0),
(4, 'Erlangga', 'Jl. H. Baping Raya No. 100, Jakarta', '021-4720971', 'cs@erlangga.co.id', '2026-04-24 08:09:32', 0),
(5, 'Elex Media', 'Jl. Palmerah Barat No. 29-37, Jakarta', '021-5306263', 'info@elexmedia.co.id', '2026-04-24 08:09:32', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rak`
--

CREATE TABLE `rak` (
  `id_rak` int(11) NOT NULL,
  `kode_rak` varchar(10) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rak`
--

INSERT INTO `rak` (`id_rak`, `kode_rak`, `lokasi`, `kapasitas`, `created_at`, `is_deleted`) VALUES
(1, 'RAK-A', 'Lantai 1 - Sisi Kiri', 60, '2026-04-24 09:59:26', 0),
(2, 'RAK-B', 'Lantai 1 - Sisi Kanan', 60, '2026-04-24 09:59:26', 0),
(3, 'RAK-C', 'Lantai 2 - Tengah', 80, '2026-04-24 09:59:26', 0),
(4, 'RAK-D', 'Lantai 2 - Sudut', 40, '2026-04-24 09:59:26', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `kode_anggota` (`kode_anggota`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_penerbit` (`id_penerbit`),
  ADD KEY `id_rak` (`id_rak`);

--
-- Indeks untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indeks untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  ADD PRIMARY KEY (`id_penerbit`);

--
-- Indeks untuk tabel `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`id_rak`),
  ADD UNIQUE KEY `kode_rak` (`kode_rak`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  MODIFY `id_penerbit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `rak`
--
ALTER TABLE `rak`
  MODIFY `id_rak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_buku` (`id_kategori`),
  ADD CONSTRAINT `buku_ibfk_2` FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`),
  ADD CONSTRAINT `buku_ibfk_3` FOREIGN KEY (`id_rak`) REFERENCES `rak` (`id_rak`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
