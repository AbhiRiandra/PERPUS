<?php
$kartu = [
    ['Total Paket',   $stat['total_paket'], 'bi-collection',    'primary', 'backend/paket'],
    ['Tersedia',      $stat['tersedia'],    'bi-check2-circle', 'success', 'backend/paket?status=tersedia'],
    ['Dipinjam',      $stat['dipinjam'],    'bi-arrow-right',   'info',    'backend/paket?status=dipinjam'],
    ['Terlambat',     $stat['terlambat'],   'bi-clock-history', 'danger',  'backend/paket?status=terlambat'],
];
?>

<!-- 4 KARTU STATISTIK MINIMALIS -->
<div class="row g-3 mb-4">
    <?php foreach ($kartu as $k): ?>
        <div class="col-6 col-md-3">
            <a href="<?= base_url($k[4]) ?>" class="text-decoration-none">
                <div class="clean-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-medium"><?= $k[0] ?></span>
                        <i class="bi <?= $k[2] ?> text-secondary opacity-50"></i>
                    </div>
                    <div class="fs-4 fw-bold text-dark lh-1"><?= (int) $k[1] ?></div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<!-- PERINGATAN TERLAMBAT (JIKA ADA) -->
<?php if (!empty($terlambat)): ?>
<div class="clean-card mb-4" style="border-left: 3px solid #ef4444;">
    <div class="px-3 py-2 border-bottom bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-danger small">
            <i class="bi bi-exclamation-circle me-1"></i> <?= count($terlambat) ?> Paket Melewati Jatuh Tempo
        </span>
        <a href="<?= base_url('backend/peminjaman?status=terlambat') ?>" class="small text-danger text-decoration-none">Lihat Semua &rarr;</a>
    </div>
    <div class="list-group list-group-flush small">
        <?php foreach ($terlambat as $t): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                <div>
                    <strong><?= html_escape($t['kode_paket']) ?></strong> di <?= html_escape($t['nama_lokasi']) ?>
                    <span class="text-muted ms-1">(tempo: <?= tgl_indo($t['tgl_jatuh_tempo']) ?>)</span>
                </div>
                <div>
                    <?= badge_jatuh_tempo($t['tgl_jatuh_tempo']) ?>
                    <a href="<?= base_url('backend/paket/detail/' . $t['id_paket']) ?>" class="btn btn-sm btn-outline-danger py-0 px-2 ms-2">Proses</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- TABEL POSISI PAKET -->
<div class="clean-card">
    <div class="px-3 py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="fw-semibold text-dark">Posisi Paket Saat Ini</span>
        <div style="width: 220px;">
            <input type="search" id="cariDashboard" class="form-control form-control-sm" placeholder="Cari paket / lokasi...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-clean table-hover" id="tabelDashboard">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Paket</th>
                    <th class="text-center">Koleksi</th>
                    <th>Lokasi Saat Ini</th>
                    <th>Jatuh Tempo</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($paket)): ?>
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted small">Belum ada paket buku.</td>
                </tr>
            <?php else: foreach ($paket as $p): ?>
                <tr>
                    <td><span class="fw-bold text-dark"><?= html_escape($p['kode']) ?></span></td>
                    <td><?= html_escape($p['nama']) ?></td>
                    <td class="text-center text-muted"><?= (int) $p['jml_buku'] ?> judul</td>
                    <td>
                        <?php if ($p['status'] === 'dipinjam'): ?>
                            <span class="badge badge-soft-warning me-1">Dipinjam</span>
                            <?= html_escape($p['nama_lokasi']) ?>
                        <?php else: ?>
                            <span class="badge badge-soft-success">Tersedia di Perpus</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($p['status'] === 'dipinjam'): ?>
                            <span class="small"><?= tgl_indo($p['tgl_jatuh_tempo']) ?></span>
                            <?= badge_jatuh_tempo($p['tgl_jatuh_tempo']) ?>
                        <?php else: ?>
                            <span class="text-muted">&mdash;</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= base_url('backend/paket/detail/' . (int) $p['id']) ?>" class="btn btn-sm btn-light border py-1 px-2">
                            Detail
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('cariDashboard');
    if (input) {
        input.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabelDashboard tbody tr').forEach(function (tr) {
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
