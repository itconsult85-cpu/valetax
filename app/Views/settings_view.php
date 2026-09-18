<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Bot Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm max-w-md mx-auto">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pengaturan Bot WhatsApp</h5>
        </div>
        <div class="card-body">
            <?php if(session()->getFlashdata('pesan')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('pesan') ?></div>
            <?php endif; ?>

            <form action="/settings/update" method="post">
                <div class="mb-3">
                    <label class="form-label">Nomor Admin (Gunakan akhiran @c.us)</label>
                    <input type="text" name="admin_number" class="form-control" value="<?= esc($setting['admin_number'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link Pendaftaran MIFX</label>
                    <input type="url" name="link_mifx" class="form-control" value="<?= esc($setting['link_mifx'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link Pendaftaran Valetax</label>
                    <input type="url" name="link_valetax" class="form-control" value="<?= esc($setting['link_valetax'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan Peraturan</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>