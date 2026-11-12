<?php $oper = ($mode === 'oper'); ?>
<div class="card glass-card shadow-sm border-0">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="fw-bold text-primary mb-1">
            <i class="bi <?= $oper ? 'bi-arrow-left-right' : 'bi-box-arrow-up-right' ?> me-2"></i>
            <?= $oper ? 'Oper' : 'Pinjamkan' ?> Paket <?= html_escape($paket['kode']) ?>
        </h4>
        <p class="text-muted mb-0"><?= html_escape($paket['nama']) ?> &middot; <?= (int) $paket['jml_buku'] ?> judul</p>
    </div>
    <div class="card-body">
        <?php if ($oper): ?>
            <div class="alert alert-warning py-2">
                Posisi sekarang: <strong><?= html_escape($paket['nama_lokasi']) ?></strong>.
                Peminjaman di lokasi ini akan <strong>ditutup otomatis</strong> pada tanggal oper, dan peminjaman baru dibuat di lokasi tujuan.
            </div>
        <?php endif; ?>

        <?= form_open('backend/peminjaman/proses_' . ($oper ? 'oper' : 'pinjam') . '/' . (int) $paket['id']) ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Lokasi tujuan</label>
                <select name="id_lokasi" class="form-select" required>
                    <option value="">-- pilih lokasi --</option>
                    <?php foreach ($lokasi as $l): ?>
                        <option value="<?= (int) $l['id'] ?>" <?= set_select('id_lokasi', $l['id']) ?>><?= html_escape($l['nama_lokasi']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($lokasi)): ?><div class="form-text text-danger">Belum ada lokasi. Tambahkan dulu di menu Lokasi.</div><?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal <?= $oper ? 'oper' : 'pinjam' ?></label>
                    <input type="date" name="tanggal" class="form-control" required value="<?= set_value('tanggal', date('Y-m-d')) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Lama pinjam (hari)</label>
                    <input type="number" name="durasi" id="durasi" class="form-control" min="1" max="730" required value="<?= set_value('durasi', 30) ?>">
                    <div class="mt-1">
                        <?php foreach ([30 => '1 bulan', 60 => '2 bulan', 90 => '3 bulan', 180 => '6 bulan'] as $h => $t): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-0" onclick="document.getElementById('durasi').value=<?= $h ?>"><?= $t ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan (opsional)</label>
                <textarea name="catatan" rows="2" class="form-control" placeholder="Nomor surat, penerima, dll."><?= set_value('catatan') ?></textarea>
            </div>
            <a href="<?= base_url('backend/paket/detail/' . $paket['id']) ?>" class="btn btn-light">Batal</a>
            <button type="submit" class="btn <?= $oper ? 'btn-warning' : 'btn-success' ?> px-4"><i class="bi bi-check2-circle me-1"></i> <?= $oper ? 'Oper Paket' : 'Pinjamkan' ?></button>
        <?= form_close() ?>
    </div>
</div>
