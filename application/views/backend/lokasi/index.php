<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="clean-card">

    <!-- HEADER -->
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-0">Lokasi / Kelurahan Tujuan</h5>
            <span class="text-muted small">Daftar kelurahan dan titik layanan baca penerima paket buku</span>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-2">
            <input type="search" id="cariLokasi" class="form-control form-control-sm" style="width: 220px;" placeholder="Cari nama lokasi atau PJ...">

            <?php if ($is_admin): ?>
                <a href="<?= base_url('backend/lokasi/tambah') ?>" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Lokasi</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- TABEL LOKASI -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-clean table-hover align-middle" id="tabelLokasi">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" style="width: 50px;">No</th>
                        <th>Nama Lokasi &amp; Alamat</th>
                        <th>Jenis Layanan</th>
                        <th>Penanggung Jawab</th>
                        <th class="text-center" style="width: 120px;">Paket Aktif</th>
                        <?php if ($is_admin): ?>
                            <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lokasi)): ?>
                        <tr>
                            <td colspan="<?= $is_admin ? 6 : 5 ?>">
                                <div class="empty-state py-4">
                                    <div class="empty-state-icon"><i class="bi bi-pin-map"></i></div>
                                    <div class="fw-semibold text-dark mb-1">Belum ada lokasi terdaftar</div>
                                    <div class="text-muted small mb-3">Tambahkan kelurahan atau titik baca untuk mulai mendistribusikan paket buku.</div>
                                    <?php if ($is_admin): ?>
                                        <a href="<?= base_url('backend/lokasi/tambah') ?>" class="btn btn-sm btn-primary">
                                            Tambah Lokasi Pertama
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($lokasi as $l): ?>
                            <tr>
                                <!-- NOMOR -->
                                <td class="ps-4 text-center text-muted small"><?= $no++ ?></td>

                                <!-- NAMA LOKASI & ALAMAT -->
                                <td>
                                    <div class="fw-semibold text-dark"><?= html_escape($l['nama_lokasi']) ?></div>
                                    <?php if ($l['alamat']): ?>
                                        <div class="text-muted small"><?= html_escape((string) $l['alamat']) ?></div>
                                    <?php endif; ?>
                                </td>

                                <!-- JENIS -->
                                <td>
                                    <span class="badge badge-soft-secondary">
                                        <?= html_escape($jenis[$l['jenis']] ?? $l['jenis']) ?>
                                    </span>
                                </td>

                                <!-- PENANGGUNG JAWAB -->
                                <td>
                                    <div class="small fw-medium text-dark">
                                        <?= $l['penanggung_jawab'] ? html_escape($l['penanggung_jawab']) : '<span class="text-muted fw-normal">-</span>' ?>
                                    </div>
                                    <?php if ($l['no_hp']): ?>
                                        <div class="text-muted small"><?= html_escape($l['no_hp']) ?></div>
                                    <?php endif; ?>
                                </td>

                                <!-- JUMLAH PAKET SAAT INI -->
                                <td class="text-center">
                                    <?php if ((int) $l['jml_paket'] > 0): ?>
                                        <span class="badge badge-soft-primary">
                                            <?= (int) $l['jml_paket'] ?> paket
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">0 paket</span>
                                    <?php endif; ?>
                                </td>

                                <!-- AKSI ADMIN -->
                                <?php if ($is_admin): ?>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <a href="<?= base_url('backend/lokasi/edit/' . (int) $l['id']) ?>"
                                               class="btn btn-sm btn-light border"
                                               title="Edit Lokasi">
                                                Edit
                                            </a>

                                            <?= form_open(
                                                'backend/lokasi/delete/' . (int) $l['id'],
                                                [
                                                    'class' => 'd-inline',
                                                    'onsubmit' => "return confirm('Hapus lokasi ini?');"
                                                ]
                                            ) ?>
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Lokasi">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            <?= form_close() ?>
                                        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('cariLokasi');
    if (input) {
        input.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabelLokasi tbody tr').forEach(function (tr) {
                if (tr.querySelector('.empty-state') || tr.cells.length < 3) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
