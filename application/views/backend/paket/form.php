<?php $edit = !empty($paket); ?>

<div class="mb-3">
    <a href="<?= base_url($edit ? 'backend/paket/detail/' . (int) $paket['id'] : 'backend/paket') ?>" class="text-decoration-none text-muted small fw-medium d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke <?= $edit ? 'Detail Paket ' . html_escape($paket['kode']) : 'Paket Buku' ?></span>
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="clean-card">
            <div class="card-header bg-transparent border-bottom px-4 py-3">
                <h5 class="fw-bold text-dark mb-0"><?= $edit ? 'Edit Paket Buku' : 'Tambah Paket Buku Baru' ?></h5>
                <span class="text-muted small">
                    <?= $edit ? 'Perbarui informasi kode, nama, atau deskripsi paket.' : 'Buat paket koleksi baru untuk distribusi ke kelurahan.' ?>
                </span>
            </div>

            <div class="card-body p-4">
                <?= form_open('backend/paket/' . ($edit ? 'update/' . (int) $paket['id'] : 'simpan')) ?>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-medium small text-muted">Kode Paket <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control text-uppercase fw-bold" maxlength="10" placeholder="Contoh: A" required autofocus
                                   value="<?= set_value('kode', $edit ? $paket['kode'] : '') ?>">
                        </div>

                        <div class="col-sm-8">
                            <label class="form-label fw-medium small text-muted">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" maxlength="150" placeholder="Contoh: Paket A - Literasi Umum" required
                                   value="<?= set_value('nama', $edit ? $paket['nama'] : '') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium small text-muted">Deskripsi / Catatan Paket <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tuliskan keterangan tema atau catatan khusus paket..."><?= set_value('deskripsi', $edit ? $paket['deskripsi'] : '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="<?= base_url($edit ? 'backend/paket/detail/' . (int) $paket['id'] : 'backend/paket') ?>" class="btn btn-light border px-3">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <?= $edit ? 'Simpan Perubahan' : 'Buat Paket' ?>
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
