-- Jalankan lewat HeidiSQL / phpMyAdmin, di database `inlislite_v3`
-- (database yang sama dengan InlisLite, tabel baru saja, tidak mengganggu tabel InlisLite yang sudah ada)

CREATE TABLE IF NOT EXISTS `inventaris_buku` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tanggal` DATE NOT NULL,
  `no_inventaris` VARCHAR(20) NOT NULL UNIQUE,
  `isbn` VARCHAR(50) DEFAULT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `pengarang` VARCHAR(255) DEFAULT NULL,
  `penerbit` VARCHAR(255) DEFAULT NULL,
  `kota_terbit` VARCHAR(100) DEFAULT NULL,
  `tahun_terbit` VARCHAR(4) DEFAULT NULL,
  `deskripsi_fisik` VARCHAR(255) DEFAULT NULL,
  `no_klasifikasi` VARCHAR(50) DEFAULT NULL,
  `asal_sumber` VARCHAR(50) DEFAULT NULL,
  `jumlah` INT NOT NULL DEFAULT 1,
  `keterangan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `peminjaman_kolektif` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tanggal_pinjam` DATE NOT NULL,
  `tingkat_pendidikan` VARCHAR(20) NOT NULL,
  `nama_sekolah` VARCHAR(255) NOT NULL,
  `penanggung_jawab` VARCHAR(255) DEFAULT NULL,
  `no_inventaris` VARCHAR(20) DEFAULT NULL,
  `judul_buku` VARCHAR(255) DEFAULT NULL,
  `jumlah` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
