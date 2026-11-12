<?php
$is_admin = $this->session->userdata('role') === 'admin';
$dipinjam = $paket['status'] === 'dipinjam';
?>

<!-- NAVIGASI KEMBALI & AKSI ATAS -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <a href="<?= base_url('backend/paket') ?>" class="text-decoration-none text-muted small fw-medium d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Paket Buku</span>
    </a>

    <?php if ($is_admin): ?>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('backend/paket/edit/' . (int) $paket['id']) ?>" class="btn btn-sm btn-light border">
                <i class="bi bi-pencil me-1 text-muted"></i>Edit Paket
            </a>

            <?= form_open(
                'backend/paket/delete/' . (int) $paket['id'],
                [
                    'class' => 'd-inline',
                    'onsubmit' => "return confirm('Hapus paket ini beserta seluruh daftar bukunya?');"
                ]
            ) ?>
                <button type="submit" class="btn btn-sm btn-light border text-danger">
                    <i class="bi bi-trash3 me-1"></i>Hapus
                </button>
            <?= form_close() ?>
        </div>
    <?php endif; ?>
</div>

<!-- =====================================================
     RINGKASAN PAKET DAN STATUS SIRKULASI
===================================================== -->
<div class="clean-card mb-4">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center">
            <!-- Info Paket -->
            <div class="col-lg-6">
                <div class="d-flex align-items-start gap-3">
                    <span class="paket-code-badge" style="width:44px;height:44px;font-size:1.15rem;border-radius:10px;">
                        <?= html_escape($paket['kode']) ?>
                    </span>
                    <div>
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                            <h4 class="fw-bold text-dark mb-0"><?= html_escape($paket['nama']) ?></h4>
                            <span class="badge badge-soft-secondary">
                                <?= (int) $paket['jml_buku'] ?> judul buku
                            </span>
                        </div>
                        <p class="text-muted small mb-0">
                            <?= $paket['deskripsi'] ? nl2br(html_escape($paket['deskripsi'])) : 'Tidak ada keterangan tambahan.' ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Panel Status Sirkulasi -->
            <div class="col-lg-6">
                <?php if ($dipinjam && $aktif): ?>
                    <div class="p-3 rounded-3 border bg-light bg-opacity-50">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <div>
                                <div class="text-muted small">Lokasi Peminjaman Saat Ini</div>
                                <div class="fs-6 fw-bold text-dark">
                                    <i class="bi bi-geo-alt text-primary me-1"></i><?= html_escape($paket['nama_lokasi']) ?>
                                </div>
                                <?php if ($paket['penanggung_jawab']): ?>
                                    <div class="small text-muted">
                                        PJ: <?= html_escape($paket['penanggung_jawab']) ?>
                                        <?= $paket['no_hp_lokasi'] ? ' &bull; ' . html_escape($paket['no_hp_lokasi']) : '' ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?= badge_jatuh_tempo($aktif['tgl_jatuh_tempo']) ?>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 py-1 text-muted small mb-3">
                            <span>Pinjam: <strong><?= tgl_indo($aktif['tgl_pinjam']) ?></strong></span>
                            <span>&bull;</span>
                            <span>Jatuh Tempo: <strong><?= tgl_indo($aktif['tgl_jatuh_tempo']) ?></strong></span>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?= base_url('backend/peminjaman/oper/' . (int) $paket['id']) ?>" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="bi bi-arrow-left-right me-1"></i>Oper
                            </a>
                            <button type="button" class="btn btn-outline-primary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#modalPerpanjang">
                                <i class="bi bi-calendar-plus me-1"></i>Perpanjang
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#modalTarik">
                                <i class="bi bi-box-arrow-in-down-left me-1"></i>Tarik ke Perpus
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-3 rounded-3 border d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 bg-light bg-opacity-50">
                        <div>
                            <span class="badge badge-soft-success mb-1">Tersedia</span>
                            <div class="small text-muted">Paket tersimpan di perpustakaan dan siap dipinjamkan.</div>
                        </div>
                        <a href="<?= base_url('backend/peminjaman/pinjam/' . (int) $paket['id']) ?>" class="btn btn-primary btn-sm flex-shrink-0">
                            Pinjamkan Paket
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- =====================================================
     DAFTAR JUDUL BUKU DALAM PAKET
