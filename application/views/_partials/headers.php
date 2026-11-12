<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titlePage) ? html_escape($titlePage) . ' - ' : '' ?>SI-PUSTAKA | DIARPUS Kota Mataram</title>

    <!-- Google Fonts: Inter / Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-soft: #eff6ff;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --sidebar-w: 250px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            font-size: 0.9rem;
        }

        /* App Shell */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* Minimal Clean Sidebar */
        .app-sidebar {
            width: var(--sidebar-w);
            background: #ffffff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1030;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: var(--text-dark);
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: var(--primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0.75rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            flex-grow: 1;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 8px;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.88rem;
            transition: all 0.15s ease;
        }

        .sidebar-menu a:hover {
            background: #f1f5f9;
            color: var(--text-dark);
        }

        .sidebar-menu a.active {
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-user {
            padding: 1rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content */
        .app-main {
            flex: 1;
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 991.98px) {
            .app-sidebar { display: none; }
            .app-main { margin-left: 0; }
        }

        /* Topbar */
        .app-topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .app-container {
            padding: 1.5rem;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            flex: 1;
        }

        /* Clean Minimalist Cards */
        .modern-card, .clean-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }

        /* Clean Tables */
        .table-modern, .table-clean {
            margin-bottom: 0;
            font-size: 0.875rem;
        }
        .table-modern thead th, .table-clean thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            border-top: none;
        }
        .table-modern tbody td, .table-clean tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }
        .table-modern.table-hover tbody tr:hover, .table-clean.table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .paket-code-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            font-weight: 700;
            font-size: 0.85rem;
            border: 1px solid #dbeafe;
        }

        .filter-pills, .clean-nav-tabs {
            display: inline-flex;
            gap: 0.25rem;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 8px;
            flex-wrap: wrap;
        }
        .filter-pill, .clean-nav-tabs a {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            transition: all 0.15s ease;
        }
        .filter-pill:hover, .clean-nav-tabs a:hover {
            color: var(--text-dark);
        }
        .filter-pill.active, .clean-nav-tabs a.active {
            background: #ffffff;
            color: var(--text-dark);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }
        .empty-state-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 0.75rem;
            border-radius: 10px;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .stat-icon-primary { background: #eff6ff; color: #2563eb; }
        .stat-icon-success { background: #ecfdf5; color: #059669; }
        .stat-icon-warning { background: #fffbeb; color: #d97706; }
        .stat-icon-info { background: #f0f9ff; color: #0284c7; }
        .stat-icon-danger { background: #fef2f2; color: #dc2626; }

        /* Subtle Badges */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }
        .badge-soft-primary { background: #eff6ff; color: #1d4ed8; }
        .badge-soft-success { background: #ecfdf5; color: #047857; }
        .badge-soft-warning { background: #fffbeb; color: #b45309; }
        .badge-soft-danger  { background: #fef2f2; color: #b91c1c; }
        .badge-soft-secondary { background: #f1f5f9; color: #475569; }

        /* Form elements */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--border);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.48rem 0.9rem;
        }
        .btn-sm {
            padding: 0.35rem 0.7rem;
            font-size: 0.8rem;
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Filter Tabs */
        .clean-nav-tabs {
            display: inline-flex;
            gap: 0.25rem;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 8px;
        }
        .clean-nav-tabs a {
            padding: 0.35rem 0.8rem;
            border-radius: 6px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.825rem;
            font-weight: 500;
        }
        .clean-nav-tabs a.active {
            background: #ffffff;
            color: var(--text-dark);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
