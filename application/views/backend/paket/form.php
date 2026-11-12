<?php $edit = !empty($paket); ?>
<div class="card glass-card shadow-sm border-0">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="fw-bold text-primary mb-0"><i class="bi bi-collection me-2"></i> <?= $edit ? 'Edit' : 'Tambah' ?> Paket</h4>
    </div>
    <div class="card-body">
        <?= form_open('backend/paket/' . ($edit ? 'update/' . (int) $paket['id'] : 'simpan')) ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Kode Paket</label>
                    <input type="text" name="kode" class="form-control text-uppercase" maxlength="10" placeholder="A" required
                           value="<?= set_value('kode', $edit ? $paket['kode'] : '') ?>">
                    <div class="form-text">Contoh: A, B, C, D</div>
                </div>
                <div class="col-md-9 mb-3">
                    <label class="form-label fw-semibold">Nama Paket</label>
                    <input type="text" name="nama" class="form-control" maxlength="150" placeholder="Paket A - Umum" required
                           value="<?= set_value('nama', $edit ? $paket['nama'] : '') ?>">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Deskripsi (opsional)</label>
                <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tema / isi singkat paket"><?= set_value('deskripsi', $edit ? $paket['deskripsi'] : '') ?></textarea>
            </div>
            <a href="<?= base_url('backend/paket') ?>" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan</button>
        <?= form_close() ?>
    </div>
</div>
