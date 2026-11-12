<?php
$is_admin = $this->session->userdata('role') === 'admin';
$dipinjam = $paket['status'] === 'dipinjam';
?>

<!-- BREADCRUMB & NAVIGASI ATAS -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <a href="<?= base_url('backend/paket') ?>" class="text-decoration-none text-secondary small fw-semibold d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Daftar Paket Buku</span>
    </a>

    <?php if ($is_admin): ?>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('backend/paket/edit/' . (int) $paket['id']) ?>" class="btn btn-sm btn-light border fw-semibold">
                <i class="bi bi-pencil-square me-1 text-warning"></i>Edit Paket
            </a>

            <?= form_open(
                'backend/paket/delete/' . (int) $paket['id'],
                [
                    'class' => 'd-inline',
                    'onsubmit' => "return confirm('Hapus paket ini beserta seluruh daftar bukunya?');"
                ]
            ) ?>
                <button type="submit" class="btn btn-sm btn-light border text-danger fw-semibold">
                    <i class="bi bi-trash me-1"></i>Hapus Paket
                </button>
            <?= form_close() ?>
        </div>
    <?php endif; ?>
</div>

<!-- =====================================================
     RINGKASAN PAKET DAN PANEL KENDALI SIRKULASI
===================================================== -->
<div class="modern-card mb-4 overflow-hidden">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center">
            <!-- Info Paket -->
            <div class="col-lg-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="paket-code-badge" style="min-width:54px;height:54px;font-size:1.25rem;border-radius:14px;">
                        <?= html_escape($paket['kode']) ?>
                    </div>
                    <div>
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                            <h4 class="fw-bold text-dark mb-0"><?= html_escape($paket['nama']) ?></h4>
                            <span class="badge badge-soft-secondary">
                                <i class="bi bi-book me-1"></i><?= (int) $paket['jml_buku'] ?> judul buku
                            </span>
                        </div>
                        <p class="text-muted small mb-0">
                            <?= $paket['deskripsi'] ? nl2br(html_escape($paket['deskripsi'])) : 'Tidak ada keterangan deskripsi tambahan.' ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Panel Status & Aksi Sirkulasi -->
            <div class="col-lg-6">
                <?php if ($dipinjam && $aktif): ?>
                    <div class="p-3 rounded-4 border" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <div>
                                <div class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.05em;">Lokasi Peminjaman Saat Ini</div>
                                <div class="fs-5 fw-bold text-dark mt-1">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= html_escape($paket['nama_lokasi']) ?>
                                </div>
                                <?php if ($paket['penanggung_jawab']): ?>
                                    <div class="small text-secondary mt-1">
                                        <i class="bi bi-person-badge me-1"></i>PJ: <strong><?= html_escape($paket['penanggung_jawab']) ?></strong>
                                        <?= $paket['no_hp_lokasi'] ? ' &bull; <i class="bi bi-telephone me-1"></i>' . html_escape($paket['no_hp_lokasi']) : '' ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?= badge_jatuh_tempo($aktif['tgl_jatuh_tempo']) ?>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 py-2 px-3 rounded-3 bg-white border small mb-3">
                            <div>
                                <span class="text-muted">Mulai Pinjam:</span>
                                <strong class="text-dark ms-1"><?= tgl_indo($aktif['tgl_pinjam']) ?></strong>
                            </div>
                            <div class="text-muted">&rarr;</div>
                            <div>
                                <span class="text-muted">Jatuh Tempo:</span>
                                <strong class="text-dark ms-1"><?= tgl_indo($aktif['tgl_jatuh_tempo']) ?></strong>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?= base_url('backend/peminjaman/oper/' . (int) $paket['id']) ?>" class="btn btn-warning btn-sm fw-bold flex-fill">
                                <i class="bi bi-arrow-left-right me-1"></i>Oper ke Lokasi Lain
                            </a>
                            <button type="button" class="btn btn-outline-primary btn-sm fw-semibold flex-fill" data-bs-toggle="modal" data-bs-target="#modalPerpanjang">
                                <i class="bi bi-calendar-plus me-1"></i>Perpanjang
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold flex-fill" data-bs-toggle="modal" data-bs-target="#modalTarik">
                                <i class="bi bi-box-arrow-in-down-left me-1"></i>Tarik ke Perpus
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-3 rounded-4 border d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3" style="background: #ecfdf5; border-color: #a7f3d0 !important;">
                        <div>
                            <div class="text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em; color: #047857;">Status Ketersediaan</div>
                            <div class="fs-5 fw-bold mt-1" style="color: #065f46;">
                                <i class="bi bi-building-check me-1"></i>Tersedia di Perpustakaan
                            </div>
                            <div class="small" style="color: #047857;">Paket siap dipinjamkan ke kelurahan atau titik layanan baca.</div>
                        </div>
                        <a href="<?= base_url('backend/peminjaman/pinjam/' . (int) $paket['id']) ?>" class="btn btn-success fw-bold px-3 shadow-sm flex-shrink-0">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Pinjamkan Paket
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
<div class="modern-card mb-4">
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-journal-text text-primary me-2"></i>Daftar Koleksi Buku
                <span class="badge badge-soft-primary ms-1"><?= count($buku) ?> Judul</span>
            </h5>
            <p class="text-muted small mb-0">Daftar seluruh judul buku yang tergabung di dalam Paket <?= html_escape($paket['kode']) ?>.</p>
        </div>

        <?php if ($is_admin): ?>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-success fw-semibold" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Impor CSV
                </button>
                <button type="button" class="btn btn-sm btn-primary fw-semibold" data-bs-toggle="collapse" data-bs-target="#formTambahBuku">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Buku
                </button>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-body p-4">
        <?php if ($is_admin): ?>
            <!-- FORM COLLAPSE TAMBAH BUKU SATUAN -->
            <div class="collapse mb-4" id="formTambahBuku">
                <div class="p-3 rounded-4 border bg-light">
                    <div class="fw-bold text-dark small mb-2">
                        <i class="bi bi-plus-circle text-primary me-1"></i>Tambah Judul Buku Baru ke Paket <?= html_escape($paket['kode']) ?>
                    </div>
                    <?= form_open('backend/buku/simpan/' . (int) $paket['id'], ['class' => 'row g-2']) ?>
                        <div class="col-md-12">
                            <input name="judul" class="form-control form-control-sm bg-white" placeholder="Judul buku lengkap *" required maxlength="255">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <input name="penulis" class="form-control form-control-sm bg-white" placeholder="Nama Penulis / Pengarang" maxlength="150">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <input name="penerbit" class="form-control form-control-sm bg-white" placeholder="Nama Penerbit" maxlength="150">
                        </div>
                        <div class="col-6 col-md-2">
                            <input name="tahun" class="form-control form-control-sm bg-white" placeholder="Tahun (4 digit)" maxlength="4">
                        </div>
                        <div class="col-6 col-md-2">
                            <input name="kategori" class="form-control form-control-sm bg-white" placeholder="Kategori" maxlength="100">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                            <button type="button" class="btn btn-light border btn-sm" data-bs-toggle="collapse" data-bs-target="#formTambahBuku">Tutup</button>
                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="bi bi-check2 me-1"></i>Simpan Buku
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- PENCARIAN BUKU -->
        <div class="mb-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="search" id="cariBuku" class="form-control bg-light border-start-0 ps-0" placeholder="Ketik untuk mencari judul buku, penulis, penerbit, tahun, atau kategori...">
            </div>
        </div>

        <!-- TABEL BUKU -->
        <div class="table-responsive scroll-y border rounded-3">
            <table class="table table-modern table-hover align-middle mb-0" id="tabelBuku">
                <thead>
                    <tr>
                        <th style="width: 55px;" class="text-center">#</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th class="text-center" style="width: 85px;">Tahun</th>
                        <th>Kategori</th>
                        <?php if ($is_admin): ?>
                            <th class="text-end pe-3" style="width: 60px;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($buku)): ?>
                        <tr>
                            <td colspan="<?= $is_admin ? 7 : 6 ?>">
                                <div class="empty-state py-4">
                                    <div class="empty-state-icon" style="width:52px;height:52px;font-size:1.5rem;"><i class="bi bi-journal-x"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Judul Buku</h6>
                                    <p class="text-muted small mb-0">Tambahkan buku satu per satu atau gunakan tombol <strong>Impor CSV</strong> untuk mengunggah ratusan judul sekaligus.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($buku as $b): ?>
                            <tr>
                                <td class="text-center text-muted small"><?= $no++ ?></td>
                                <td class="fw-semibold text-dark"><?= html_escape($b['judul']) ?></td>
                                <td class="text-secondary small"><?= $b['penulis'] ? html_escape((string) $b['penulis']) : '-' ?></td>
                                <td class="text-secondary small"><?= $b['penerbit'] ? html_escape((string) $b['penerbit']) : '-' ?></td>
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
                                                'onsubmit' => "return confirm('Hapus buku ini dari paket?');"
                                            ]
                                        ) ?>
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus buku">
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
<div class="modern-card">
    <div class="card-header bg-transparent border-bottom px-4 py-3">
        <h5 class="fw-bold text-dark mb-1">
            <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Perjalanan Paket
        </h5>
        <p class="text-muted small mb-0">Jejak distribusi paket dari perpustakaan maupun perpindahan (oper) antar lokasi.</p>
    </div>

    <div class="card-body p-0">
        <?php if (empty($riwayat)): ?>
            <div class="empty-state py-4">
                <p class="text-muted small mb-0">Paket ini belum memiliki riwayat peminjaman.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-modern table-hover align-middle mb-0">
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
                                    <span class="fw-bold text-dark"><?= html_escape($r['nama_lokasi']) ?></span>
                                    <?php if ($r['jenis'] === 'oper'): ?>
                                        <span class="badge badge-soft-warning ms-1">Oper</span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-info ms-1">Pinjam</span>
                                    <?php endif; ?>
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
        <div class="modal-content">
            <?= form_open('backend/peminjaman/proses_tarik/' . (int) $paket['id']) ?>
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-info" style="width:38px;height:38px;font-size:1rem;">
                            <i class="bi bi-box-arrow-in-down-left"></i>
                        </div>
                        <h5 class="modal-title fw-bold" id="modalTarikLabel">Tarik Paket <?= html_escape($paket['kode']) ?></h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="p-3 rounded-3 bg-light border small text-secondary mb-3">
                        Paket akan ditarik dari <strong><?= html_escape($paket['nama_lokasi']) ?></strong> kembali ke perpustakaan dan statusnya berubah menjadi <strong>Tersedia</strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Tanggal Ditarik <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div>
                        <label class="form-label fw-bold small text-secondary">Catatan Penarikan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="catatan" rows="2" class="form-control" placeholder="Kondisi fisik buku, kelengkapan jumlah, atau berita acara..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i>Konfirmasi Tarik Paket
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPerpanjang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <?= form_open('backend/peminjaman/proses_perpanjang/' . (int) $paket['id']) ?>
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-primary" style="width:38px;height:38px;font-size:1rem;">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <h5 class="modal-title fw-bold">Perpanjang Masa Pinjam</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="small text-muted mb-3">
                        Jatuh tempo saat ini: <strong><?= tgl_indo($aktif['tgl_jatuh_tempo']) ?></strong>
                    </div>

                    <label class="form-label fw-bold small text-secondary">Tanggal Jatuh Tempo Baru <span class="text-danger">*</span></label>
                    <input type="date"
                           name="tgl_jatuh_tempo"
                           id="inputJatuhTempoBaru"
                           class="form-control"
                           min="<?= date('Y-m-d', strtotime($aktif['tgl_jatuh_tempo'] . ' +1 day')) ?>"
                           value="<?= date('Y-m-d', strtotime($aktif['tgl_jatuh_tempo'] . ' +30 days')) ?>"
                           required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i>Simpan Perpanjangan
                    </button>
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
        <div class="modal-content">
            <?= form_open_multipart('backend/buku/import/' . (int) $paket['id']) ?>
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-success" style="width:38px;height:38px;font-size:1rem;">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                        </div>
                        <h5 class="modal-title fw-bold" id="modalImportLabel">Impor Daftar Buku dari CSV</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="p-3 rounded-3 bg-light border small mb-3">
                        <div class="fw-bold text-dark mb-1">Format Urutan Kolom CSV:</div>
                        <code class="d-block mb-2">judul, penulis, penerbit, tahun, kategori</code>
                        <div class="text-muted mb-2">Hanya kolom <strong>judul</strong> yang wajib terisi. Dari Microsoft Excel, pilih <em>Save As &rarr; CSV (Comma delimited)</em>.</div>
                        <a href="<?= base_url('backend/buku/template') ?>" class="btn btn-sm btn-outline-primary py-1">
                            <i class="bi bi-download me-1"></i>Unduh Template Contoh CSV
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Pilih File CSV <span class="text-danger">*</span></label>
                        <input type="file" name="file_csv" accept=".csv" class="form-control" required>
                    </div>

                    <label class="form-label fw-bold small text-secondary d-block">Mode Impor Data</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="mode" value="tambah" id="m1" checked>
                        <label class="form-check-label small" for="m1">
                            <strong>Tambahkan</strong> ke daftar buku yang sudah ada
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mode" value="ganti" id="m2">
                        <label class="form-check-label small text-danger" for="m2">
                            <strong>Ganti seluruh daftar</strong> (hapus daftar buku lama di paket ini)
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i>Proses Impor CSV
                    </button>
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
                if (tr.querySelector('.empty-state')) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
