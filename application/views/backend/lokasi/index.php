<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="modern-card">

    <!-- HEADER -->
    <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-geo-alt-fill text-primary me-2"></i>Lokasi / Kelurahan Tujuan
            </h5>
            <p class="text-muted small mb-0">
                Daftar kelurahan, desa, sekolah, dan taman baca penerima layanan peminjaman paket buku kolektif.
            </p>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-2">
            <div class="input-group input-group-sm" style="width: 240px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="search" id="cariLokasi" class="form-control bg-light border-start-0 ps-0" placeholder="Cari lokasi, alamat, atau PJ...">
            </div>

            <?php if ($is_admin): ?>
                <a href="<?= base_url('backend/lokasi/tambah') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Lokasi</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- BODY TABEL -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle" id="tabelLokasi">
                <thead>
                    <tr>
                        <th class="ps-4 text-center" style="width: 60px;">No</th>
                        <th>Nama Lokasi &amp; Alamat</th>
                        <th>Jenis Layanan</th>
                        <th>Penanggung Jawab</th>
                        <th class="text-center" style="width: 140px;">Paket Aktif</th>
                        <?php if ($is_admin): ?>
                            <th class="text-end pe-4" style="width: 170px;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lokasi)): ?>
                        <tr>
                            <td colspan="<?= $is_admin ? 6 : 5 ?>">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="bi bi-pin-map"></i></div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Lokasi Terdaftar</h6>
                                    <p class="text-muted small mb-3">Tambahkan lokasi kelurahan atau titik baca sebagai tujuan distribusi paket buku.</p>
                                    <?php if ($is_admin): ?>
                                        <a href="<?= base_url('backend/lokasi/tambah') ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-lg me-1"></i>Tambah Lokasi Pertama
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($lokasi as $l): ?>
                            <tr>
                                <!-- NOMOR -->
                                <td class="ps-4 text-center text-muted small fw-semibold"><?= $no++ ?></td>

                                <!-- NAMA LOKASI & ALAMAT -->
                                <td>
                                    <div class="fw-bold text-dark"><?= html_escape($l['nama_lokasi']) ?></div>
                                    <div class="text-muted small">
                                        <?= $l['alamat'] ? '<i class="bi bi-pin-map me-1"></i>' . html_escape((string) $l['alamat']) : '<span class="fst-italic">Alamat belum diisi</span>' ?>
                                    </div>
                                </td>

                                <!-- JENIS -->
                                <td>
                                    <span class="badge badge-soft-primary">
                                        <?= html_escape($jenis[$l['jenis']] ?? $l['jenis']) ?>
                                    </span>
                                </td>

                                <!-- PENANGGUNG JAWAB -->
                                <td>
                                    <div class="fw-semibold text-dark small">
                                        <?= $l['penanggung_jawab'] ? html_escape($l['penanggung_jawab']) : '<span class="text-muted fw-normal">-</span>' ?>
                                    </div>
                                    <?php if ($l['no_hp']): ?>
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-telephone-fill text-success me-1"></i><?= html_escape($l['no_hp']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- JUMLAH PAKET SAAT INI -->
                                <td class="text-center">
                                    <?php if ((int) $l['jml_paket'] > 0): ?>
                                        <span class="badge badge-soft-info">
                                            <i class="bi bi-collection-fill me-1"></i><?= (int) $l['jml_paket'] ?> paket
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-secondary">0 paket</span>
                                    <?php endif; ?>
                                </td>

                                <!-- AKSI ADMIN -->
                                <?php if ($is_admin): ?>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <a href="<?= base_url('backend/lokasi/edit/' . (int) $l['id']) ?>"
                                               class="btn btn-sm btn-light border text-dark fw-semibold"
                                               title="Edit Lokasi">
                                                <i class="bi bi-pencil-square text-warning me-1"></i>Edit
                                            </a>

                                            <?= form_open(
                                                'backend/lokasi/delete/' . (int) $l['id'],
                                                [
                                                    'class' => 'd-inline',
                                                    'onsubmit' => "return confirm('Hapus lokasi ini?');"
                                                ]
                                            ) ?>
                                                <button type="submit" class="btn btn-sm btn-light border text-danger fw-semibold" title="Hapus Lokasi">
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
                if (tr.querySelector('.empty-state')) return;
                tr.style.display = tr.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
