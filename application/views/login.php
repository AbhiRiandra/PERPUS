<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background:
            radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.14) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.14) 0px, transparent 50%),
            linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    .auth-card {
        width: 100%;
        max-width: 960px;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.10), 0 4px 12px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    .auth-banner {
        background: linear-gradient(145deg, #0f172a 0%, #1e3a8a 55%, #2563eb 100%);
        color: #ffffff;
        padding: 3rem 2.75rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .auth-banner::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, transparent 70%);
        top: -80px;
        right: -80px;
        pointer-events: none;
    }
    .auth-banner::after {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
        bottom: -90px;
        left: -70px;
        pointer-events: none;
    }
    .auth-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        margin-bottom: 1rem;
    }
    .auth-feature-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.12);
        color: #60a5fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .auth-form-side {
        padding: 3rem 2.75rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    @media (max-width: 767.98px) {
        .auth-wrapper {
            padding: 1rem;
        }
        .auth-form-side {
            padding: 2rem 1.5rem;
        }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="row g-0">

            <!-- SISI KIRI: BRANDING & FITUR (Tampil di Tablet/Desktop) -->
            <div class="col-lg-6 d-none d-lg-flex auth-banner">
                <div class="position-relative z-1">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="bi bi-journal-bookmark-fill text-info"></i>
                        <span class="small fw-bold tracking-wide">DIARPUS KOTA MATARAM</span>
                    </div>
                    <h2 class="fw-extrabold mb-3 lh-sm" style="font-weight: 800;">
                        Sistem Peminjaman Paket Buku Kolektif
                    </h2>
                    <p class="text-white-50 mb-4" style="font-size: 0.93rem;">
                        Platform manajemen distribusi dan sirkulasi paket buku bacaan masyarakat untuk kelurahan, desa, sekolah, dan taman baca.
                    </p>

                    <div class="mt-4 pt-2">
                        <div class="auth-feature-item">
                            <div class="auth-feature-icon"><i class="bi bi-collection-fill"></i></div>
                            <div>
                                <div class="fw-bold small">Manajemen Paket Kolektif</div>
                                <div class="text-white-50" style="font-size: 0.8rem;">Kelola ratusan judul buku dalam setiap paket siap pinjam &amp; impor CSV cepat.</div>
                            </div>
                        </div>
                        <div class="auth-feature-item">
                            <div class="auth-feature-icon"><i class="bi bi-arrow-left-right"></i></div>
                            <div>
                                <div class="fw-bold small">Sirkulasi Pinjam, Oper &amp; Tarik</div>
                                <div class="text-white-50" style="font-size: 0.8rem;">Pantau perpindahan paket antar kelurahan secara akurat dalam satu transaksi.</div>
                            </div>
                        </div>
                        <div class="auth-feature-item mb-0">
                            <div class="auth-feature-icon"><i class="bi bi-bell-fill"></i></div>
                            <div>
                                <div class="fw-bold small">Monitoring Jatuh Tempo</div>
                                <div class="text-white-50" style="font-size: 0.8rem;">Indikator otomatis untuk paket buku yang mendekati atau melewati batas waktu.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="position-relative z-1 pt-4 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center small text-white-50">
                    <span>Dinas Kearsipan &amp; Perpustakaan</span>
                    <span class="badge bg-white bg-opacity-10 text-white">SI-PUSTAKA</span>
                </div>
            </div>

            <!-- SISI KANAN: FORM LOGIN -->
            <div class="col-lg-6 auth-form-side">
                <!-- Header Mobile -->
                <div class="d-lg-none text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-4 bg-primary text-white shadow-sm mb-2" style="width: 52px; height: 52px; font-size: 1.5rem;">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">DIARPUS KOTA MATARAM</h5>
                    <p class="text-muted small mb-0">Sistem Peminjaman Paket Buku Kolektif</p>
                </div>

                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1">Selamat Datang</h3>
                    <p class="text-muted small mb-0">Silakan masuk menggunakan akun petugas atau administrator Anda.</p>
                </div>

                <?php if ($this->session->flashdata('message')): ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 d-flex align-items-center py-2 px-3 mb-4" role="alert" style="background-color:#fef2f2;color:#991b1b;">
                        <i class="bi bi-exclamation-circle-fill me-2 text-danger"></i>
                        <div class="small flex-grow-1"><?= $this->session->flashdata('message') ?></div>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?= form_open('login/index') ?>
                    <div class="mb-3">
                        <label for="username" class="form-label small fw-bold text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-secondary"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="username" name="username" placeholder="Masukkan username Anda" required autofocus autocomplete="username">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label small fw-bold text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-secondary"></i></span>
                            <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
                            <button class="input-group-text bg-light border-start-0 text-secondary" type="button" id="togglePassword" tabindex="-1" title="Tampilkan/Sembunyikan Password">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <span>MASUK KE SISTEM</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                <?= form_close() ?>

                <div class="mt-4 pt-3 border-top text-center">
                    <div class="small text-muted mb-2">Hak akses yang didukung:</div>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge badge-soft-primary"><i class="bi bi-shield-lock me-1"></i>Administrator</span>
                        <span class="badge badge-soft-success"><i class="bi bi-person-check me-1"></i>Petugas Sirkulasi</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('togglePassword');
    var input = document.getElementById('password');
    var icon = document.getElementById('togglePasswordIcon');
    if (btn && input && icon) {
        btn.addEventListener('click', function () {
            var isPass = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPass ? 'text' : 'password');
            icon.className = isPass ? 'bi bi-eye-slash text-primary' : 'bi bi-eye';
        });
    }
});
</script>
