<?php
$menu = [
    'dashboard'  => ['backend/dashboard',  'bi-speedometer2',    'Dashboard'],
    'paket'      => ['backend/paket',      'bi-collection',      'Paket Buku'],
    'lokasi'     => ['backend/lokasi',     'bi-geo-alt',         'Lokasi / Kelurahan'],
    'peminjaman' => ['backend/peminjaman', 'bi-clock-history',   'Riwayat Peminjaman'],
];
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card glass-card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;font-size:24px;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-1"><?= html_escape($this->session->userdata('nama')) ?></h5>
                    <p class="text-muted small mb-0">@<?= html_escape($this->session->userdata('username')) ?></p>
                    <span class="badge bg-success mt-2 px-3 py-2 text-uppercase"><?= html_escape($this->session->userdata('role')) ?></span>
                </div>
                <div class="list-group list-group-flush border-top">
                    <?php foreach ($menu as $key => $m): ?>
                        <a href="<?= base_url($m[0]) ?>" class="list-group-item list-group-item-action <?= $active === $key ? 'active' : '' ?>">
                            <i class="bi <?= $m[1] ?> me-2"></i> <?= $m[2] ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?= base_url('login/logout') ?>" class="list-group-item list-group-item-action text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Log out
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger shadow-sm border-0 mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= validation_errors('<div>', '</div>') ?>
                </div>
            <?php endif; ?>
