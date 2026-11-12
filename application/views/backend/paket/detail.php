
<?php
$is_admin = $this->session->userdata('role') === 'admin';
$dipinjam = $paket['status'] === 'dipinjam';
?>

<!-- =====================================================
     RINGKASAN PAKET DAN POSISI
===================================================== -->

<div class="card glass-card shadow-sm border-0 mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <span class="badge bg-dark me-2">
                        <?= html_escape($paket['kode']) ?>
                    </span>
                    <?= html_escape($paket['nama']) ?>
                </h4>

                <p class="text-muted mb-0">
                    <?= $paket['deskripsi']
                        ? nl2br(html_escape($paket['deskripsi']))
                        : '-' ?>
                </p>

                <span class="badge bg-secondary mt-2">
                    <?= (int) $paket['jml_buku'] ?> judul buku
                </span>
            </div>

            <div class="d-flex gap-2">

                <a href="<?= base_url('backend/paket') ?>"
                   class="btn btn-sm btn-outline-secondary">
                    Kembali
                </a>

                <?php if ($is_admin): ?>

                    <a href="<?= base_url('backend/paket/edit/' . $paket['id']) ?>"
                       class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>

                    <?= form_open(
                        'backend/paket/delete/' . (int) $paket['id'],
                        [
                            'class' => 'd-inline',
                            'onsubmit' => "return confirm('Hapus paket ini beserta seluruh daftar bukunya?');"
                        ]
                    ) ?>

                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>

                    <?= form_close() ?>

                <?php endif; ?>

            </div>
        </div>

        <hr>

        <?php if ($dipinjam && $aktif): ?>

            <!-- POSISI PAKET SEDANG DIPINJAM -->

            <div class="row g-3 align-items-center">

                <div class="col-md-7">

                    <div class="small text-muted">
                        Posisi saat ini
                    </div>

                    <div class="fs-5 fw-bold">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        <?= html_escape($paket['nama_lokasi']) ?>
                    </div>

                    <?php if ($paket['penanggung_jawab']): ?>
                        <div class="small text-muted">
                            PJ: <?= html_escape($paket['penanggung_jawab']) ?>

                            <?= $paket['no_hp_lokasi']
                                ? '(' . html_escape($paket['no_hp_lokasi']) . ')'
                                : '' ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-2">
                        Dipinjam
                        <strong><?= tgl_indo($aktif['tgl_pinjam']) ?></strong>
                        &rarr;

                        jatuh tempo
                        <strong><?= tgl_indo($aktif['tgl_jatuh_tempo']) ?></strong>

                        <?= badge_jatuh_tempo($aktif['tgl_jatuh_tempo']) ?>
                    </div>

                </div>

                <div class="col-md-5">

                    <div class="d-grid gap-2">

                        <a href="<?= base_url('backend/peminjaman/oper/' . $paket['id']) ?>"
                           class="btn btn-warning">
                            <i class="bi bi-arrow-left-right me-1"></i>
                            Oper ke lokasi lain
                        </a>

                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalTarik">

                            <i class="bi bi-box-arrow-in-down-left me-1"></i>
                            Tarik ke perpustakaan

                        </button>

                        <button type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPerpanjang">

                            <i class="bi bi-calendar-plus me-1"></i>
                            Perpanjang

                        </button>

                    </div>

                </div>

            </div>

        <?php else: ?>

            <!-- POSISI PAKET TERSEDIA -->

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <div class="small text-muted">
                        Posisi saat ini
                    </div>

                    <div class="fs-5 fw-bold text-success">
                        <i class="bi bi-building me-1"></i>
                        Di perpustakaan (tersedia)
                    </div>
                </div>

                <a href="<?= base_url('backend/peminjaman/pinjam/' . $paket['id']) ?>"
                   class="btn btn-success">

                    <i class="bi bi-box-arrow-up-right me-1"></i>
                    Pinjamkan ke lokasi

                </a>

            </div>

        <?php endif; ?>

    </div>
</div>


<!-- =====================================================
     RIWAYAT PERJALANAN PAKET
===================================================== -->

