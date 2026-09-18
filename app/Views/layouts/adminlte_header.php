<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Circle Republic Trader') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc.7/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/adminlte-custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/legacy-pages.css') ?>">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <button class="navbar-toggler border-0" type="button" data-lte-toggle="sidebar" aria-label="Buka navigasi"><i class="bi bi-list"></i></button>
            <span class="navbar-brand fw-semibold mb-0"><?= esc($heading ?? 'Circle Republic Trader') ?></span>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-secondary small d-none d-md-inline"><i class="bi bi-person-circle me-1"></i><?= esc(session()->get('username') ?? '') ?></span>
                <a class="btn btn-sm btn-outline-danger" href="<?= site_url('auth/logout') ?>"><i class="bi bi-box-arrow-right me-1"></i>Keluar</a>
            </div>
        </div>
    </nav>
    <?= $this->include('layouts/adminlte_sidebar') ?>
    <main class="app-main">
        <div class="app-content p-3 p-md-4">
            <div class="container-fluid">
