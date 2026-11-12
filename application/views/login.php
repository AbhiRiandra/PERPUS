<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 1.5rem; background: #f8fafc;">
    <div class="clean-card p-4 p-sm-5" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <div class="brand-icon mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="bi bi-book"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">SI-PUSTAKA</h5>
            <p class="text-muted small mb-0">DIARPUS Kota Mataram</p>
        </div>

        <?php if ($this->session->flashdata('message')): ?>
            <div class="alert alert-danger border-0 small py-2 px-3 mb-3 d-flex align-items-center" role="alert" style="background: #fef2f2; color: #b91c1c;">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <div class="flex-grow-1"><?= $this->session->flashdata('message') ?></div>
            </div>
        <?php endif; ?>

        <?= form_open('login/index') ?>
            <div class="mb-3">
                <label for="username" class="form-label small fw-semibold text-secondary">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus autocomplete="username">
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label small fw-semibold text-secondary mb-0">Password</label>
                </div>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                    <button class="btn btn-light border text-muted" type="button" id="togglePassword" tabindex="-1">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                Masuk
            </button>
        <?= form_close() ?>

        <div class="text-center mt-4 pt-3 border-top text-muted small">
            Sistem Peminjaman Paket Buku Kolektif
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
            icon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }
});
</script>
