<?= $this->include('layouts/adminlte_header', [
    'title' => 'Dashboard Utama - Circle Republic Trader',
    'heading' => 'Dashboard Utama - Circle Republic Trader',
    'activeMenu' => '',
]) ?>

<?php
    // Cek apakah user adalah admin
    $isAdmin = (session()->get('role') === 'admin' || session()->get('username') === 'admin');
    ?>

    <div class="container py-5">
        <?php if (session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('pesan') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-4 shadow-sm border-0 text-center">
                    <h5 class="fw-bold mb-4">Kontrol Bot</h5>

                    <form action="<?= site_url('BotSettings/updateGlobals') ?>" method="POST">
                        <div class="row g-2 mb-3">
                            <?php foreach ($globals as $global): ?>
                                <?php if (strpos($global['key_name'], 'LINK_') === 0 || strpos($global['key_name'], 'ADMIN_') === 0): ?>
                                    <div class="col-12 text-start">
                                        <label class="form-label small fw-semibold text-muted">
                                            <?= esc($global['label'] ?? $global['key_name']) ?>
                                        </label>
                                        <input type="text" class="form-control" name="config[<?= $global['key_name'] ?>]" value="<?= esc($global['key_value']) ?>" <?= !$isAdmin ? 'readonly' : '' ?>>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-grid">
                            <?php if ($isAdmin): ?>
                                <button type="submit" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
                                    <i class="bi bi-floppy me-1"></i> Simpan Variabel Sistem
                                </button>
                            <?php else: ?>
                                <button type="button" onclick="window.location.href='<?= site_url('AdminDashboard') ?>'" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
                                    <i class="bi bi-floppy me-1"></i> Simpan Variabel Sistem
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <hr class="text-muted my-4 border-2 opacity-25">

                    <h6 class="fw-bold text-start mb-3 text-muted">Aksi Sistem Utama</h6>
                    <div class="d-grid gap-3">
                        <button type="button" class="btn btn-warning rounded-pill fw-semibold shadow-sm text-dark" data-bs-toggle="modal" data-bs-target="#modalRestartBot">
                            <i class="bi bi-arrow-clockwise me-1"></i> Restart Bot
                        </button>

                        <button type="button" class="btn btn-danger rounded-pill fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalClearCache">
                            <i class="bi bi-trash3-fill me-1"></i> Bersihkan Cache Bot
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4 shadow-sm border-0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Status Bot Telegram</h5>
                        <span class="badge text-bg-light">PM2 monitor</span>
                    </div>
                    <div id="telegram-bot-status" data-url="<?= site_url('AdminDashboard/getBotStatus') ?>">
                        <p class="text-muted mb-0">Memeriksa status proses bot...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isAdmin): ?>
        <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="<?= site_url('AdminDashboard/tambahUser') ?>" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalTambahUserLabel">Tambah Akses Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" name="username" required placeholder="Masukkan username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" name="password" required placeholder="Masukkan password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role / Peran</label>
                            <select class="form-select" name="role" required>
                                <option value="user">User Biasa (Hanya Lihat)</option>
                                <option value="admin">Admin (Akses Penuh)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="modal fade" id="modalGantiPassword" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-lock text-primary me-2"></i>Ganti Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="<?= site_url('AdminDashboard/gantiPassword') ?>" method="POST">
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 rounded-3 small">
                            <i class="bi bi-info-circle-fill me-1"></i> Anda akan mengganti password untuk akun: <strong><?= esc(session()->get('username')) ?></strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Password Baru</label>
                            <input type="password" class="form-control" name="password_baru" required minlength="6" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-muted">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="konfirmasi_password" required minlength="6" placeholder="Ketik ulang password baru">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                            <i class="bi bi-floppy me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRestartBot" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Restart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin melakukan restart pada bot?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="<?= site_url('AdminDashboard/botControl/restart') ?>" class="btn btn-primary">Ya, Restart Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalClearCache" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header text-bg-danger">
                    <h1 class="modal-title fs-5"><i class="bi bi-exclamation-triangle-fill me-2"></i> Peringatan Sistem</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3">Apakah Anda yakin ingin membersihkan cache dan memori bot WhatsApp?</p>
                    <div class="alert alert-warning mb-0">Tindakan ini akan menghapus sesi login WhatsApp saat ini.</div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="<?= site_url('AdminDashboard/botControl/clearcache') ?>" class="btn btn-danger">Ya, Bersihkan Cache</a>
                </div>
            </div>
        </div>
    </div>

<?= $this->include('layouts/adminlte_footer') ?>
