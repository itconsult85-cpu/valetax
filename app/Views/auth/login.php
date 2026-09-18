<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Circle Republic Trader</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc.7/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/adminlte-custom.css') ?>">
</head>
<body class="login-page bg-body-tertiary">
<div class="login-box">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header text-center border-0 pt-4"><a href="<?= site_url('auth') ?>" class="h3 text-decoration-none"><i class="bi bi-robot me-2"></i><b>Circle</b> Republic</a></div>
        <div class="card-body">
            <p class="login-box-msg">Masuk ke dashboard administrasi</p>
            <?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger alert-dismissible fade show small" role="alert"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= esc(session()->getFlashdata('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
            <?php if (session()->getFlashdata('pesan')): ?><div class="alert alert-success alert-dismissible fade show small" role="alert"><?= esc(session()->getFlashdata('pesan')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
            <form action="<?= site_url('auth/process') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="input-group mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="username"><span class="input-group-text"><i class="bi bi-person"></i></span></div>
                <div class="input-group mb-4"><input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password"><span class="input-group-text"><i class="bi bi-key"></i></span></div>
                <button type="submit" class="btn btn-primary w-100">Masuk <i class="bi bi-arrow-right ms-1"></i></button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc.7/dist/js/adminlte.min.js"></script>
<script src="<?= base_url('assets/js/adminlte-app.js') ?>"></script>
</body>
</html>
