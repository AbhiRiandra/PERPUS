<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="clean-card">

    <!-- HEADER -->
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-0">Daftar Paket Buku</h5>
            <span class="text-muted small">Kelola paket koleksi buku untuk distribusi kelurahan</span>
        </div>

        <?php if ($is_admin): ?>
            <a href="<?= base_url('backend/paket/tambah') ?>" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Paket</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- FILTER & PENCARIAN -->
    <div class="px-4 py-2 border-bottom bg-light bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div class="filter-pills">
            <?php
            $filter_status = [
                ''          => 'Semua',
                'tersedia'  => 'Tersedia',
                'dipinjam'  => 'Dipinjam',
                'terlambat' => 'Terlambat',
            ];
            foreach ($filter_status as $v => $label):
            ?>
                <a href="<?= base_url('backend/paket' . ($v ? '?status=' . $v : '')) ?>"
                   class="filter-pill <?= $status === $v ? 'active' : '' ?>">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div style="min-width: 240px;">
            <input type="search" id="cariPaketList" class="form-control form-control-sm" placeholder="Cari kode atau nama paket...">
        </div>
    </div>

    <!-- TABEL PAKET -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean table-hover align-middle" id="tabelPaketList">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 70px;">Kode</th>
                        <th>Nama Paket</th>
                        <th class="text-center" style="width: 130px;">Koleksi</th>
                        <th>Posisi Saat Ini</th>
                        <th class="text-end pe-4" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($paket)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state py-4">
                                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                    <div class="fw-semibold text-dark mb-1">Tidak ada paket ditemukan</div>
                                    <div class="text-muted small">
                                        <?= $status ? 'Tidak ada paket dengan filter status "' . html_escape(ucfirst($status)) . '".' : 'Belum ada paket buku yang terdaftar.' ?>
                                    </div>
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
                                    <a href="<?= base_url('backend/paket/detail/' . (int) $p['id']) ?>" class="fw-semibold text-dark text-decoration-none">
                                        <?= html_escape($p['nama']) ?>
                                    </a>
                                    <?php if ($p['deskripsi']): ?>
                                        <div class="text-muted small"><?= html_escape(character_limiter((string) $p['deskripsi'], 65)) ?></div>
                                    <?php endif; ?>
                                </td>

                                <!-- JUMLAH JUDUL -->
                                <td class="text-center">
                                    <span class="badge badge-soft-secondary">
                                        <?= (int) $p['jml_buku'] ?> judul
                                    </span>
                                </td>

                                <!-- STATUS DAN POSISI -->
                                <td>
                                    <?php if ($p['status'] === 'dipinjam'): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold text-dark"><?= html_escape($p['nama_lokasi']) ?></span>
                                            <?= badge_jatuh_tempo($p['tgl_jatuh_tempo']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-soft-success">Tersedia di Perpus</span>
                                    <?php endif; ?>
                                </td>

                                <!-- AKSI -->
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <?php if ($p['status'] === 'tersedia'): ?>
                                            <a href="<?= base_url('backend/peminjaman/pinjam/' . (int) $p['id']) ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Pinjamkan">
                                                Pinjamkan
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('backend/peminjaman/oper/' . (int) $p['id']) ?>"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Oper ke Lokasi Lain">
                                                Oper
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= base_url('backend/paket/detail/' . (int) $p['id']) ?>"
                                           class="btn btn-sm btn-light border" title="Detail">
                                            <i class="bi bi-chevron-right"></i>
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
