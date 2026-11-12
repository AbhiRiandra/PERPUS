# Sistem Peminjaman Paket Buku Kolektif ke Kelurahan

CodeIgniter 3 + MySQL/MariaDB + Bootstrap 5 (dasar: template `bengkel321`).

## Cara pasang (XAMPP / Laragon)
1. Salin folder `perpus_kelurahan` ke `htdocs`.
2. Jalankan Apache + MySQL, buka phpMyAdmin, **Import** `application/sql/db_perpus_kelurahan.sql`.
   (Opsional: import `application/sql/dummy_data.sql` untuk data contoh.)
3. Cek `application/config/database.php` (username/password MySQL) dan
   `application/config/config.php` -> `base_url` (default `http://localhost/perpus_kelurahan/`).
4. Buka `http://localhost/perpus_kelurahan/`.

Akun awal (GANTI setelah dipakai sungguhan):
- admin / admin123   -> semua fitur
- petugas / petugas123 -> melihat data, meminjamkan, mengoper, menarik, memperpanjang

## Konsep
- **Paket** (A, B, C, ...) = satu kesatuan berisi 200+ judul (tabel `buku`).
- **Lokasi** = kelurahan / tempat tujuan peminjaman.
- Setiap perpindahan paket memakai SATU transaksi database (`Peminjaman_model`):
  - **Pinjam**   : perpustakaan -> lokasi
  - **Oper**     : lokasi A -> lokasi B (riwayat A ditutup, riwayat B dibuka otomatis)
  - **Tarik**    : lokasi -> perpustakaan (paket kembali "tersedia")
  - **Perpanjang**: ubah jatuh tempo
- Posisi terkini paket (`paket.id_lokasi`, `paket.status`) dan riwayat (`peminjaman`) selalu sinkron.
- "Terlambat" dihitung otomatis (jatuh tempo < hari ini & belum ditarik).
- Isi paket bisa diimpor dari CSV (kolom: judul, penulis, penerbit, tahun, kategori; koma atau titik-koma).

## Struktur penting
application/core/MY_Controller.php        helper login/role/render layout
application/controllers/backend/          Dashboard, Paket, Buku, Lokasi, Peminjaman
application/models/                       Paket, Buku, Lokasi, Peminjaman, Login
application/views/backend/                dashboard, paket, lokasi, peminjaman
application/sql/                          skema + data contoh