===================================================== -->
<div class="clean-card mb-4">
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="fw-bold text-dark mb-0">Daftar Koleksi Buku (<?= count($buku) ?>)</h6>
            <span class="text-muted small">Judul buku yang tergabung dalam paket <?= html_escape($paket['kode']) ?></span>
        </div>

        <?php if ($is_admin): ?>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Impor CSV
                </button>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#formTambahBuku">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Buku
                </button>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-body p-4">
        <?php if ($is_admin): ?>
            <!-- FORM COLLAPSE TAMBAH BUKU -->
            <div class="collapse mb-3" id="formTambahBuku">
                <div class="p-3 rounded-3 border bg-light bg-opacity-50">
                    <div class="fw-semibold text-dark small mb-2">Tambah Judul Buku Baru</div>
                    <?= form_open('backend/buku/simpan/' . (int) $paket['id'], ['class' => 'row g-2']) ?>
                        <div class="col-md-12">
                            <input name="judul" class="form-control form-control-sm" placeholder="Judul buku lengkap *" required maxlength="255">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <input name="penulis" class="form-control form-control-sm" placeholder="Penulis / Pengarang" maxlength="150">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <input name="penerbit" class="form-control form-control-sm" placeholder="Penerbit" maxlength="150">
                        </div>
                        <div class="col-6 col-md-2">
                            <input name="tahun" class="form-control form-control-sm" placeholder="Tahun" maxlength="4">
                        </div>
                        <div class="col-6 col-md-2">
                            <input name="kategori" class="form-control form-control-sm" placeholder="Kategori" maxlength="100">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                            <button type="button" class="btn btn-light border btn-sm" data-bs-toggle="collapse" data-bs-target="#formTambahBuku">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm px-3">Simpan</button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- PENCARIAN BUKU -->
        <div class="mb-3">
            <input type="search" id="cariBuku" class="form-control form-control-sm" placeholder="Cari judul buku, penulis, kategori...">
        </div>

        <!-- TABEL BUKU -->
        <div class="table-responsive border rounded-3">
            <table class="table table-clean table-hover align-middle mb-0" id="tabelBuku">
                <thead>
                    <tr>
                        <th style="width: 45px;" class="text-center">#</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th class="text-center" style="width: 75px;">Tahun</th>
                        <th>Kategori</th>
                        <?php if ($is_admin): ?>
                            <th class="text-end pe-3" style="width: 50px;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($buku)): ?>
                        <tr>
                            <td colspan="<?= $is_admin ? 7 : 6 ?>" class="text-center py-4 text-muted small">
                                Belum ada buku di paket ini. Tambahkan secara manual atau impor dari CSV.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($buku as $b): ?>
                            <tr>
                                <td class="text-center text-muted small"><?= $no++ ?></td>
                                <td class="fw-semibold text-dark"><?= html_escape($b['judul']) ?></td>
                                <td class="text-muted small"><?= $b['penulis'] ? html_escape((string) $b['penulis']) : '-' ?></td>
                                <td class="text-muted small"><?= $b['penerbit'] ? html_escape((string) $b['penerbit']) : '-' ?></td>
                                <td class="text-center small"><?= $b['tahun'] ? html_escape((string) $b['tahun']) : '-' ?></td>
                                <td>
                                    <?php if ($b['kategori']): ?>
                                        <span class="badge badge-soft-secondary"><?= html_escape((string) $b['kategori']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($is_admin): ?>
                                    <td class="text-end pe-3">
                                        <?= form_open(
                                            'backend/buku/delete/' . (int) $b['id'],
                                            [
                                                'class' => 'd-inline',
                                                'onsubmit' => "return confirm('Hapus buku ini?');"
                                            ]
                                        ) ?>
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        <?= form_close() ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- =====================================================
     RIWAYAT PERJALANAN PAKET
===================================================== -->
<div class="clean-card">
    <div class="card-header bg-transparent border-bottom px-4 py-3">
        <h6 class="fw-bold text-dark mb-0">Riwayat Sirkulasi Paket</h6>
        <span class="text-muted small">Catatan perjalanan distribusi paket ini</span>
    </div>

    <div class="card-body p-0">
        <?php if (empty($riwayat)): ?>
            <div class="text-center py-4 text-muted small">Paket ini belum memiliki riwayat peminjaman.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-clean table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Lokasi Tujuan</th>
                            <th>Tgl Masuk</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Keluar</th>
                            <th>Status</th>
                            <th class="pe-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat as $r): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark"><?= html_escape($r['nama_lokasi']) ?></span>
                                    <span class="badge badge-soft-secondary ms-1"><?= ucfirst($r['jenis']) ?></span>
                                </td>
                                <td class="small"><?= tgl_indo($r['tgl_pinjam']) ?></td>
                                <td class="small"><?= tgl_indo($r['tgl_jatuh_tempo']) ?></td>
                                <td class="small"><?= tgl_indo($r['tgl_kembali']) ?></td>
                                <td>
                                    <?php if ($r['status'] === 'berjalan'): ?>
                                        <?= badge_jatuh_tempo($r['tgl_jatuh_tempo']) ?>
                                    <?php else: ?>
                                        <span class="badge badge-soft-secondary">Selesai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted pe-4">
                                    <?= $r['catatan'] ? html_escape((string) $r['catatan']) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =====================================================
     MODAL TARIK PAKET & PERPANJANG
===================================================== -->
<?php if ($dipinjam && $aktif): ?>
<div class="modal fade" id="modalTarik" tabindex="-1" aria-labelledby="modalTarikLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <?= form_open('backend/peminjaman/proses_tarik/' . (int) $paket['id']) ?>
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold" id="modalTarikLabel">Tarik Paket <?= html_escape($paket['kode']) ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 bg-light border small text-muted mb-3">
                        Paket akan dikembalikan dari <strong><?= html_escape($paket['nama_lokasi']) ?></strong> ke perpustakaan.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small text-muted">Tanggal Ditarik <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div>
                        <label class="form-label fw-medium small text-muted">Catatan Penarikan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="catatan" rows="2" class="form-control form-control-sm" placeholder="Kondisi fisik buku, kelengkapan, dll..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Konfirmasi Tarik</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPerpanjang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <?= form_open('backend/peminjaman/proses_perpanjang/' . (int) $paket['id']) ?>
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold">Perpanjang Masa Pinjam</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="small text-muted mb-3">
                        Jatuh tempo saat ini: <strong><?= tgl_indo($aktif['tgl_jatuh_tempo']) ?></strong>
                    </div>

                    <label class="form-label fw-medium small text-muted">Tanggal Jatuh Tempo Baru <span class="text-danger">*</span></label>
                    <input type="date"
                           name="tgl_jatuh_tempo"
                           id="inputJatuhTempoBaru"
                           class="form-control form-control-sm"
                           min="<?= date('Y-m-d', strtotime($aktif['tgl_jatuh_tempo'] . ' +1 day')) ?>"
                           value="<?= date('Y-m-d', strtotime($aktif['tgl_jatuh_tempo'] . ' +30 days')) ?>"
                           required>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Perpanjangan</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- =====================================================
     MODAL IMPOR CSV
===================================================== -->
<?php if ($is_admin): ?>
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <?= form_open_multipart('backend/buku/import/' . (int) $paket['id']) ?>
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold" id="modalImportLabel">Impor Daftar Buku dari CSV</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="p-3 rounded-3 bg-light border small mb-3">
                        <div class="fw-semibold text-dark mb-1">Format Kolom CSV:</div>
                        <code class="d-block mb-2">judul, penulis, penerbit, tahun, kategori</code>
                        <div class="text-muted mb-2">Kolom <strong>judul</strong> wajib terisi.</div>
                        <a href="<?= base_url('backend/buku/template') ?>" class="btn btn-sm btn-outline-primary py-1">
                            <i class="bi bi-download me-1"></i>Unduh Template CSV
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small text-muted">Pilih File CSV <span class="text-danger">*</span></label>
                        <input type="file" name="file_csv" accept=".csv" class="form-control form-control-sm" required>
                    </div>

                    <label class="form-label fw-medium small text-muted d-block">Mode Impor Data</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="mode" value="tambah" id="m1" checked>
                        <label class="form-check-label small" for="m1">
                            Tambahkan ke koleksi yang sudah ada
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mode" value="ganti" id="m2">
                        <label class="form-check-label small text-danger" for="m2">
                            Ganti seluruh koleksi (hapus buku lama)
                        </label>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Proses Impor</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var cariBuku = document.getElementById('cariBuku');
    if (cariBuku) {
        cariBuku.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabelBuku tbody tr').forEach(function (tr) {
                if (tr.querySelector('.empty-state') || tr.cells.length < 3) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
