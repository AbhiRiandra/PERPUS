-- =====================================================================
--  Sistem Peminjaman Paket Buku Kolektif ke Kelurahan
--  Versi PostgreSQL / Supabase
--  Jalankan file ini langsung di SQL Editor Supabase
-- =====================================================================

-- 1. Tabel User (admin & petugas)
-- Catatan: "user" adalah reserved keyword di PostgreSQL sehingga menggunakan tanda kutip dua.
CREATE TABLE IF NOT EXISTS "user" (
  id       SERIAL PRIMARY KEY,
  nama     VARCHAR(100) NOT NULL,
  username VARCHAR(50)  NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role     VARCHAR(20)  NOT NULL DEFAULT 'petugas' CHECK (role IN ('admin', 'petugas'))
);

-- Akun awal -> admin / admin123 dan petugas / petugas123
INSERT INTO "user" (nama, username, password, role) VALUES
('Administrator',  'admin',   '$2y$10$dA3ZAoPuJZBNpM7xPpHllO254/izozrJUNI815BTq4XANArWclHOm', 'admin'),
('Petugas Perpus', 'petugas', '$2y$10$7J76uMcZ/PIa3xTGURk72uyClfhaggSKh1UecEd.pWKNeQaWW.0Zq',   'petugas')
ON CONFLICT (username) DO UPDATE 
SET nama = EXCLUDED.nama, 
    password = EXCLUDED.password, 
    role = EXCLUDED.role;

-- 2. Lokasi tujuan peminjaman (kelurahan / tempat lain)
CREATE TABLE IF NOT EXISTS lokasi (
  id               SERIAL PRIMARY KEY,
  nama_lokasi      VARCHAR(150) NOT NULL UNIQUE,
  jenis            VARCHAR(30)  NOT NULL DEFAULT 'kelurahan' CHECK (jenis IN ('kelurahan','desa','sekolah','taman_baca','lainnya')),
  alamat           TEXT,
  penanggung_jawab VARCHAR(100) DEFAULT NULL,
  no_hp            VARCHAR(20)  DEFAULT NULL,
  created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 3. Paket buku (A, B, C, D, ...)
CREATE TABLE IF NOT EXISTS paket (
  id         SERIAL PRIMARY KEY,
  kode       VARCHAR(10)  NOT NULL UNIQUE,
  nama       VARCHAR(150) NOT NULL,
  deskripsi  TEXT,
  status     VARCHAR(20)  NOT NULL DEFAULT 'tersedia' CHECK (status IN ('tersedia','dipinjam')),
  id_lokasi  INT DEFAULT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_paket_lokasi FOREIGN KEY (id_lokasi) REFERENCES lokasi (id)
    ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_paket_lokasi ON paket(id_lokasi);

-- 4. Isi paket: daftar judul buku
CREATE TABLE IF NOT EXISTS buku (
  id        SERIAL PRIMARY KEY,
  id_paket  INT NOT NULL,
  judul     VARCHAR(255) NOT NULL,
  penulis   VARCHAR(150) DEFAULT NULL,
  penerbit  VARCHAR(150) DEFAULT NULL,
  tahun     SMALLINT DEFAULT NULL,
  kategori  VARCHAR(100) DEFAULT NULL,
  CONSTRAINT fk_buku_paket FOREIGN KEY (id_paket) REFERENCES paket (id)
    ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_buku_paket ON buku(id_paket);
CREATE INDEX IF NOT EXISTS idx_buku_judul ON buku(judul);

-- 5. Riwayat peminjaman / perpindahan paket
CREATE TABLE IF NOT EXISTS peminjaman (
  id              SERIAL PRIMARY KEY,
  id_paket        INT NOT NULL,
  id_lokasi       INT NOT NULL,
  jenis           VARCHAR(20) NOT NULL DEFAULT 'pinjam' CHECK (jenis IN ('pinjam','oper')),
  tgl_pinjam      DATE NOT NULL,
  tgl_jatuh_tempo DATE NOT NULL,
  tgl_kembali     DATE DEFAULT NULL,
  status          VARCHAR(20) NOT NULL DEFAULT 'berjalan' CHECK (status IN ('berjalan','selesai')),
  catatan         TEXT,
  id_user         INT DEFAULT NULL,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pinjam_paket  FOREIGN KEY (id_paket)  REFERENCES paket (id)  ON DELETE CASCADE,
  CONSTRAINT fk_pinjam_lokasi FOREIGN KEY (id_lokasi) REFERENCES lokasi (id),
  CONSTRAINT fk_pinjam_user   FOREIGN KEY (id_user)   REFERENCES "user" (id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_pinjam_paket ON peminjaman(id_paket, status);
CREATE INDEX IF NOT EXISTS idx_pinjam_lokasi ON peminjaman(id_lokasi);
CREATE INDEX IF NOT EXISTS idx_pinjam_user   ON peminjaman(id_user);

