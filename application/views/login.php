<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card glass-card shadow-lg p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-1 text-primary fw-bold"><i class="bi bi-journal-bookmark-fill me-2"></i>Login</h3>
        <p class="text-center text-muted small mb-4">Sistem Peminjaman Paket Buku Kolektif</p>

        <?php if ($this->session->flashdata('message')): ?>
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <?= $this->session->flashdata('message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= form_open('login/index') ?>
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">MASUK</button>
        <?= form_close() ?>

        <div class="text-center mt-3 text-muted small">
            Role yang tersedia: <strong>Admin, Petugas</strong>
        </div>
    </div>
</div>
