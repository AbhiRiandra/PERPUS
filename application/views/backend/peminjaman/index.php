<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="modern-card">

    <!-- HEADER -->
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Sirkulasi Peminjaman
            </h5>
            <p class="text-muted small mb-0">
                Rekam jejak otomatis setiap paket buku yang dipinjamkan, dioper antar lokasi, maupun ditarik kembali.
            </p>
        </div>
        <span class="badge badge-soft-primary"><?= count($riwayat) ?> Data Tercatat</span>
    </div>

    <!-- FILTER BAR -->
    <div class="px-4 py-3 border-bottom bg-light bg-opacity-50">
        <form method="get" action="<?= base_url('backend/peminjaman') ?>" class="row g-2 align-items-center">
            <div class="col-12 col-sm-6 col-lg-3">
                <select name="status" class="form-select form-select-sm bg-white">
                    <option value="">Semua Status Sirkulasi</option>
                    <?php foreach ([
                        'berjalan'  => 'Sedang Berjalan (Aktif)',
                        'terlambat' => 'Melewati Jatuh Tempo (Terlambat)',
                        'selesai'   => 'Selesai (Sudah Ditarik / Dioper)'
                    ] as $v => $t): ?>
                        <option value="<?= $v ?>" <?= $status === $v ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <select name="lokasi" class="form-select form-select-sm bg-white">
                    <option value="">Semua Lokasi / Kelurahan</option>
                    <?php foreach ($lokasi as $l): ?>
                        <option value="<?= (int) $l['id'] ?>" <?= (int) $id_lokasi === (int) $l['id'] ? 'selected' : '' ?>>
                            <?= html_escape($l['nama_lokasi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-sm-7 col-lg-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="search" id="cariRiwayat" class="form-control bg-white border-start-0 ps-0" placeholder="Filter cepat di tabel...">
                </div>
            </div>

            <div class="col-12 col-sm-5 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-funnel me-1"></i>Terapkan
                </button>
                <?php if ($status || $id_lokasi): ?>
                    <a href="<?= base_url('backend/peminjaman') ?>" class="btn btn-light border btn-sm" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- TOOLBAR HAPUS MASSAL (KHUSUS ADMIN) -->
    <?php if ($is_admin): ?>
        <?= form_open(
            'backend/peminjaman/hapus_terpilih',
            [
                'id' => 'formHapusRiwayat',
                'onsubmit' => "return konfirmasiHapusRiwayat();"
            ]
        ) ?>
        <div class="px-4 py-2 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #f8fafc;">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="pilihSemuaRiwayat">
                <label class="form-check-label small fw-medium text-secondary" for="pilihSemuaRiwayat">
                    Pilih semua riwayat yang berstatus <strong>Selesai</strong>
                </label>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted d-none d-sm-inline">
                    <i class="bi bi-info-circle me-1"></i>Hanya riwayat selesai yang dapat dihapus
                </span>
                <button type="submit" form="formHapusRiwayat" class="btn btn-sm btn-outline-danger fw-semibold" id="btnHapusRiwayat">
                    <i class="bi bi-trash3 me-1"></i>Hapus Terpilih
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- TABEL RIWAYAT -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0" id="tabelRiwayat">
                <thead>
                    <tr>
                        <?php if ($is_admin): ?>
                            <th class="ps-4 text-center" style="width: 55px;">Pilih</th>
                        <?php endif; ?>
                        <th class="<?= $is_admin ? '' : 'ps-4' ?>" style="width: 90px;">Paket</th>
                        <th>Lokasi &amp; Jenis Transaksi</th>
                        <th>Tgl Masuk</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Keluar</th>
                        <th>Status</th>
                        <th class="pe-4">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($riwayat)): ?>
                    <tr>
                        <td colspan="<?= $is_admin ? 8 : 7 ?>">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-clock-history"></i></div>
                                <h6 class="fw-bold text-dark mb-1">Tidak Ada Data Riwayat</h6>
                                <p class="text-muted small mb-0">Belum ada transaksi peminjaman yang sesuai dengan filter saat ini.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($riwayat as $r): ?>
                        <?php $boleh_hapus = $r['status'] === 'selesai'; ?>
                        <tr>
                            <?php if ($is_admin): ?>
                                <td class="ps-4 text-center">
                                    <?php if ($boleh_hapus): ?>
                                        <input class="form-check-input checkbox-riwayat"
                                               type="checkbox"
                                               name="riwayat_ids[]"
                                               value="<?= (int) $r['id'] ?>"
                                               aria-label="Pilih riwayat <?= html_escape($r['kode_paket']) ?>">
                                    <?php else: ?>
                                        <input class="form-check-input"
                                               type="checkbox"
                                               disabled
                                               title="Riwayat yang masih berjalan tidak dapat dihapus">
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <td class="<?= $is_admin ? '' : 'ps-4' ?>">
                                <a href="<?= base_url('backend/paket/detail/' . (int) $r['id_paket']) ?>" class="text-decoration-none">
                                    <span class="paket-code-badge"><?= html_escape($r['kode_paket']) ?></span>
                                </a>
                            </td>

                            <td>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="fw-bold text-dark"><?= html_escape($r['nama_lokasi']) ?></span>
                                    <?php if ($r['jenis'] === 'oper'): ?>
                                        <span class="badge badge-soft-warning">Oper Lokasi</span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-info">Pinjam Baru</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($r['catatan'])): ?>
                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-chat-left-text me-1"></i><?= html_escape((string) $r['catatan']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="small fw-medium"><?= tgl_indo($r['tgl_pinjam']) ?></td>
                            <td class="small fw-medium"><?= tgl_indo($r['tgl_jatuh_tempo']) ?></td>
                            <td class="small text-secondary"><?= tgl_indo($r['tgl_kembali']) ?></td>

                            <td>
                                <?php if ($r['status'] === 'berjalan'): ?>
                                    <?= badge_jatuh_tempo($r['tgl_jatuh_tempo']) ?>
                                <?php else: ?>
                                    <span class="badge badge-soft-secondary">
                                        <i class="bi bi-check2-all me-1"></i>Selesai
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="small text-secondary pe-4">
                                <?php if ($r['nama_petugas']): ?>
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-person-circle text-muted"></i>
                                        <?= html_escape($r['nama_petugas']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($is_admin): ?>
        <?= form_close() ?>
    <?php endif; ?>

</div>

<script>
function konfirmasiHapusRiwayat() {
    var terpilih = document.querySelectorAll('.checkbox-riwayat:checked');
    if (terpilih.length === 0) {
        alert('Pilih minimal satu riwayat berstatus Selesai yang ingin dihapus.');
        return false;
    }
    return confirm(
        'Apakah Anda yakin ingin menghapus ' +
        terpilih.length +
        ' riwayat peminjaman yang dipilih? Data yang dihapus tidak dapat dikembalikan.'
    );
}

document.addEventListener('DOMContentLoaded', function () {
    var pilihSemua = document.getElementById('pilihSemuaRiwayat');
    var checkboxRiwayat = document.querySelectorAll('.checkbox-riwayat');

    if (pilihSemua) {
        pilihSemua.addEventListener('change', function () {
            checkboxRiwayat.forEach(function (checkbox) {
                checkbox.checked = pilihSemua.checked;
            });
        });
    }

    checkboxRiwayat.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var semuaTerpilih = checkboxRiwayat.length > 0 &&
                Array.from(checkboxRiwayat).every(function (item) {
                    return item.checked;
                });
            if (pilihSemua) {
                pilihSemua.checked = semuaTerpilih;
            }
        });
    });

    var cariInput = document.getElementById('cariRiwayat');
    if (cariInput) {
        cariInput.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabelRiwayat tbody tr').forEach(function (tr) {
                if (tr.querySelector('.empty-state')) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
