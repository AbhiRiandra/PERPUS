<?php $oper = ($mode === 'oper'); ?>

<div class="mb-3">
    <a href="<?= base_url('backend/paket/detail/' . (int) $paket['id']) ?>" class="text-decoration-none text-muted small fw-medium d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Detail Paket <?= html_escape($paket['kode']) ?></span>
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="clean-card">
            <div class="card-header bg-transparent border-bottom px-4 py-3">
                <h5 class="fw-bold text-dark mb-0">
                    <?= $oper ? 'Oper Paket ke Lokasi Lain' : 'Pinjamkan Paket Buku' ?>
                </h5>
                <span class="text-muted small">
                    Paket <strong><?= html_escape($paket['kode']) ?></strong> &mdash; <?= html_escape($paket['nama']) ?> (<?= (int) $paket['jml_buku'] ?> judul buku)
                </span>
            </div>

            <div class="card-body p-4">
                <?php if ($oper): ?>
                    <div class="p-3 rounded-3 border bg-light bg-opacity-50 mb-3 small text-muted">
                        Posisi saat ini di <strong><?= html_escape($paket['nama_lokasi']) ?></strong>. Transaksi peminjaman di lokasi lama akan otomatis ditutup dan dipindahkan ke lokasi baru.
                    </div>
                <?php endif; ?>

                <?= form_open('backend/peminjaman/proses_' . ($oper ? 'oper' : 'pinjam') . '/' . (int) $paket['id']) ?>
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-muted">
                            Lokasi / Kelurahan Tujuan <span class="text-danger">*</span>
                        </label>
                        <select name="id_lokasi" class="form-select" required>
                            <option value="">-- Pilih Lokasi Tujuan --</option>
                            <?php foreach ($lokasi as $l): ?>
                                <option value="<?= (int) $l['id'] ?>" <?= set_select('id_lokasi', $l['id']) ?>>
                                    <?= html_escape($l['nama_lokasi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($lokasi)): ?>
                            <div class="form-text text-danger mt-1">
                                Belum ada lokasi tujuan. Tambahkan terlebih dahulu di menu <strong>Lokasi / Kelurahan</strong>.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium small text-muted">
                                Tanggal <?= $oper ? 'Oper' : 'Pinjam' ?> <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="tanggal" id="inputTanggalPinjam" class="form-control" required value="<?= set_value('tanggal', date('Y-m-d')) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium small text-muted">
                                Durasi Peminjaman (Hari) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" name="durasi" id="durasi" class="form-control" min="1" max="730" required value="<?= set_value('durasi', 30) ?>">
                                <span class="input-group-text bg-light text-muted small">hari</span>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                <?php foreach ([30 => '30 hr', 60 => '60 hr', 90 => '90 hr', 180 => '180 hr'] as $h => $t): ?>
                                    <button type="button" class="btn btn-sm btn-light border py-0 px-2" style="font-size: 0.75rem;" onclick="setDurasiPinjam(<?= $h ?>)">
                                        <?= $t ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-2 px-3 rounded-2 bg-light border small d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Estimasi Tanggal Jatuh Tempo:</span>
                        <strong class="text-dark" id="estimasiJatuhTempo">-</strong>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium small text-muted">
                            Catatan Sirkulasi <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <textarea name="catatan" rows="2" class="form-control" placeholder="Nomor surat pengantar, nama penerima, atau keterangan lain..."><?= set_value('catatan') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="<?= base_url('backend/paket/detail/' . (int) $paket['id']) ?>" class="btn btn-light border px-3">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <?= $oper ? 'Simpan Oper Paket' : 'Simpan Peminjaman' ?>
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
function hitungJatuhTempo() {
    var tglEl = document.getElementById('inputTanggalPinjam');
    var durEl = document.getElementById('durasi');
    var outEl = document.getElementById('estimasiJatuhTempo');
    if (!tglEl || !durEl || !outEl || !tglEl.value) return;

    var hari = parseInt(durEl.value, 10) || 0;
    var parts = tglEl.value.split('-');
    if (parts.length !== 3 || hari <= 0) {
        outEl.textContent = '-';
        return;
    }
    var d = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
    d.setDate(d.getDate() + hari);
    var bln = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    outEl.textContent = d.getDate() + ' ' + bln[d.getMonth()] + ' ' + d.getFullYear();
}

function setDurasiPinjam(hari) {
    var durEl = document.getElementById('durasi');
    if (durEl) {
        durEl.value = hari;
        hitungJatuhTempo();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var tglEl = document.getElementById('inputTanggalPinjam');
    var durEl = document.getElementById('durasi');
    if (tglEl) tglEl.addEventListener('change', hitungJatuhTempo);
    if (durEl) durEl.addEventListener('input', hitungJatuhTempo);
    hitungJatuhTempo();
});
</script>
