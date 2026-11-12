<?php
$kartu = [
    ['Total Paket Buku',   $stat['total_paket'], 'bi-collection-fill',          'primary', 'backend/paket',                  'Seluruh paket terdaftar'],
    ['Tersedia di Perpus', $stat['tersedia'],    'bi-check2-circle',            'success', 'backend/paket?status=tersedia',  'Siap didistribusikan'],
    ['Sedang Dipinjam',    $stat['dipinjam'],    'bi-box-arrow-up-right',       'info',    'backend/paket?status=dipinjam',  'Berada di lokasi layanan'],
    ['Melewati Tempo',     $stat['terlambat'],   'bi-exclamation-octagon-fill', 'danger',  'backend/paket?status=terlambat', 'Perlu ditarik / dioper'],
];
?>

<!-- HERO BANNER RINGKASAN -->
<div class="modern-card border-0 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%); color: #ffffff;">
    <div class="card-body p-4 p-lg-4 position-relative z-1">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(255,255,255,0.12); font-size: 0.76rem; font-weight: 600;">
                    <i class="bi bi-sparkles text-info"></i>
                    <span>Panel Kendali Sirkulasi Kolektif</span>
                </div>
                <h3 class="fw-bold mb-1">Halo, <?= html_escape($this->session->userdata('nama')) ?>!</h3>
                <p class="text-white-50 mb-0" style="font-size: 0.92rem;">
                    Saat ini tercatat <strong><?= number_format((int) $stat['total_buku']) ?> judul buku</strong> yang tergabung dalam <strong><?= (int) $stat['total_paket'] ?> paket kolektif</strong> dan melayani <strong><?= (int) $stat['total_lokasi'] ?> titik lokasi/kelurahan</strong>.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="<?= base_url('backend/paket') ?>" class="btn btn-light fw-bold shadow-sm px-3">
                        <i class="bi bi-collection me-1 text-primary"></i>Kelola Paket
                    </a>
                    <a href="<?= base_url('backend/peminjaman') ?>" class="btn btn-outline-light fw-semibold px-3">
                        <i class="bi bi-clock-history me-1"></i>Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KARTU STATISTIK UTAMA -->
<div class="row g-3 mb-4">
    <?php foreach ($kartu as $k): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="<?= base_url($k[4]) ?>" class="text-decoration-none">
                <div class="modern-card h-100 hover-up p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-secondary fw-semibold small"><?= $k[0] ?></span>
                        <div class="stat-icon stat-icon-<?= $k[3] ?>" style="width:44px;height:44px;font-size:1.2rem;">
                            <i class="bi <?= $k[2] ?>"></i>
                        </div>
                    </div>
                    <div class="fs-2 fw-extrabold text-dark lh-1 mb-1" style="font-weight: 800;"><?= (int) $k[1] ?></div>
                    <div class="text-muted" style="font-size: 0.76rem;">
                        <?= $k[5] ?> <i class="bi bi-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<!-- PERINGATAN PAKET TERLAMBAT (JIKA ADA) -->
<?php if (!empty($terlambat)): ?>
<div class="modern-card mb-4 overflow-hidden" style="border: 1px solid #fecaca;">
    <div class="px-4 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(90deg, #fef2f2 0%, #fff1f2 100%); border-bottom: 1px solid #fecaca;">
        <div class="d-flex align-items-center gap-2 text-danger fw-bold">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <span>Perhatian: <?= count($terlambat) ?> Paket Melewati Jatuh Tempo (Perlu Segera Ditarik / Dioper)</span>
        </div>
        <a href="<?= base_url('backend/peminjaman?status=terlambat') ?>" class="btn btn-sm btn-danger">
            Lihat Semua Terlambat
        </a>
    </div>
    <div class="list-group list-group-flush">
        <?php foreach ($terlambat as $t): ?>
            <div class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="paket-code-badge"><?= html_escape($t['kode_paket']) ?></span>
                    <div>
                        <div class="fw-bold text-dark">
                            Berada di <span class="text-danger"><?= html_escape($t['nama_lokasi']) ?></span>
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar-event me-1"></i>Batas jatuh tempo: <strong><?= tgl_indo($t['tgl_jatuh_tempo']) ?></strong>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <?= badge_jatuh_tempo($t['tgl_jatuh_tempo']) ?>
                    <a href="<?= base_url('backend/paket/detail/' . $t['id_paket']) ?>" class="btn btn-sm btn-outline-danger fw-semibold">
                        <i class="bi bi-arrow-right-circle me-1"></i>Proses Paket
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- TABEL POSISI PAKET SAAT INI -->
<div class="modern-card">
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-geo-alt-fill text-primary me-2"></i>Posisi Paket Saat Ini
            </h5>
            <p class="text-muted small mb-0">
                Pemantauan real-time keberadaan setiap paket buku kolektif beserta status jatuh temponya.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2" style="min-width: 250px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="search" id="cariPaketDashboard" class="form-control border-start-0 ps-0" placeholder="Cari kode, nama paket, atau lokasi...">
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle" id="tabelPaketDashboard">
                <thead>
                    <tr>
                        <th class="ps-4">Paket Buku</th>
                        <th class="text-center">Koleksi</th>
                        <th>Posisi / Lokasi Saat Ini</th>
                        <th>Batas Jatuh Tempo</th>
                        <th class="text-end pe-4">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($paket)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-collection"></i></div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Paket Buku</h6>
                                <p class="text-muted small mb-3">Silakan tambahkan paket buku baru untuk memulai distribusi ke kelurahan.</p>
                                <a href="<?= base_url('backend/paket') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>Buka Menu Paket Buku
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: foreach ($paket as $p): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <span class="paket-code-badge"><?= html_escape($p['kode']) ?></span>
                                <div>
                                    <div class="fw-bold text-dark"><?= html_escape($p['nama']) ?></div>
                                    <div class="text-muted small">ID Paket #<?= (int) $p['id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft-secondary">
                                <i class="bi bi-book me-1"></i><?= (int) $p['jml_buku'] ?> judul
                            </span>
                        </td>
                        <td>
                            <?php if ($p['status'] === 'dipinjam'): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-soft-info"><i class="bi bi-geo-alt-fill me-1"></i>Dipinjam</span>
                                    <span class="fw-semibold text-dark"><?= html_escape($p['nama_lokasi']) ?></span>
                                </div>
                            <?php else: ?>
                                <span class="badge badge-soft-success">
                                    <i class="bi bi-building-check me-1"></i>Tersedia di Perpustakaan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['status'] === 'dipinjam'): ?>
                                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-1 gap-sm-2">
                                    <span class="fw-medium text-dark small"><?= tgl_indo($p['tgl_jatuh_tempo']) ?></span>
                                    <?= badge_jatuh_tempo($p['tgl_jatuh_tempo']) ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <?php if ($p['status'] === 'tersedia'): ?>
                                    <a href="<?= base_url('backend/peminjaman/pinjam/' . (int) $p['id']) ?>" class="btn btn-sm btn-outline-success" title="Pinjamkan Paket">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        <span class="d-none d-xl-inline ms-1">Pinjamkan</span>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('backend/peminjaman/oper/' . (int) $p['id']) ?>" class="btn btn-sm btn-outline-warning" title="Oper ke Lokasi Lain">
                                        <i class="bi bi-arrow-left-right"></i>
                                        <span class="d-none d-xl-inline ms-1">Oper</span>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('backend/paket/detail/' . (int) $p['id']) ?>" class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('cariPaketDashboard');
    if (input) {
        input.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabelPaketDashboard tbody tr').forEach(function (tr) {
                if (tr.querySelector('.empty-state')) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
