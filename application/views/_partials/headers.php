<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titlePage) ? html_escape($titlePage) . ' — ' : '' ?>SI-PUSTAKA | DIARPUS Kota Mataram</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-primary-dark: #1d4ed8;
            --brand-primary-deep: #1e3a8a;
            --brand-accent: #0ea5e9;
            --bg-body: #f1f5f9;
            --bg-surface: #ffffff;
            --text-main: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --sidebar-width: 272px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.05), 0 4px 12px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(15, 23, 42, 0.07), 0 10px 20px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 12px 32px -4px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* =========================================================
           APP SHELL: SIDEBAR + MAIN AREA
        ========================================================= */
        .app-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Desktop */
        .app-sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1030;
            border-right: 1px solid rgba(255, 255, 255, 0.07);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 1.35rem 1.35rem 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
            color: #ffffff;
        }
        .sidebar-brand:hover {
            color: #ffffff;
        }

        .sidebar-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            line-height: 1.2;
        }
        .sidebar-brand-title {
            font-weight: 800;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            color: #ffffff;
            display: block;
        }
        .sidebar-brand-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
            display: block;
            margin-top: 2px;
        }

        .sidebar-nav-wrap {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 0.9rem;
        }

        .sidebar-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.72rem 0.9rem;
            border-radius: 11px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.18s ease;
            position: relative;
        }

        .sidebar-link i {
            font-size: 1.12rem;
            width: 22px;
            text-align: center;
            color: #94a3b8;
            transition: color 0.18s ease, transform 0.18s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #ffffff;
        }
        .sidebar-link:hover i {
            color: #60a5fa;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.38);
        }
        .sidebar-link.active i {
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(15, 23, 42, 0.45);
        }

        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.07);
            margin-bottom: 0.65rem;
        }

        .sidebar-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .sidebar-user-info {
            min-width: 0;
            flex: 1;
        }
        .sidebar-user-name {
            font-size: 0.84rem;
            font-weight: 700;
            color: #f8fafc;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 1px;
        }
        .sidebar-user-meta {
            font-size: 0.72rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-logout-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.58rem;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.22);
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s ease;
        }
        .btn-logout-sidebar:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
        }

        /* Offcanvas mobile sidebar override */
        .offcanvas-sidebar {
            width: 280px !important;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
        }

        /* Main Area */
        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                display: none;
            }
            .app-main {
                margin-left: 0;
            }
        }

        /* Topbar */
        .app-topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        @media (max-width: 575.98px) {
            .app-topbar {
                padding: 0.75rem 1rem;
            }
        }

        .topbar-toggle {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.15s ease;
        }
        .topbar-toggle:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .app-content {
            flex: 1;
            padding: 1.5rem;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        @media (max-width: 575.98px) {
            .app-content {
                padding: 1rem;
            }
        }

        /* =========================================================
           CARDS, TABLES, BADGES, & MODERN UI COMPONENTS
        ========================================================= */
        .glass-card,
        .modern-card {
            background: var(--bg-surface);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: var(--shadow-sm);
            border-radius: var(--radius-lg);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .hover-up {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s ease;
        }
        .hover-up:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* Stat Icons */
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            font-size: 1.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon-primary { background: rgba(37, 99, 235, 0.12); color: #2563eb; }
        .stat-icon-success { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .stat-icon-info    { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
        .stat-icon-danger  { background: rgba(239, 68, 68, 0.12);  color: #dc2626; }
        .stat-icon-warning { background: rgba(245, 158, 11, 0.14); color: #d97706; }

        /* Soft Badges */
        .badge {
            font-weight: 600;
            letter-spacing: 0.01em;
            padding: 0.42em 0.78em;
            border-radius: 8px;
        }
        .badge-soft-primary {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .badge-soft-success {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .badge-soft-warning {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .badge-soft-danger {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .badge-soft-info {
            background-color: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .badge-soft-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Paket Code Pill */
        .paket-code-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 34px;
            padding: 0 0.65rem;
            border-radius: 9px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 0.03em;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.16);
        }

        /* Modern Tables */
        .table-modern {
            margin-bottom: 0;
        }
        .table-modern thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
            border-top: none;
            white-space: nowrap;
        }
        .table-modern tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            font-size: 0.9rem;
        }
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }
        .table-modern.table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .scroll-y {
            max-height: 500px;
            overflow-y: auto;
        }
        .scroll-y thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: #f8fafc;
            box-shadow: 0 1px 0 var(--border-color);
        }

        /* Form Controls & Buttons */
        .form-control, .form-select {
            border-radius: 10px;
            border-color: #cbd5e1;
            padding: 0.58rem 0.9rem;
            font-size: 0.92rem;
            color: var(--text-main);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.14);
        }
        .form-control-sm, .form-select-sm {
            border-radius: 8px;
            padding: 0.42rem 0.75rem;
            font-size: 0.85rem;
        }
        .input-group-text {
            border-radius: 10px;
            border-color: #cbd5e1;
            background-color: #f8fafc;
            color: #64748b;
        }

        .btn {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.55rem 1rem;
            transition: all 0.18s ease;
        }
        .btn-sm {
            border-radius: 8px;
            padding: 0.38rem 0.78rem;
            font-size: 0.8rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
        }

        /* Filter Pills */
        .filter-pills {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            background: #f1f5f9;
            padding: 0.3rem;
            border-radius: 12px;
        }
        .filter-pill {
            padding: 0.4rem 0.9rem;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .filter-pill:hover {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.6);
        }
        .filter-pill.active {
            background: #ffffff;
            color: var(--brand-primary);
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        }

        /* Modals */
        .modal-content {
            border: none;
            border-radius: 18px;
            box-shadow: var(--shadow-lg);
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.15rem 1.35rem;
        }
        .modal-body {
            padding: 1.35rem;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1rem 1.35rem;
        }

        /* Empty States */
        .empty-state {
            padding: 3rem 1.5rem;
            text-align: center;
        }
        .empty-state-icon {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: #f1f5f9;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
