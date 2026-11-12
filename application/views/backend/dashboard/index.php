<?php
$kartu = [
    ['Total Paket',   $stat['total_paket'], 'bi-collection',          'primary', 'backend/paket'],
    ['Tersedia',      $stat['tersedia'],    'bi-check2-circle',       'success', 'backend/paket?status=tersedia'],
    ['Sedang Dipinjam', $stat['dipinjam'],  'bi-box-arrow-up-right',  'info',    'backend/paket?status=dipinjam'],
    ['Terlambat',     $stat['terlambat'],   'bi-exclamation-octagon', 'danger',  'backend/paket?status=terlambat'],
];
?>
<div class="row g-3 mb-4">
    <?php foreach ($kartu as $k): ?>
        <div class="col-6 col-lg-3">
            <a href="<?= base_url($k[4]) ?>" class="text-decoration-none">
                <div class="card glass-card border-0 shadow-sm h-100 hover-up">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-<?= $k[3] ?> text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="bi <?= $k[2] ?>"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark lh-1"><?= (int) $k[1] ?></div>
                            <div class="small text-muted"><?= $k[0] ?></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="card glass-card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-geo-alt me-2"></i>Posisi Paket Saat Ini</h5>
        <p class="text-muted small mb-0">Total <?= number_format($stat['total_buku']) ?> judul buku di <?= (int) $stat['total_paket'] ?> paket, tersebar di <?= (int) $stat['total_lokasi'] ?> lokasi terdaftar.</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Paket</th><th class="text-center">Judul</th><th>Posisi</th><th>Jatuh Tempo</th><th></th></tr>
                </thead>
                <tbody>
                <?php if (empty($paket)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada paket. Mulai dari menu <strong>Paket Buku</strong>.</td></tr>
                <?php else: foreach ($paket as $p): ?>
                    <tr>
                        <td>
                            <span class="badge bg-dark me-1"><?= html_escape($p['kode']) ?></span>
                            <span class="fw-semibold"><?= html_escape($p['nama']) ?></span>
                        </td>
                        <td class="text-center"><?= (int) $p['jml_buku'] ?></td>
                        <td>
                            <?php if ($p['status'] === 'dipinjam'): ?>
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= html_escape($p['nama_lokasi']) ?>
                            <?php else: ?>
                                <span class="text-success"><i class="bi bi-building me-1"></i>Di perpustakaan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['status'] === 'dipinjam'): ?>
                                <?= tgl_indo($p['tgl_jatuh_tempo']) ?> <?= badge_jatuh_tempo($p['tgl_jatuh_tempo']) ?>
                            <?php else: ?>-<?php endif; ?>
                        </td>
                        <td class="text-end"><a href="<?= base_url('backend/paket/detail/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($terlambat)): ?>
<div class="card border-danger shadow-sm">
    <div class="card-header bg-danger text-white fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Perlu Segera Ditarik / Dioper (<?= count($terlambat) ?>)</div>
    <ul class="list-group list-group-flush">
        <?php foreach ($terlambat as $t): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-dark"><?= html_escape($t['kode_paket']) ?></span>
                    di <strong><?= html_escape($t['nama_lokasi']) ?></strong>
                    <span class="text-muted small">&middot; jatuh tempo <?= tgl_indo($t['tgl_jatuh_tempo']) ?></span>
                </div>
                <div>
                    <?= badge_jatuh_tempo($t['tgl_jatuh_tempo']) ?>
                    <a href="<?= base_url('backend/paket/detail/' . $t['id_paket']) ?>" class="btn btn-sm btn-outline-danger ms-2">Proses</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
