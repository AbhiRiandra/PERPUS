<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="modern-card">

    <!-- HEADER -->
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-collection-fill text-primary me-2"></i>Daftar Paket Buku
            </h5>
            <p class="text-muted small mb-0">
                Setiap paket berisi ratusan judul buku yang didistribusikan sebagai satu kesatuan.
            </p>
        </div>

        <?php if ($is_admin): ?>
            <a href="<?= base_url('backend/paket/tambah') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Paket Baru</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- FILTER & PENCARIAN BAR -->
    <div class="px-4 py-3 border-bottom bg-light bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="filter-pills">
            <?php
            $filter_status = [
                ''          => ['Semua',     'bi-grid'],
                'tersedia'  => ['Tersedia',  'bi-check2-circle'],
                'dipinjam'  => ['Dipinjam',  'bi-box-arrow-up-right'],
                'terlambat' => ['Terlambat', 'bi-exclamation-triangle'],
            ];
            foreach ($filter_status as $v => $meta):
            ?>
                <a href="<?= base_url('backend/paket' . ($v ? '?status=' . $v : '')) ?>"
                   class="filter-pill <?= $status === $v ? 'active' : '' ?>">
                    <i class="bi <?= $meta[1] ?> me-1"></i><?= $meta[0] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div style="min-width: 260px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="search" id="cariPaketList" class="form-control bg-white border-start-0 ps-0" placeholder="Cari kode, nama, atau deskripsi paket...">
            </div>
        </div>
    </div>

    <!-- TABEL PAKET -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle" id="tabelPaketList">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 90px;">Kode</th>
                        <th>Nama &amp; Deskripsi Paket</th>
                        <th class="text-center" style="width: 140px;">Jumlah Koleksi</th>
                        <th>Status &amp; Posisi Saat Ini</th>
                        <th class="text-end pe-4" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($paket)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">Tidak Ada Paket Ditemukan</h6>
                                    <p class="text-muted small mb-3">
                                        <?= $status ? 'Tidak ada paket dengan filter status "' . html_escape(ucfirst($status)) . '".' : 'Belum ada paket buku yang terdaftar di sistem.' ?>
                                    </p>
                                    <?php if ($status): ?>
                                        <a href="<?= base_url('backend/paket') ?>" class="btn btn-sm btn-outline-primary">Tampilkan Semua Paket</a>
                                    <?php elseif ($is_admin): ?>
                                        <a href="<?= base_url('backend/paket/tambah') ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat Paket Pertama</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($paket as $p): ?>
                            <tr>
                                <!-- KODE -->
                                <td class="ps-4">
                                    <span class="paket-code-badge"><?= html_escape($p['kode']) ?></span>
                                </td>

                                <!-- NAMA PAKET -->
                                <td>
                                    <a href="<?= base_url('backend/paket/detail/' . (int) $p['id']) ?>" class="fw-bold text-dark text-decoration-none d-block mb-1">
                                        <?= html_escape($p['nama']) ?>
                                    </a>
                                    <div class="text-muted small">
                                        <?= $p['deskripsi'] ? html_escape(character_limiter((string) $p['deskripsi'], 75)) : '<span class="fst-italic">Tidak ada deskripsi singkat</span>' ?>
                                    </div>
                                </td>

                                <!-- JUMLAH JUDUL -->
                                <td class="text-center">
                                    <span class="badge badge-soft-secondary">
                                        <i class="bi bi-book me-1"></i><?= (int) $p['jml_buku'] ?> judul
                                    </span>
                                </td>

                                <!-- STATUS DAN POSISI -->
                                <td>
                                    <?php if ($p['status'] === 'dipinjam'): ?>
                                        <div class="fw-bold text-dark mb-1">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= html_escape($p['nama_lokasi']) ?>
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap gap-2 small text-muted">
                                            <span>Jatuh tempo: <strong><?= tgl_indo($p['tgl_jatuh_tempo']) ?></strong></span>
                                            <?= badge_jatuh_tempo($p['tgl_jatuh_tempo']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-soft-success">
                                            <i class="bi bi-building-check me-1"></i>Tersedia di Perpustakaan
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- AKSI -->
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <?php if ($p['status'] === 'tersedia'): ?>
                                            <a href="<?= base_url('backend/peminjaman/pinjam/' . (int) $p['id']) ?>"
                                               class="btn btn-sm btn-outline-success"
                                               title="Pinjamkan ke Lokasi">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('backend/peminjaman/oper/' . (int) $p['id']) ?>"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Oper ke Lokasi Lain">
                                                <i class="bi bi-arrow-left-right"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= base_url('backend/paket/detail/' . (int) $p['id']) ?>"
                                           class="btn btn-sm btn-primary px-3">
                                            Detail <i class="bi bi-chevron-right ms-1 small"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('cariPaketList');
    if (input) {
        input.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabelPaketList tbody tr').forEach(function (tr) {
                if (tr.querySelector('.empty-state')) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
