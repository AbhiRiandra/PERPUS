<?php
$menu = [
    'dashboard'  => ['backend/dashboard',  'bi-grid',          'Dashboard'],
    'paket'      => ['backend/paket',      'bi-collection',    'Paket Buku'],
    'lokasi'     => ['backend/lokasi',     'bi-geo-alt',       'Lokasi / Kelurahan'],
    'peminjaman' => ['backend/peminjaman', 'bi-clock-history', 'Riwayat Peminjaman'],
];

$nama_user = (string) $this->session->userdata('nama');
$role_user = (string) $this->session->userdata('role');
?>

<div class="app-shell">

    <!-- DESKTOP SIDEBAR -->
    <aside class="app-sidebar">
        <a href="<?= base_url('backend/dashboard') ?>" class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-book"></i>
            </div>
            <div>
                <span class="fw-bold d-block lh-1 text-dark" style="font-size: 0.95rem;">SI-PUSTAKA</span>
                <small class="text-muted" style="font-size: 0.72rem;">DIARPUS Kota Mataram</small>
            </div>
        </a>

        <ul class="sidebar-menu">
            <?php foreach ($menu as $key => $m): ?>
                <li>
                    <a href="<?= base_url($m[0]) ?>" class="<?= $active === $key ? 'active' : '' ?>">
                        <i class="bi <?= $m[1] ?>"></i>
                        <span><?= $m[2] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="sidebar-user">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <div class="rounded-circle bg-light border text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; flex-shrink: 0;">
                    <?= strtoupper(substr($nama_user ?: 'U', 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <div class="fw-semibold text-truncate small text-dark"><?= html_escape($nama_user) ?></div>
                    <div class="text-muted text-uppercase" style="font-size: 0.68rem;"><?= html_escape($role_user) ?></div>
                </div>
            </div>
            <a href="<?= base_url('login/logout') ?>" class="btn btn-sm btn-link text-danger p-1" title="Logout" onclick="return confirm('Keluar dari aplikasi?');">
                <i class="bi bi-box-arrow-right fs-6"></i>
            </a>
        </div>
    </aside>

    <!-- MOBILE OFFCANVAS -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" style="width: 250px;">
        <div class="offcanvas-header border-bottom py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-icon" style="width:30px;height:30px;font-size:0.95rem;">
                    <i class="bi bi-book"></i>
                </div>
                <span class="fw-bold small text-dark">SI-PUSTAKA</span>
            </div>
            <button type="button" class="btn-close text-reset btn-sm" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column">
            <ul class="sidebar-menu flex-grow-1">
                <?php foreach ($menu as $key => $m): ?>
                    <li>
                        <a href="<?= base_url($m[0]) ?>" class="<?= $active === $key ? 'active' : '' ?>">
                            <i class="bi <?= $m[1] ?>"></i>
                            <span><?= $m[2] ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="p-3 border-top">
                <a href="<?= base_url('login/logout') ?>" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Keluar dari aplikasi?');">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN WRAPPER -->
    <div class="app-main">
        <header class="app-topbar">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-light border d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                    <i class="bi bi-list fs-6"></i>
                </button>
                <h6 class="fw-bold mb-0 text-dark"><?= isset($title) ? html_escape($title) : 'Dashboard' ?></h6>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small d-none d-md-inline">Halo, <strong><?= html_escape($nama_user) ?></strong></span>
                <span class="badge badge-soft-secondary text-uppercase d-none d-sm-inline"><?= html_escape($role_user) ?></span>
                <a href="<?= base_url('login/logout') ?>" class="btn btn-sm btn-light border d-none d-sm-inline-flex align-items-center gap-1 text-secondary" onclick="return confirm('Keluar dari aplikasi?');">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </header>

        <main class="app-container">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 small py-2 px-3 mb-3 d-flex align-items-center" role="alert" style="background: #ecfdf5; color: #047857;">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div class="flex-grow-1"><?= $this->session->flashdata('success') ?></div>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 small py-2 px-3 mb-3 d-flex align-items-center" role="alert" style="background: #fef2f2; color: #b91c1c;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div class="flex-grow-1"><?= $this->session->flashdata('error') ?></div>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 small py-2 px-3 mb-3" role="alert" style="background: #fef2f2; color: #b91c1c;">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Terjadi kesalahan:</div>
                    <?= validation_errors('<div>', '</div>') ?>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
