<?php
$menu = [
    'dashboard'  => ['backend/dashboard',  'bi-grid-1x2-fill',      'Dashboard',          'Ringkasan & Statistik'],
    'paket'      => ['backend/paket',      'bi-collection-fill',    'Paket Buku',         'Koleksi & Distribusi'],
    'lokasi'     => ['backend/lokasi',     'bi-geo-alt-fill',       'Lokasi / Kelurahan', 'Titik Layanan Baca'],
    'peminjaman' => ['backend/peminjaman', 'bi-clock-history',      'Riwayat Sirkulasi',  'Log Pinjam & Oper'],
];

$nama_user = (string) $this->session->userdata('nama');
$username  = (string) $this->session->userdata('username');
$role_user = (string) $this->session->userdata('role');
$inisial   = strtoupper(substr(trim($nama_user ?: 'U'), 0, 1));
$hari_indo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bln_indo  = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$tgl_hari_ini = $hari_indo[(int) date('w')] . ', ' . date('j') . ' ' . $bln_indo[(int) date('n')] . ' ' . date('Y');
?>

<div class="app-layout">

    <!-- =========================================================
         SIDEBAR DESKTOP (>= 992px)
    ========================================================= -->
    <aside class="app-sidebar">
        <a href="<?= base_url('backend/dashboard') ?>" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div class="sidebar-brand-text">
                <span class="sidebar-brand-title">DIARPUS MATARAM</span>
                <span class="sidebar-brand-sub">Sirkulasi Paket Buku</span>
            </div>
        </a>

        <div class="sidebar-nav-wrap">
            <div class="sidebar-section-label">Menu Navigasi</div>
            <ul class="sidebar-nav">
                <?php foreach ($menu as $key => $m): ?>
                    <li>
                        <a href="<?= base_url($m[0]) ?>" class="sidebar-link <?= $active === $key ? 'active' : '' ?>">
                            <i class="bi <?= $m[1] ?>"></i>
                            <span><?= $m[2] ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if ($role_user === 'admin'): ?>
                <div class="sidebar-section-label mt-4">Aksi Cepat</div>
                <ul class="sidebar-nav">
                    <li>
                        <a href="<?= base_url('backend/paket/tambah') ?>" class="sidebar-link">
                            <i class="bi bi-plus-circle-dotted"></i>
                            <span>Buat Paket Baru</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('backend/lokasi/tambah') ?>" class="sidebar-link">
                            <i class="bi bi-pin-map"></i>
                            <span>Tambah Lokasi</span>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user-card">
                <div class="sidebar-user-avatar"><?= html_escape($inisial) ?></div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?= html_escape($nama_user) ?></div>
                    <div class="sidebar-user-meta">
                        <span>@<?= html_escape($username) ?></span>
                        <span>&bull;</span>
                        <span class="text-info fw-semibold text-uppercase"><?= html_escape($role_user) ?></span>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('login/logout') ?>" class="btn-logout-sidebar" onclick="return confirm('Keluar dari sesi sistem?');">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar Sistem</span>
            </a>
        </div>
    </aside>

    <!-- =========================================================
         OFFCANVAS SIDEBAR MOBILE & TABLET (< 992px)
    ========================================================= -->
    <div class="offcanvas offcanvas-start offcanvas-sidebar d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25 py-3 px-3">
            <a href="<?= base_url('backend/dashboard') ?>" class="d-flex align-items-center gap-2 text-decoration-none text-white" id="mobileSidebarLabel">
                <div class="sidebar-brand-icon" style="width:38px;height:38px;font-size:1.1rem;">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="sidebar-brand-text">
                    <span class="sidebar-brand-title">DIARPUS MATARAM</span>
                    <span class="sidebar-brand-sub">Sirkulasi Paket Buku</span>
                </div>
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column p-0">
            <div class="sidebar-nav-wrap">
                <div class="sidebar-section-label">Menu Navigasi</div>
                <ul class="sidebar-nav">
                    <?php foreach ($menu as $key => $m): ?>
                        <li>
                            <a href="<?= base_url($m[0]) ?>" class="sidebar-link <?= $active === $key ? 'active' : '' ?>">
                                <i class="bi <?= $m[1] ?>"></i>
                                <span><?= $m[2] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($role_user === 'admin'): ?>
                    <div class="sidebar-section-label mt-4">Aksi Cepat</div>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="<?= base_url('backend/paket/tambah') ?>" class="sidebar-link">
                                <i class="bi bi-plus-circle-dotted"></i>
                                <span>Buat Paket Baru</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('backend/lokasi/tambah') ?>" class="sidebar-link">
                                <i class="bi bi-pin-map"></i>
                                <span>Tambah Lokasi</span>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar"><?= html_escape($inisial) ?></div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?= html_escape($nama_user) ?></div>
                        <div class="sidebar-user-meta">
                            <span>@<?= html_escape($username) ?></span>
                            <span>&bull;</span>
                            <span class="text-info fw-semibold text-uppercase"><?= html_escape($role_user) ?></span>
                        </div>
                    </div>
                </div>
                <a href="<?= base_url('login/logout') ?>" class="btn-logout-sidebar" onclick="return confirm('Keluar dari sesi sistem?');">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar Sistem</span>
                </a>
            </div>
        </div>
    </div>

    <!-- =========================================================
         MAIN CONTENT WRAPPER
    ========================================================= -->
    <div class="app-main">

        <!-- TOPBAR HEADER -->
        <header class="app-topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="topbar-toggle d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-label="Buka Menu">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h1 class="fs-5 fw-bold mb-0 text-dark lh-sm">
                        <?= isset($title) ? html_escape($title) : (isset($menu[$active][2]) ? $menu[$active][2] : 'Dashboard') ?>
                    </h1>
                    <div class="text-muted small d-none d-sm-block" style="font-size: 0.78rem;">
                        <?= isset($menu[$active][3]) ? $menu[$active][3] : 'Sistem Informasi Peminjaman Paket Buku Kolektif' ?>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border text-secondary" style="font-size: 0.8rem;">
                    <i class="bi bi-calendar3 text-primary"></i>
                    <span class="fw-medium"><?= $tgl_hari_ini ?></span>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light border d-flex align-items-center gap-2 py-1 px-2 px-sm-3 rounded-pill shadow-xs" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold" style="width:28px;height:28px;font-size:0.78rem;">
                            <?= html_escape($inisial) ?>
                        </span>
                        <span class="d-none d-sm-inline fw-semibold small text-dark"><?= html_escape($nama_user) ?></span>
                        <span class="badge <?= $role_user === 'admin' ? 'badge-soft-primary' : 'badge-soft-success' ?> d-none d-md-inline text-uppercase" style="font-size:0.68rem;">
                            <?= html_escape($role_user) ?>
                        </span>
                        <i class="bi bi-chevron-down text-muted small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2 mt-2" style="min-width: 210px;">
                        <li class="px-3 py-2 border-bottom mb-1">
                            <div class="fw-bold text-dark small"><?= html_escape($nama_user) ?></div>
                            <div class="text-muted" style="font-size: 0.75rem;">@<?= html_escape($username) ?> &bull; <?= strtoupper(html_escape($role_user)) ?></div>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 small" href="<?= base_url('backend/dashboard') ?>">
                                <i class="bi bi-grid-1x2 me-2 text-primary"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 small" href="<?= base_url('backend/paket') ?>">
                                <i class="bi bi-collection me-2 text-primary"></i>Data Paket Buku
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 small text-danger fw-semibold" href="<?= base_url('login/logout') ?>" onclick="return confirm('Keluar dari sesi sistem?');">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="app-content">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-xs border-0 rounded-4 d-flex align-items-center mb-4" role="alert" style="background-color:#ecfdf5;color:#065f46;border-left:4px solid #10b981 !important;">
                    <i class="bi bi-check-circle-fill fs-5 me-3 text-success"></i>
                    <div class="flex-grow-1"><?= $this->session->flashdata('success') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-xs border-0 rounded-4 d-flex align-items-center mb-4" role="alert" style="background-color:#fef2f2;color:#991b1b;border-left:4px solid #ef4444 !important;">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-3 text-danger"></i>
                    <div class="flex-grow-1"><?= $this->session->flashdata('error') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-xs border-0 rounded-4 d-flex align-items-start mb-4" role="alert" style="background-color:#fef2f2;color:#991b1b;border-left:4px solid #ef4444 !important;">
                    <i class="bi bi-exclamation-octagon-fill fs-5 me-3 text-danger mt-1"></i>
                    <div class="flex-grow-1"><?= validation_errors('<div>', '</div>') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
