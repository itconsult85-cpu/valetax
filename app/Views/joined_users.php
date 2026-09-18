<?= $this->include('layouts/adminlte_header', [
    'title' => 'Anggota Bergabung | Circle Republic Trader',
    'heading' => 'Data Anggota',
    'activeMenu' => 'joined',
]) ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="text-secondary small mb-1">Manajemen pengguna</div>
        <h1 class="h3 mb-1 fw-semibold">Anggota yang Sudah Bergabung</h1>
        <p class="text-secondary mb-0">Data user yang telah menyelesaikan pendaftaran dan memiliki bukti tersimpan.</p>
    </div>
    <a class="btn btn-primary" href="<?= site_url('JoinedUsers/download') ?>"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Download Datasheet</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Total anggota</div>
                <div class="fs-2 fw-semibold mt-1"><?= number_format($total, 0, ',', '.') ?></div>
                <div class="text-success small mt-2"><i class="bi bi-check-circle me-1"></i>Data selesai terdeteksi</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Sumber data</div>
                <div class="fs-5 fw-semibold mt-2">User Progress</div>
                <div class="text-secondary small mt-2">Tracking admin aktif jika kolom migrasi tersedia</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-table me-2 text-primary"></i>Datasheet anggota</h2>
        <span class="badge text-bg-light"><?= number_format($total, 0, ',', '.') ?> data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th class="ps-4">No</th><th>Nama</th><th>Nomor Telepon</th><th>Progress</th><th>Screenshot</th><th>Mulai</th><th>Selesai</th><th>Dikirim ke Admin</th><th class="pe-4">Aktif Terakhir</th></tr>
                </thead>
                <tbody>
                <?php if ($users === []): ?>
                    <tr><td colspan="9" class="text-center text-secondary py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada anggota yang ditandai selesai.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td class="ps-4 text-secondary"><?= $index + 1 ?></td>
                            <td><div class="fw-semibold"><?= esc($user['user_name'] ?: 'Tanpa nama') ?></div><div class="text-secondary small"><?= esc($user['user_id'] ?: '-') ?></div></td>
                            <td><?= esc($user['phone_number'] ?: '-') ?></td>
                            <td><span class="badge text-bg-success"><i class="bi bi-check2 me-1"></i><?= (int) $user['current_step'] ?></span></td>
                            <td><?= (int) $user['screenshots_sent'] ?>/2</td>
                            <td><?= esc($user['started_at'] ?: '-') ?></td>
                            <td><?= esc($user['completed_at'] ?: '-') ?></td>
                            <td><?= esc($user['admin_sent_at'] ?? 'Belum ada tracking') ?></td>
                            <td class="pe-4"><?= esc($user['last_active'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 text-secondary small"><i class="bi bi-info-circle me-1"></i>Tanpa migrasi delivery admin, halaman memakai fallback data lama agar tidak error.</div>
</div>

<?= $this->include('layouts/adminlte_footer') ?>
