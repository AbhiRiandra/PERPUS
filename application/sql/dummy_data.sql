USE db_perpus_kelurahan;
-- DATA CONTOH (opsional). Nama lokasi hanya contoh, silakan ganti.
INSERT INTO lokasi (nama_lokasi, jenis, alamat, penanggung_jawab, no_hp) VALUES
('Kelurahan Contoh 1', 'kelurahan', 'Jl. Contoh No. 1, Mataram', 'Bapak Lurah 1', '081200000001'),
('Kelurahan Contoh 2', 'kelurahan', 'Jl. Contoh No. 2, Mataram', 'Ibu Sekretaris 2', '081200000002'),
('Kelurahan Contoh 3', 'kelurahan', 'Jl. Contoh No. 3, Mataram', 'Bapak Kasi 3',     '081200000003');

INSERT INTO paket (kode, nama, deskripsi, status, id_lokasi) VALUES
('A', 'Paket A - Umum & Pengetahuan', 'Buku bacaan umum dan pengetahuan', 'dipinjam', 1),
('B', 'Paket B - Anak & Remaja',      'Buku cerita dan edukasi anak',     'tersedia', NULL),
('C', 'Paket C - Pertanian & UMKM',   'Buku keterampilan dan usaha',      'tersedia', NULL),
('D', 'Paket D - Agama & Budaya',     'Buku agama dan budaya lokal',      'tersedia', NULL);

INSERT INTO buku (id_paket, judul, penulis, penerbit, tahun, kategori) VALUES
(1, 'Contoh Judul Buku 1', 'Penulis 1', 'Penerbit 1', 2018, 'Umum'),
(1, 'Contoh Judul Buku 2', 'Penulis 2', 'Penerbit 2', 2019, 'Umum'),
(1, 'Contoh Judul Buku 3', 'Penulis 3', 'Penerbit 1', 2020, 'Sains'),
(2, 'Contoh Cerita Anak 1', 'Penulis 4', 'Penerbit 3', 2017, 'Anak'),
(2, 'Contoh Cerita Anak 2', 'Penulis 5', 'Penerbit 3', 2021, 'Anak');

INSERT INTO peminjaman (id_paket, id_lokasi, jenis, tgl_pinjam, tgl_jatuh_tempo, tgl_kembali, status, catatan, id_user) VALUES
(1, 2, 'pinjam', DATE_SUB(CURDATE(), INTERVAL 90 DAY), DATE_SUB(CURDATE(), INTERVAL 60 DAY), DATE_SUB(CURDATE(), INTERVAL 60 DAY), 'selesai', 'Dikembalikan tepat waktu', 1),
(1, 1, 'pinjam', DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 20 DAY), NULL, 'berjalan', 'Peminjaman pertama ke Kelurahan Contoh 1', 1);
