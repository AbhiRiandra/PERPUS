
<?php $is_admin = $this->session->userdata('role') === 'admin'; ?>

<div class="card glass-card shadow-sm border-0">

    <!-- HEADER -->
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <div>
            <h4 class="fw-bold text-primary mb-1">
                <i class="bi bi-collection me-2"></i>
                Paket Buku
            </h4>

            <p class="text-muted small mb-0">
                Setiap paket berisi ratusan judul dan dipinjamkan sebagai satu kesatuan.
            </p>
        </div>

        <?php if ($is_admin): ?>
            <a href="<?= base_url('backend/paket/tambah') ?>"
               class="btn btn-primary btn-sm px-3 py-2 rounded-3 shadow-sm hover-up">

                <i class="bi bi-plus-lg me-1"></i>
                Tambah Paket

            </a>
        <?php endif; ?>

    </div>


    <!-- BODY -->
    <div class="card-body mt-2">

        <!-- FILTER STATUS -->
        <div class="btn-group btn-group-sm mb-3" role="group">

            <?php
            $filter_status = [
                '' => 'Semua',
                'tersedia' => 'Tersedia',
                'dipinjam' => 'Dipinjam',
                'terlambat' => 'Terlambat'
            ];
            ?>

            <?php foreach ($filter_status as $v => $label): ?>

                <a href="<?= base_url('backend/paket' . ($v ? '?status=' . $v : '')) ?>"
                   class="btn btn-outline-primary <?= $status === $v ? 'active' : '' ?>">

                    <?= $label ?>

                </a>

            <?php endforeach; ?>

        </div>


        <!-- TABEL PAKET -->
        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th style="width:6%">Kode</th>
                        <th>Nama Paket</th>
                        <th class="text-center">Judul</th>
                        <th>Status / Posisi</th>
                        <th class="text-center" style="width:120px">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($paket)): ?>

                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">

                                <i class="bi bi-inbox-fill d-block mb-3 text-secondary"
                                   style="font-size:48px;"></i>

                                <p class="mb-0 fw-semibold">
                                    Tidak ada paket
                                </p>

                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($paket as $p): ?>

                            <tr>

                                <!-- KODE -->
                                <td>
                                    <span class="badge bg-dark fs-6">
                                        <?= html_escape($p['kode']) ?>
                                    </span>
                                </td>


                                <!-- NAMA PAKET -->
                                <td>

                                    <div class="fw-semibold">
                                        <?= html_escape($p['nama']) ?>
                                    </div>

                                    <div class="text-muted small">
                                        <?= html_escape(character_limiter((string) $p['deskripsi'], 60)) ?>
                                    </div>

                                </td>


                                <!-- JUMLAH JUDUL -->
                                <td class="text-center">

                                    <span class="badge bg-secondary">
                                        <?= (int) $p['jml_buku'] ?>
                                    </span>

                                </td>


                                <!-- STATUS DAN POSISI -->
                                <td>

                                    <?php if ($p['status'] === 'dipinjam'): ?>

                                        <div>
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                            <?= html_escape($p['nama_lokasi']) ?>
                                        </div>

                                        <div class="small">

                                            s/d <?= tgl_indo($p['tgl_jatuh_tempo']) ?>

                                            <?= badge_jatuh_tempo($p['tgl_jatuh_tempo']) ?>

                                        </div>

                                    <?php else: ?>

                                        <span class="badge bg-success">
                                            Tersedia di perpustakaan
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- AKSI -->
                                <td class="text-center">

                                    <a href="<?= base_url('backend/paket/detail/' . (int) $p['id']) ?>"
                                       class="btn btn-sm btn-outline-primary px-3">

                                        Detail

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
