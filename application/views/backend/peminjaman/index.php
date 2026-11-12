<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="clean-card">

    <!-- HEADER -->
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-0">Riwayat Sirkulasi Peminjaman</h5>
            <span class="text-muted small">Catatan peminjaman, oper lokasi, dan pengembalian paket buku</span>
        </div>
        <span class="badge badge-soft-secondary"><?= count($riwayat) ?> Catatan</span>
    </div>

    <!-- FILTER BAR -->
    <div class="px-4 py-2 border-bottom bg-light bg-opacity-50">
        <form method="get" action="<?= base_url('backend/peminjaman') ?>" class="row g-2 align-items-center">
            <div class="col-12 col-sm-6 col-lg-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <?php foreach ([
                        'berjalan'  => 'Sedang Berjalan',
                        'terlambat' => 'Terlambat',
                        'selesai'   => 'Selesai'
                    ] as $v => $t): ?>
                        <option value="<?= $v ?>" <?= $status === $v ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <select name="lokasi" class="form-select form-select-sm">
                    <option value="">Semua Lokasi / Kelurahan</option>
                    <?php foreach ($lokasi as $l): ?>
                        <option value="<?= (int) $l['id'] ?>" <?= (int) $id_lokasi === (int) $l['id'] ? 'selected' : '' ?>>
                            <?= html_escape($l['nama_lokasi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-sm-7 col-lg-3">
                <input type="search" id="cariRiwayat" class="form-control form-control-sm" placeholder="Cari di tabel...">
            </div>

            <div class="col-12 col-sm-5 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    Terapkan
                </button>
                <?php if ($status || $id_lokasi): ?>
                    <a href="<?= base_url('backend/peminjaman') ?>" class="btn btn-light border btn-sm" title="Reset">
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
        <div class="px-4 py-2 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2 bg-light bg-opacity-25">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="pilihSemuaRiwayat">
                <label class="form-check-label small text-muted" for="pilihSemuaRiwayat">
                    Pilih semua yang berstatus <strong>Selesai</strong>
                </label>
            </div>

            <button type="submit" form="formHapusRiwayat" class="btn btn-sm btn-outline-danger" id="btnHapusRiwayat">
                <i class="bi bi-trash3 me-1"></i>Hapus Terpilih
            </button>
        </div>
    <?php endif; ?>

    <!-- TABEL RIWAYAT -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean table-hover align-middle mb-0" id="tabelRiwayat">
                <thead>
                    <tr>
                        <?php if ($is_admin): ?>
                            <th class="ps-4 text-center" style="width: 45px;">Pilih</th>
                        <?php endif; ?>
                        <th class="<?= $is_admin ? '' : 'ps-4' ?>" style="width: 70px;">Paket</th>
                        <th>Lokasi Tujuan</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Selesai</th>
                        <th>Status</th>
                        <th class="pe-4">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($riwayat)): ?>
                    <tr>
                        <td colspan="<?= $is_admin ? 8 : 7 ?>" class="text-center py-4 text-muted small">
                            Tidak ada data riwayat peminjaman yang sesuai.
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
                                               title="Riwayat berjalan tidak dapat dihapus">
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <td class="<?= $is_admin ? '' : 'ps-4' ?>">
                                <a href="<?= base_url('backend/paket/detail/' . (int) $r['id_paket']) ?>" class="text-decoration-none">
                                    <span class="paket-code-badge"><?= html_escape($r['kode_paket']) ?></span>
                                </a>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold text-dark"><?= html_escape($r['nama_lokasi']) ?></span>
                                    <span class="badge badge-soft-secondary"><?= ucfirst($r['jenis']) ?></span>
                                </div>
                                <?php if (!empty($r['catatan'])): ?>
                                    <div class="text-muted small mt-1"><?= html_escape((string) $r['catatan']) ?></div>
                                <?php endif; ?>
                            </td>

                            <td class="small"><?= tgl_indo($r['tgl_pinjam']) ?></td>
                            <td class="small"><?= tgl_indo($r['tgl_jatuh_tempo']) ?></td>
                            <td class="small text-muted"><?= tgl_indo($r['tgl_kembali']) ?></td>

                            <td>
                                <?php if ($r['status'] === 'berjalan'): ?>
                                    <?= badge_jatuh_tempo($r['tgl_jatuh_tempo']) ?>
                                <?php else: ?>
                                    <span class="badge badge-soft-secondary">Selesai</span>
                                <?php endif; ?>
                            </td>

                            <td class="small text-muted pe-4">
                                <?= $r['nama_petugas'] ? html_escape($r['nama_petugas']) : '-' ?>
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
    return confirm('Hapus ' + terpilih.length + ' riwayat peminjaman yang dipilih?');
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
                if (tr.querySelector('.empty-state') || tr.cells.length < 3) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
