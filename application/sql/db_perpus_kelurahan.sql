-- =====================================================================
--  Sistem Peminjaman Paket Buku Kolektif ke Kelurahan
--  Import file ini lewat phpMyAdmin (tab Import) atau: mysql -u root < db_perpus_kelurahan.sql
-- =====================================================================
CREATE DATABASE IF NOT EXISTS `db_perpus_kelurahan`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_perpus_kelurahan`;

-- ---------- User (admin & petugas) ----------
CREATE TABLE IF NOT EXISTS `user` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama`     VARCHAR(100) NOT NULL,
  `username` VARCHAR(50)  NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role`     ENUM('admin','petugas') NOT NULL DEFAULT 'petugas',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Akun awal -> admin / admin123  dan  petugas / petugas123  (GANTI setelah login pertama)
INSERT INTO `user` (`nama`, `username`, `password`, `role`) VALUES
('Administrator',  'admin',   '$2y$10$dA3ZAoPuJZBNpM7xPpHllO254/izozrJUNI815BTq4XANArWclHOm', 'admin'),
('Petugas Perpus', 'petugas', '$2y$10$7J76uMcZ/PIa3xTGURk72uyClfhaggSKh1UecEd.pWKNeQaWW.0Zq',   'petugas')
ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `password` = VALUES(`password`), `role` = VALUES(`role`);

-- ---------- Lokasi tujuan peminjaman (kelurahan / tempat lain) ----------
CREATE TABLE IF NOT EXISTS `lokasi` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lokasi`      VARCHAR(150) NOT NULL,
  `jenis`            ENUM('kelurahan','desa','sekolah','taman_baca','lainnya') NOT NULL DEFAULT 'kelurahan',
  `alamat`           TEXT,
  `penanggung_jawab` VARCHAR(100) DEFAULT NULL,
  `no_hp`            VARCHAR(20)  DEFAULT NULL,
  `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_nama_lokasi` (`nama_lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Paket buku (A, B, C, D, ...) ----------
-- id_lokasi = posisi paket SAAT INI (NULL = ada di perpustakaan). Diupdate otomatis oleh sistem.
CREATE TABLE IF NOT EXISTS `paket` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode`       VARCHAR(10)  NOT NULL,
  `nama`       VARCHAR(150) NOT NULL,
  `deskripsi`  TEXT,
  `status`     ENUM('tersedia','dipinjam') NOT NULL DEFAULT 'tersedia',
  `id_lokasi`  INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_kode` (`kode`),
  KEY `fk_paket_lokasi` (`id_lokasi`),
  CONSTRAINT `fk_paket_lokasi` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Isi paket: daftar judul buku (200+ per paket) ----------
CREATE TABLE IF NOT EXISTS `buku` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_paket`  INT UNSIGNED NOT NULL,
  `judul`     VARCHAR(255) NOT NULL,
  `penulis`   VARCHAR(150) DEFAULT NULL,
  `penerbit`  VARCHAR(150) DEFAULT NULL,
  `tahun`     YEAR DEFAULT NULL,
  `kategori`  VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_buku_paket` (`id_paket`),
  KEY `idx_buku_judul` (`judul`),
  CONSTRAINT `fk_buku_paket` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------- Riwayat peminjaman / perpindahan paket ----------
-- Satu baris = satu "masa tinggal" paket di satu lokasi.
-- status 'berjalan' = paket masih di lokasi itu; 'selesai' = sudah ditarik/dioper.
-- Terlambat tidak disimpan: dihitung dari tgl_jatuh_tempo < hari ini & status berjalan.
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_paket`        INT UNSIGNED NOT NULL,
  `id_lokasi`       INT UNSIGNED NOT NULL,
  `jenis`           ENUM('pinjam','oper') NOT NULL DEFAULT 'pinjam',
  `tgl_pinjam`      DATE NOT NULL,
  `tgl_jatuh_tempo` DATE NOT NULL,
  `tgl_kembali`     DATE DEFAULT NULL,
  `status`          ENUM('berjalan','selesai') NOT NULL DEFAULT 'berjalan',
  `catatan`         TEXT,
  `id_user`         INT UNSIGNED DEFAULT NULL,
  `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pinjam_paket` (`id_paket`, `status`),
  KEY `fk_pinjam_lokasi` (`id_lokasi`),
  KEY `fk_pinjam_user` (`id_user`),
  CONSTRAINT `fk_pinjam_paket`  FOREIGN KEY (`id_paket`)  REFERENCES `paket` (`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_pinjam_lokasi` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi` (`id`),
  CONSTRAINT `fk_pinjam_user`   FOREIGN KEY (`id_user`)   REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