<div class="card glass-card shadow-sm border-0 mb-4">

    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">

        <h5 class="fw-bold text-primary mb-0">
            <i class="bi bi-clock-history me-2"></i>
            Riwayat Perjalanan Paket
        </h5>

    </div>

    <div class="card-body">

        <?php if (empty($riwayat)): ?>

            <p class="text-muted mb-0">
                Paket ini belum pernah dipinjamkan.
            </p>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-sm align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Lokasi</th>
                            <th>Masuk</th>
                            <th>Jatuh Tempo</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($riwayat as $r): ?>

                            <tr>

                                <td class="fw-semibold">
                                    <?= html_escape($r['nama_lokasi']) ?>

                                    <?php if ($r['jenis'] === 'oper'): ?>
                                        <span class="badge bg-warning text-dark">
                                            oper
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= tgl_indo($r['tgl_pinjam']) ?>
                                </td>

                                <td>
                                    <?= tgl_indo($r['tgl_jatuh_tempo']) ?>
                                </td>

                                <td>
                                    <?= tgl_indo($r['tgl_kembali']) ?>
                                </td>

                                <td>

                                    <?php if ($r['status'] === 'berjalan'): ?>

                                        <?= badge_jatuh_tempo($r['tgl_jatuh_tempo']) ?>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            Selesai
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td class="small text-muted">
                                    <?= html_escape((string) $r['catatan']) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>
</div>


<!-- =====================================================
     DAFTAR BUKU
===================================================== -->

<div class="card glass-card shadow-sm border-0">

    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <h5 class="fw-bold text-primary mb-0">
            <i class="bi bi-book me-2"></i>
            Daftar Judul (<?= count($buku) ?>)
        </h5>

        <?php if ($is_admin): ?>

            <div class="d-flex gap-2">

                <button type="button"
                        class="btn btn-sm btn-outline-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalImport">

                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                    Impor CSV

                </button>

                <button type="button"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#formTambahBuku">

                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Buku

                </button>

            </div>

        <?php endif; ?>

    </div>

    <div class="card-body">

        <?php if ($is_admin): ?>

            <!-- FORM TAMBAH BUKU -->

            <div class="collapse mb-3" id="formTambahBuku">

                <?= form_open(
                    'backend/buku/simpan/' . (int) $paket['id'],
                    ['class' => 'row g-2 p-3 bg-light rounded']
                ) ?>

                    <div class="col-md-12">
                        <input name="judul"
                               class="form-control"
                               placeholder="Judul buku *"
                               required
                               maxlength="255">
                    </div>

                    <div class="col-md-4">
                        <input name="penulis"
                               class="form-control"
                               placeholder="Penulis"
                               maxlength="150">
                    </div>

                    <div class="col-md-4">
                        <input name="penerbit"
                               class="form-control"
                               placeholder="Penerbit"
                               maxlength="150">
                    </div>

                    <div class="col-md-2">
                        <input name="tahun"
                               class="form-control"
                               placeholder="Tahun"
                               maxlength="4">
                    </div>

                    <div class="col-md-2">
                        <input name="kategori"
                               class="form-control"
                               placeholder="Kategori"
                               maxlength="100">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Simpan Buku
                        </button>
                    </div>

                <?= form_close() ?>

            </div>

        <?php endif; ?>


        <!-- PENCARIAN BUKU -->

        <input type="search"
               id="cariBuku"
               class="form-control form-control-sm mb-2"
               placeholder="Cari judul / penulis / kategori...">


        <!-- TABEL BUKU -->

        <div class="table-responsive scroll-y">

            <table class="table table-sm table-hover align-middle mb-0"
                   id="tabelBuku">

                <thead class="table-light">

                    <tr>
                        <th style="width:5%">#</th>
                        <th>Judul</th>
                        <th>Pengarang
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Kategori</th>

                        <?php if ($is_admin): ?>
                            <th></th>
                        <?php endif; ?>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($buku)): ?>

                        <tr>
                            <td colspan="<?= $is_admin ? 7 : 6 ?>"
                                class="text-center text-muted py-4">

                                Belum ada buku di paket ini.
                                Tambahkan satu per satu atau impor CSV.

                            </td>
                        </tr>

                    <?php else: ?>

                        <?php $no = 1; ?>

                        <?php foreach ($buku as $b): ?>

                            <tr>

                                <td>
                                    <?= $no++ ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= html_escape($b['judul']) ?>
                                </td>

                                <td>
                                    <?= html_escape((string) $b['penulis']) ?>
                                </td>

                                <td>
                                    <?= html_escape((string) $b['penerbit']) ?>
                                </td>

                                <td>
                                    <?= html_escape((string) $b['tahun']) ?>
                                </td>

                                <td>
                                    <?= html_escape((string) $b['kategori']) ?>
                                </td>

                                <?php if ($is_admin): ?>

                                    <td class="text-end">

                                        <?= form_open(
                                            'backend/buku/delete/' . (int) $b['id'],
                                            [
                                                'class' => 'd-inline',
                                                'onsubmit' => "return confirm('Hapus buku ini dari paket?');"
                                            ]
                                        ) ?>

                                            <button type="submit"
                                                    class="btn btn-sm btn-link text-danger p-0"
                                                    title="Hapus">

                                                <i class="bi bi-x-circle"></i>

                                            </button>

                                        <?= form_close() ?>

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


