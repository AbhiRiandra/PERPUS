
<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="card glass-card shadow-sm border-0">

    <!-- HEADER -->
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">

        <div>
            <h4 class="fw-bold text-primary mb-1">
                <i class="bi bi-geo-alt me-2"></i>
                Lokasi / Kelurahan
            </h4>

            <p class="text-muted small mb-0">
                Tempat tujuan peminjaman paket buku.
            </p>
        </div>

        <?php if ($is_admin): ?>

            <a href="<?= base_url('backend/lokasi/tambah') ?>"
               class="btn btn-primary btn-sm px-3 py-2 rounded-3 shadow-sm hover-up">

                <i class="bi bi-plus-lg me-1"></i>
                Tambah Lokasi

            </a>

        <?php endif; ?>

    </div>


    <!-- BODY -->
    <div class="card-body mt-2">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th style="width:5%">No</th>
                        <th>Nama Lokasi</th>
                        <th>Penanggung Jawab</th>
                        <th class="text-center">Paket Saat Ini</th>

                        <?php if ($is_admin): ?>
                            <th class="text-center" style="width:180px">Aksi</th>
                        <?php endif; ?>

                    </tr>

                </thead>


                <tbody>

                    <?php if (empty($lokasi)): ?>

                        <tr>
                            <td colspan="<?= $is_admin ? 5 : 4 ?>"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-inbox-fill d-block mb-3 text-secondary"
                                   style="font-size:48px;"></i>

                                Belum ada lokasi

                            </td>
                        </tr>

                    <?php else: ?>

                        <?php $no = 1; ?>

                        <?php foreach ($lokasi as $l): ?>

                            <tr>

                                <!-- NOMOR -->
                                <td>
                                    <?= $no++ ?>
                                </td>


                                <!-- NAMA LOKASI -->
                                <td>

                                    <div class="fw-bold">

                                        <?= html_escape($l['nama_lokasi']) ?>

                                        <span class="badge bg-light text-dark border">
                                            <?= html_escape($jenis[$l['jenis']] ?? $l['jenis']) ?>
                                        </span>

                                    </div>

                                    <div class="text-muted small">
                                        <?= html_escape((string) $l['alamat']) ?>
                                    </div>

                                </td>


                                <!-- PENANGGUNG JAWAB -->
                                <td>

                                    <div>
                                        <?= $l['penanggung_jawab']
                                            ? html_escape($l['penanggung_jawab'])
                                            : '-' ?>
                                    </div>

                                    <div class="text-muted small">

                                        <?= $l['no_hp']
                                            ? '<i class="bi bi-telephone-fill me-1"></i>' . html_escape($l['no_hp'])
                                            : '' ?>

                                    </div>

                                </td>


                                <!-- JUMLAH PAKET -->
                                <td class="text-center">

                                    <span class="badge <?= $l['jml_paket'] > 0 ? 'bg-info' : 'bg-secondary' ?>">

                                        <?= (int) $l['jml_paket'] ?>

                                    </span>

                                </td>


                                <!-- AKSI -->
                                <?php if ($is_admin): ?>

                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-2">

                                            <!-- TOMBOL EDIT -->
                                            <a href="<?= base_url('backend/lokasi/edit/' . (int) $l['id']) ?>"
                                               class="btn btn-sm btn-outline-warning px-3">

                                                Edit

                                            </a>


                                            <!-- TOMBOL HAPUS -->
                                            <?= form_open(
                                                'backend/lokasi/delete/' . (int) $l['id'],
                                                [
                                                    'class' => 'd-inline',
                                                    'onsubmit' => "return confirm('Hapus lokasi ini?');"
                                                ]
                                            ) ?>

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger px-3">

                                                    Hapus

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
