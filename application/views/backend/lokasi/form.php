<?php $edit = !empty($lokasi); ?>

<div class="mb-3">
    <a href="<?= base_url('backend/lokasi') ?>" class="text-decoration-none text-muted small fw-medium d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Lokasi</span>
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="clean-card">
            <div class="card-header bg-transparent border-bottom px-4 py-3">
                <h5 class="fw-bold text-dark mb-0"><?= $edit ? 'Edit Data Lokasi' : 'Tambah Lokasi Baru' ?></h5>
                <span class="text-muted small">
                    <?= $edit ? 'Perbarui data kelurahan atau kontak penanggung jawab.' : 'Tambahkan titik kelurahan atau taman baca tujuan peminjaman.' ?>
                </span>
            </div>

            <div class="card-body p-4">
                <?= form_open('backend/lokasi/' . ($edit ? 'update/' . (int) $lokasi['id'] : 'simpan')) ?>
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-medium small text-muted">Nama Lokasi / Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lokasi" class="form-control" maxlength="150" required autofocus placeholder="Contoh: Kelurahan Dasan Agung"
                                   value="<?= set_value('nama_lokasi', $edit ? $lokasi['nama_lokasi'] : '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-medium small text-muted">Jenis Layanan <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select" required>
                                <?php foreach ($jenis as $k => $v): ?>
                                    <option value="<?= $k ?>" <?= set_select('jenis', $k, $edit && $lokasi['jenis'] === $k) ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small text-muted">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="form-control" placeholder="Nama jalan, lingkungan, atau keterangan lokasi..."><?= set_value('alamat', $edit ? $lokasi['alamat'] : '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium small text-muted">Nama Penanggung Jawab (PJ)</label>
                            <input type="text" name="penanggung_jawab" class="form-control" maxlength="100" placeholder="Nama petugas kelurahan"
                                   value="<?= set_value('penanggung_jawab', $edit ? $lokasi['penanggung_jawab'] : '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium small text-muted">Nomor Kontak / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" maxlength="20" placeholder="08xxxxxxxxxx"
                                   value="<?= set_value('no_hp', $edit ? $lokasi['no_hp'] : '') ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="<?= base_url('backend/lokasi') ?>" class="btn btn-light border px-3">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <?= $edit ? 'Simpan Perubahan' : 'Simpan Lokasi' ?>
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
