<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titlePage) ? html_escape($titlePage) . ' | ' : '' ?>Sistem Peminjaman Paket Buku</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-radius: 12px;
        }
        .hover-up { transition: all .2s ease-in-out; }
        .hover-up:hover { transform: translateY(-2px); }
        .stat-icon { width: 52px; height: 52px; font-size: 22px; }
        .scroll-y { max-height: 480px; overflow-y: auto; }
        .scroll-y thead th { position: sticky; top: 0; z-index: 1; }
        .head1 { background-color: #003cff; }
        
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark head1  shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('backend/dashboard') ?>">
            <i class="bi bi-journal-bookmark-fill me-2"></i>DIARPUS KOTA MATARAM
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($this->session->userdata('id')): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= html_escape($this->session->userdata('nama')) ?>
                            <span class="badge bg-warning text-dark ms-1"><?= html_escape(strtoupper($this->session->userdata('role'))) ?></span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('login/logout') ?>">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
