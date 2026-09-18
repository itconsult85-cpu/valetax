<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anggota Bergabung | Circle Republic Trader</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc.7/dist/css/adminlte.min.css">
    <style>
        body { background: #f4f6f9; }
        .app-content { min-height: calc(100vh - 124px); }
        .brand-mark { width: 2.25rem; height: 2.25rem; display: inline-grid; place-items: center; border-radius: .65rem; }
        .table thead th { white-space: nowrap; font-size: .78rem; letter-spacing: .02em; text-transform: uppercase; }
        .table td { vertical-align: middle; white-space: nowrap; }
        .table-responsive { min-height: 260px; }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <button class="navbar-toggler border-0" type="button" data-lte-toggle="sidebar" aria-label="Buka navigasi"><i class="bi bi-list"></i></button>
            <span class="navbar-brand fw-semibold mb-0">Data Anggota</span>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-secondary small d-none d-md-inline"><i class="bi bi-person-circle me-1"></i><?= esc(session()->get('username')) ?></span>
                <a class="btn btn-sm btn-outline-danger" href="<?= site_url('auth/logout') ?>"><i class="bi bi-box-arrow-right me-1"></i>Keluar</a>
            </div>
        </div>
    </nav>

    <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="<?= site_url('AdminDashboard') ?>" class="brand-link text-decoration-none">
                <span class="brand-mark bg-primary"><i class="bi bi-robot"></i></span>
                <span class="brand-text fw-light ms-2">Republic Trader</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                    <li class="nav-item"><a href="<?= site_url('AdminDashboard') ?>" class="nav-link"><i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p></a></li>
                    <li class="nav-item"><a href="<?= site_url('JoinedUsers') ?>" class="nav-link active"><i class="nav-icon bi bi-people-fill"></i><p>Anggota Bergabung</p></a></li>
                    <?php if (session()->get('role') === 'admin' || session()->get('username') === 'admin'): ?>
                        <li class="nav-header">ADMINISTRASI</li>
                        <li class="nav-item"><a href="<?= site_url('BotSettings') ?>" class="nav-link"><i class="nav-icon bi bi-sliders"></i><p>Pengaturan Bot</p></a></li>
                        <li class="nav-item"><a href="<?= site_url('BotAutoRespon') ?>" class="nav-link"><i class="nav-icon bi bi-chat-square-text"></i><p>Auto-Respon</p></a></li>
                        <li class="nav-item"><a href="<?= site_url('ChatHistory') ?>" class="nav-link"><i class="nav-icon bi bi-clock-history"></i><p>Riwayat Chat</p></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content p-3 p-md-4">
            <div class="container-fluid">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <div class="text-secondary small mb-1">Manajemen pengguna</div>
                        <h1 class="h3 mb-1 fw-semibold">Anggota yang Sudah Bergabung</h1>
                        <p class="text-secondary mb-0">Daftar pengguna yang bukti pendaftarannya berhasil dikirim ke admin.</p>
                    </div>
                    <a class="btn btn-primary" href="<?= site_url('JoinedUsers/download') ?>"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Download Datasheet</a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary small">Total anggota</div><div class="fs-2 fw-semibold mt-1"><?= number_format($total, 0, ',', '.') ?></div><div class="text-success small mt-2"><i class="bi bi-check-circle me-1"></i>Terkirim ke admin</div></div></div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary small">Sumber data</div><div class="fs-5 fw-semibold mt-2">User Progress</div><div class="text-secondary small mt-2">Data tersimpan otomatis saat selesai</div></div></div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center"><h2 class="h6 mb-0 fw-semibold"><i class="bi bi-table me-2 text-primary"></i>Datasheet anggota</h2><span class="badge text-bg-light"><?= number_format($total, 0, ',', '.') ?> data</span></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light"><tr><th class="ps-4">No</th><th>Nama</th><th>Nomor Telepon</th><th>Progress</th><th>Screenshot</th><th>Mulai</th><th>Selesai</th><th>Dikirim ke Admin</th><th class="pe-4">Aktif Terakhir</th></tr></thead>
                                <tbody>
                                <?php if ($users === []): ?>
                                    <tr><td colspan="9" class="text-center text-secondary py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada anggota yang ditandai selesai.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($users as $index => $user): ?>
                                        <tr><td class="ps-4 text-secondary"><?= $index + 1 ?></td><td><div class="fw-semibold"><?= esc($user['user_name'] ?: 'Tanpa nama') ?></div><div class="text-secondary small"><?= esc($user['user_id'] ?: '-') ?></div></td><td><?= esc($user['phone_number'] ?: '-') ?></td><td><span class="badge text-bg-success"><i class="bi bi-check2 me-1"></i><?= (int) $user['current_step'] ?></span></td><td><?= (int) $user['screenshots_sent'] ?>/2</td><td><?= esc($user['started_at'] ?: '-') ?></td><td><?= esc($user['completed_at'] ?: '-') ?></td><td><?= esc($user['admin_sent_at'] ?: '-') ?></td><td class="pe-4"><?= esc($user['last_active'] ?: '-') ?></td></tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 text-secondary small"><i class="bi bi-info-circle me-1"></i>Data menampilkan pengguna dengan <strong>completed_at</strong> terisi dan minimal satu bukti screenshot dan pengiriman admin terverifikasi.</div>
                </div>
            </div>
        </div>
    </main>
    <footer class="app-footer"><strong>Circle Republic Trader</strong><span class="float-end d-none d-sm-inline">AdminLTE 4</span></footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc.7/dist/js/adminlte.min.js"></script>
</body>
</html>
