<?php $edit = !empty($lokasi); ?>
<div class="card glass-card shadow-sm border-0">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="fw-bold text-primary mb-0"><i class="bi bi-geo-alt me-2"></i> <?= $edit ? 'Edit' : 'Tambah' ?> Lokasi</h4>
    </div>
    <div class="card-body">
        <?= form_open('backend/lokasi/' . ($edit ? 'update/' . (int) $lokasi['id'] : 'simpan')) ?>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-semibold">Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" class="form-control" maxlength="150" required placeholder="Kelurahan ..."
                           value="<?= set_value('nama_lokasi', $edit ? $lokasi['nama_lokasi'] : '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <?php foreach ($jenis as $k => $v): ?>
                            <option value="<?= $k ?>" <?= set_select('jenis', $k, $edit && $lokasi['jenis'] === $k) ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" rows="2" class="form-control"><?= set_value('alamat', $edit ? $lokasi['alamat'] : '') ?></textarea>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-semibold">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" maxlength="100" value="<?= set_value('penanggung_jawab', $edit ? $lokasi['penanggung_jawab'] : '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" maxlength="20" value="<?= set_value('no_hp', $edit ? $lokasi['no_hp'] : '') ?>">
                </div>
            </div>
            <a href="<?= base_url('backend/lokasi') ?>" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan</button>
        <?= form_close() ?>
    </div>
</div>