<!-- =====================================================
     MODAL TARIK PAKET
     DILETAKKAN DI LUAR GLASS-CARD
===================================================== -->

<?php if ($dipinjam && $aktif): ?>

<div class="modal fade"
     id="modalTarik"
     tabindex="-1"
     aria-labelledby="modalTarikLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <?= form_open(
                'backend/peminjaman/proses_tarik/' . (int) $paket['id']
            ) ?>

                <div class="modal-header">

                    <h5 class="modal-title" id="modalTarikLabel">
                        Tarik Paket <?= html_escape($paket['kode']) ?>
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <p class="small text-muted">
                        Paket kembali ke perpustakaan dan berstatus
                        <strong>tersedia</strong>.
                        Riwayat otomatis ditutup.
                    </p>

                    <label class="form-label fw-semibold">
                        Tanggal ditarik
                    </label>

                    <input type="date"
                           name="tanggal"
                           class="form-control mb-3"
                           value="<?= date('Y-m-d') ?>"
                           required>

                    <label class="form-label fw-semibold">
                        Catatan (opsional)
                    </label>

                    <textarea name="catatan"
                              rows="2"
                              class="form-control"
                              placeholder="Kondisi buku, jumlah kurang, dll."></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-box-arrow-in-down-left me-1"></i>
                        Tarik Paket
                    </button>

                </div>

            <?= form_close() ?>

        </div>

    </div>

</div>


<!-- =====================================================
     MODAL PERPANJANG PEMINJAMAN
===================================================== -->

<div class="modal fade"
     id="modalPerpanjang"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <?= form_open(
                'backend/peminjaman/proses_perpanjang/' . (int) $paket['id']
            ) ?>

                <div class="modal-header">

                    <h5 class="modal-title">
                        Perpanjang Peminjaman
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <label class="form-label fw-semibold">
                        Jatuh tempo baru
                    </label>

                    <input type="date"
                           name="tgl_jatuh_tempo"
                           class="form-control"
                           min="<?= date(
                               'Y-m-d',
                               strtotime($aktif['tgl_jatuh_tempo'] . ' +1 day')
                           ) ?>"
                           value="<?= date(
                               'Y-m-d',
                               strtotime($aktif['tgl_jatuh_tempo'] . ' +30 days')
                           ) ?>"
                           required>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            <?= form_close() ?>

        </div>

    </div>

</div>

<?php endif; ?>


<!-- =====================================================
     MODAL IMPOR CSV
===================================================== -->

<?php if ($is_admin): ?>

<div class="modal fade"
     id="modalImport"
     tabindex="-1"
     aria-labelledby="modalImportLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <?= form_open_multipart(
                'backend/buku/import/' . (int) $paket['id']
            ) ?>

                <div class="modal-header">

                    <h5 class="modal-title" id="modalImportLabel">
                        Impor Judul dari CSV
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <p class="small text-muted mb-2">

                        Kolom berurutan:
                        <code>judul, penulis, penerbit, tahun, kategori</code>

                        (hanya judul yang wajib).

                        Dari Excel:
                        <em>Save As &rarr; CSV</em>.

                    </p>

                    <a href="<?= base_url('backend/buku/template') ?>"
                       class="small">

                        <i class="bi bi-download"></i>
                        Unduh contoh format

                    </a>

                    <input type="file"
                           name="file_csv"
                           accept=".csv"
                           class="form-control my-3"
                           required>

                    <div class="form-check">

                        <input class="form-check-input"
                               type="radio"
                               name="mode"
                               value="tambah"
                               id="m1"
                               checked>

                        <label class="form-check-label" for="m1">
                            Tambahkan ke daftar yang ada
                        </label>

                    </div>

                    <div class="form-check">

                        <input class="form-check-input"
                               type="radio"
                               name="mode"
                               value="ganti"
                               id="m2">

                        <label class="form-check-label" for="m2">
                            Ganti seluruh daftar (hapus yang lama)
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i>
                        Impor
                    </button>

                </div>

            <?= form_close() ?>

        </div>

    </div>

</div>

<?php endif; ?>


<!-- =====================================================
     JAVASCRIPT PENCARIAN BUKU
===================================================== -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    var cariBuku = document.getElementById('cariBuku');

    if (cariBuku) {

        cariBuku.addEventListener('input', function () {

            var q = this.value.toLowerCase();

            document.querySelectorAll('#tabelBuku tbody tr').forEach(function (tr) {

                tr.style.display =
                    tr.textContent.toLowerCase().indexOf(q) > -1
                    ? ''
                    : 'none';

            });

        });

    }

});
</script>
