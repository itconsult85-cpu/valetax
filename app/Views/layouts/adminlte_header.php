<!doctype html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title><?= esc($title ?? 'Circle Republic Trader') ?></title>
<<<<<<< HEAD

    <!--begin::Theme Init-->
    <script>
        (() => {
            'use strict';
            const root = document.documentElement;
            if (root.getAttribute('data-lte-color-mode') === 'off') return;
            const stored = localStorage.getItem('lte-theme');
            const authored = root.getAttribute('data-bs-theme');
            let resolved = 'light';
            if (stored === 'dark' || stored === 'light') {
                resolved = stored;
            } else if (authored === 'dark' || authored === 'light') {
                resolved = authored;
            } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
                resolved = 'dark';
            }
            root.setAttribute('data-bs-theme', resolved);
            root.style.colorScheme = resolved;
            if (resolved !== authored) root.setAttribute('data-lte-theme-resolved', '');
        })();
    </script>
    <!--end::Theme Init-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/adminlte@4.0.0-beta2/dist/css/adminlte.min.css" />

    <!-- DataTables Bootstrap 5 & Responsive -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">

    <!-- Kustom CSS (Hanya untuk style di luar layout utama) -->
=======
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/adminlte4@4.0.0-rc.7.20260519/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/adminlte-custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/legacy-pages.css') ?>">
>>>>>>> d2fdd78c20117783a7c60d84a2f9f1be61d02fa1
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-data.css') ?>">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary sidebar-collapse">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
                    </li>
                </ul>
                <span class="navbar-brand fw-semibold ms-2 me-auto"><?= esc($heading ?? 'Dashboard') ?></span>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center gap-3">
                        <span class="text-secondary small d-none d-md-inline"><i class="bi bi-person-circle me-1"></i><?= esc(session()->get('username') ?? '') ?></span>
                        <a class="btn btn-sm btn-outline-danger" href="<?= site_url('auth/logout') ?>"><i class="bi bi-box-arrow-right me-1"></i>Keluar</a>
                    </li>
                </ul>
            </div>
        </nav>

        <?= $this->include('layouts/adminlte_sidebar') ?>

        <main class="app-main">
            <div class="app-content p-4">
                <div class="container-fluid">