<?php $oper = ($mode === 'oper'); ?>

<div class="mb-3">
    <a href="<?= base_url('backend/paket/detail/' . (int) $paket['id']) ?>" class="text-decoration-none text-secondary small fw-semibold d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Detail Paket <?= html_escape($paket['kode']) ?></span>
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex align-items-center gap-3">
                <div class="stat-icon <?= $oper ? 'stat-icon-warning' : 'stat-icon-success' ?>" style="width:48px;height:48px;font-size:1.3rem;">
                    <i class="bi <?= $oper ? 'bi-arrow-left-right' : 'bi-box-arrow-up-right' ?>"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">
                        <?= $oper ? 'Oper Paket ke Lokasi Lain' : 'Pinjamkan Paket Buku' ?>
                    </h5>
                    <p class="text-muted small mb-0">
                        Paket <strong><?= html_escape($paket['kode']) ?></strong> &mdash; <?= html_escape($paket['nama']) ?> (<?= (int) $paket['jml_buku'] ?> judul buku)
                    </p>
                </div>
            </div>

            <div class="card-body p-4">
                <?php if ($oper): ?>
                    <div class="p-3 rounded-4 border mb-4 d-flex align-items-start gap-3" style="background: #fffbeb; border-color: #fde68a !important; color: #92400e;">
                        <i class="bi bi-info-circle-fill fs-5 text-warning mt-1"></i>
                        <div class="small">
                            <strong>Informasi Oper Lokasi:</strong><br>
                            Posisi paket saat ini berada di <strong><?= html_escape($paket['nama_lokasi']) ?></strong>.
                            Saat proses oper disimpan, riwayat di lokasi lama akan <strong>ditutup otomatis</strong> dan riwayat peminjaman baru langsung dibuka di lokasi tujuan.
                        </div>
                    </div>
                <?php endif; ?>

                <?= form_open('backend/peminjaman/proses_' . ($oper ? 'oper' : 'pinjam') . '/' . (int) $paket['id']) ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">
                            Lokasi / Kelurahan Tujuan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <select name="id_lokasi" class="form-select" required>
                                <option value="">-- Pilih Lokasi Tujuan Distribusi --</option>
                                <?php foreach ($lokasi as $l): ?>
                                    <option value="<?= (int) $l['id'] ?>" <?= set_select('id_lokasi', $l['id']) ?>>
                                        <?= html_escape($l['nama_lokasi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (empty($lokasi)): ?>
                            <div class="form-text text-danger mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>Belum ada lokasi tujuan yang tersedia. Silakan tambahkan terlebih dahulu di menu <strong>Lokasi / Kelurahan</strong>.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">
                                Tanggal <?= $oper ? 'Oper' : 'Pinjam' ?> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="tanggal" id="inputTanggalPinjam" class="form-control" required value="<?= set_value('tanggal', date('Y-m-d')) ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">
                                Durasi Peminjaman (Hari) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hourglass-split"></i></span>
                                <input type="number" name="durasi" id="durasi" class="form-control" min="1" max="730" required value="<?= set_value('durasi', 30) ?>">
                                <span class="input-group-text">hari</span>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                <?php foreach ([30 => '1 Bulan (30 hr)', 60 => '2 Bulan (60 hr)', 90 => '3 Bulan (90 hr)', 180 => '6 Bulan (180 hr)'] as $h => $t): ?>
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 0.75rem;" onclick="setDurasiPinjam(<?= $h ?>)">
                                        <?= $t ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light border small d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary"><i class="bi bi-calendar-check text-primary me-1"></i>Estimasi Tanggal Jatuh Tempo:</span>
                        <strong class="text-dark" id="estimasiJatuhTempo">-</strong>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">
                            Catatan Sirkulasi <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <textarea name="catatan" rows="2" class="form-control" placeholder="Nomor surat pengantar, nama penerima, kondisi paket, dll."><?= set_value('catatan') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="<?= base_url('backend/paket/detail/' . (int) $paket['id']) ?>" class="btn btn-light border px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn <?= $oper ? 'btn-warning fw-bold' : 'btn-success fw-bold' ?> px-4">
                            <i class="bi bi-check2-circle me-1"></i><?= $oper ? 'Simpan Oper Paket' : 'Simpan Peminjaman' ?>
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
