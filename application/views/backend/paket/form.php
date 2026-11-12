<?php $edit = !empty($paket); ?>

<div class="mb-3">
    <a href="<?= base_url($edit ? 'backend/paket/detail/' . (int) $paket['id'] : 'backend/paket') ?>" class="text-decoration-none text-secondary small fw-semibold d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke <?= $edit ? 'Detail Paket ' . html_escape($paket['kode']) : 'Daftar Paket Buku' ?></span>
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-primary" style="width:44px;height:44px;font-size:1.2rem;">
                    <i class="bi <?= $edit ? 'bi-pencil-square' : 'bi-collection-fill' ?>"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0"><?= $edit ? 'Perbarui Informasi Paket' : 'Buat Paket Buku Baru' ?></h5>
                    <p class="text-muted small mb-0">
                        <?= $edit ? 'Ubah kode identitas, nama, atau keterangan deskripsi paket.' : 'Tentukan kode unik dan tema paket sebelum memasukkan daftar judul buku.' ?>
                    </p>
                </div>
            </div>

            <div class="card-body p-4">
                <?= form_open('backend/paket/' . ($edit ? 'update/' . (int) $paket['id'] : 'simpan')) ?>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold small text-secondary">Kode Paket <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" name="kode" class="form-control text-uppercase fw-bold" maxlength="10" placeholder="A" required autofocus
                                       value="<?= set_value('kode', $edit ? $paket['kode'] : '') ?>">
                            </div>
                            <div class="form-text small">Contoh: A, B, C, D1 (Maks. 10 karakter)</div>
                        </div>

                        <div class="col-sm-8">
                            <label class="form-label fw-bold small text-secondary">Nama Paket <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-bookmark-star"></i></span>
                                <input type="text" name="nama" class="form-control" maxlength="150" placeholder="Contoh: Paket A — Literasi Umum & Keluarga" required
                                       value="<?= set_value('nama', $edit ? $paket['nama'] : '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">Deskripsi / Tema Paket <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tuliskan ringkasan tema buku, sasaran pembaca, atau catatan khusus paket..."><?= set_value('deskripsi', $edit ? $paket['deskripsi'] : '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="<?= base_url($edit ? 'backend/paket/detail/' . (int) $paket['id'] : 'backend/paket') ?>" class="btn btn-light border px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-1"></i><?= $edit ? 'Simpan Perubahan' : 'Buat Paket' ?>
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
