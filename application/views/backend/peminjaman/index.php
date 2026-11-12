<?php
$is_admin = $this->session->userdata('role') === 'admin';
?>

<div class="card glass-card shadow-sm border-0">

    <!-- HEADER -->
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="fw-bold text-primary mb-1">
            <i class="bi bi-clock-history me-2"></i>
            Riwayat Peminjaman
        </h4>
        <p class="text-muted small mb-0">
            Dicatat otomatis setiap paket dipinjam, dioper, atau ditarik.
        </p>
    </div>

    <div class="card-body mt-2">

        <!-- FILTER -->
        <form method="get"
              action="<?= base_url('backend/peminjaman') ?>"
              class="row g-2 mb-3">

            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua status</option>
                    <?php foreach ([
                        'berjalan' => 'Sedang berjalan',
                        'terlambat' => 'Terlambat',
                        'selesai' => 'Selesai'
                    ] as $v => $t): ?>
                        <option value="<?= $v ?>"
                            <?= $status === $v ? 'selected' : '' ?>>
                            <?= $t ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-5">
                <select name="lokasi" class="form-select form-select-sm">
                    <option value="">Semua lokasi</option>
                    <?php foreach ($lokasi as $l): ?>
                        <option value="<?= (int) $l['id'] ?>"
                            <?= (int) $id_lokasi === (int) $l['id'] ? 'selected' : '' ?>>
                            <?= html_escape($l['nama_lokasi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    Filter
                </button>
                <a href="<?= base_url('backend/peminjaman') ?>"
                   class="btn btn-light btn-sm">
                    Reset
                </a>
            </div>
        </form>


        <!-- TABEL RIWAYAT DAN FORM HAPUS -->
        <?php if ($is_admin): ?>
            <?= form_open(
                'backend/peminjaman/hapus_terpilih',
                [
                    'id' => 'formHapusRiwayat',
                    'onsubmit' => "return konfirmasiHapusRiwayat();"
                ]
            ) ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

            <?php if ($is_admin): ?>
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           id="pilihSemuaRiwayat">
                    <label class="form-check-label small"
                           for="pilihSemuaRiwayat">
                        Pilih semua riwayat yang sudah selesai
                    </label>
                </div>

                <button type="submit"
                        form="formHapusRiwayat"
                        class="btn btn-sm btn-outline-danger"
                        id="btnHapusRiwayat">
                    Hapus Riwayat Terpilih
                </button>
            <?php endif; ?>

        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">

                <thead class="table-light">
                    <tr>
                        <?php if ($is_admin): ?>
                            <th class="text-center" style="width:4%">Pilih</th>
                        <?php endif; ?>
                        <th>Paket</th>
                        <th>Lokasi</th>
                        <th>Masuk</th>
                        <th>Jatuh Tempo</th>
                        <th>Keluar</th>
                        <th>Status</th>
                        <th>Petugas</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($riwayat)): ?>
                    <tr>
                        <td colspan="<?= $is_admin ? 8 : 7 ?>"
                            class="text-center text-muted py-4">
                            Tidak ada data.
                        </td>
                    </tr>
                <?php else: ?>

                    <?php foreach ($riwayat as $r): ?>
                        <?php $boleh_hapus = $r['status'] === 'selesai'; ?>

                        <tr>

                            <?php if ($is_admin): ?>
                                <td class="text-center">
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

                            <td>
                                <a href="<?= base_url('backend/paket/detail/' . (int) $r['id_paket']) ?>"
                                   class="text-decoration-none">
                                    <span class="badge bg-dark">
                                        <?= html_escape($r['kode_paket']) ?>
                                    </span>
                                </a>
                            </td>

                            <td class="fw-semibold">
                                <?= html_escape($r['nama_lokasi']) ?>

                                <?php if ($r['jenis'] === 'oper'): ?>
                                    <span class="badge bg-warning text-dark">
                                        oper
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td><?= tgl_indo($r['tgl_pinjam']) ?></td>
                            <td><?= tgl_indo($r['tgl_jatuh_tempo']) ?></td>
                            <td><?= tgl_indo($r['tgl_kembali']) ?></td>

                            <td>
                                <?php if ($r['status'] === 'berjalan'): ?>
                                    <?= badge_jatuh_tempo($r['tgl_jatuh_tempo']) ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        Selesai
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="small text-muted">
                                <?= $r['nama_petugas']
                                    ? html_escape($r['nama_petugas'])
                                    : '-' ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>
                </tbody>

            </table>
        </div>

        <?php if ($is_admin): ?>
            <?= form_close() ?>
        <?php endif; ?>

        <?php if ($is_admin): ?>
            <p class="small text-muted mt-2 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Hanya riwayat berstatus Selesai yang dapat dipilih untuk dihapus.
                Riwayat yang masih berjalan atau terlambat akan tetap tersimpan.
            </p>
        <?php endif; ?>

    </div>
</div>


<?php if ($is_admin): ?>
<script>
function konfirmasiHapusRiwayat() {
    var terpilih = document.querySelectorAll('.checkbox-riwayat:checked');

    if (terpilih.length === 0) {
        alert('Pilih minimal satu riwayat yang ingin dihapus.');
        return false;
    }

    return confirm(
        'Apakah kamu yakin ingin menghapus ' +
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
});
</script>
<?php endif; ?>
