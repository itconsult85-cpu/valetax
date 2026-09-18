<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Utama - Circle Republic Trader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', system-ui, sans-serif;
            color: #0f172a;
        }

        .nav-link {
            color: #64748b;
        }

        .nav-link.active {
            color: #0d6efd !important;
            font-weight: 600;
        }

        .metric-card,
        .data-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.04);
        }

        .data-card:hover {
            transform: translateX(4px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.03);
            border-color: #cbd5e1;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 0.6rem 1rem;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
        }
    </style>
</head>

<body>
    <?php
    // Cek apakah user adalah admin
    $isAdmin = (session()->get('role') === 'admin' || session()->get('username') === 'admin');
    ?>

    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold" href="#"><i class="bi bi-robot me-2"></i>Circle Republic Trader BOT</a>

            <div class="d-flex align-items-center gap-3">
                <ul class="navbar-nav flex-row gap-3">
                    <li class="nav-item"><a class="nav-link active" href="<?= site_url('AdminDashboard') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('JoinedUsers') ?>"><i class="bi bi-people-fill me-1"></i>Anggota Bergabung</a></li>

                    <?php if ($isAdmin): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= site_url('BotSettings') ?>">Pengaturan Bot</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= site_url('BotAutoRespon') ?>">Auto-Respon (FAQ)</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= site_url('ChatHistory') ?>">Riwayat Chat</a></li>
                    <?php endif; ?>
                </ul>

                <div class="vr"></div>

                <?php if ($isAdmin): ?>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                        <i class="bi bi-person-plus me-1"></i> Tambah User
                    </button>
                <?php endif; ?>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalGantiPassword">
                    <i class="bi bi-key me-1"></i> Ganti Password
                </button>
                <a href="<?= site_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

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
                <div class="card p-4 shadow-sm border-0 text-center">
                    <h5 class="fw-bold mb-4">Status WhatsApp</h5>
                    <div id="qrCodeWrapper">
                        <p class="text-muted">Menunggu sinkronisasi status...</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <script>
        const NODE_API_URL = "http://202.10.34.128:3001/api/bot-status";

        function checkBotStatus() {
            fetch(NODE_API_URL)
                .then(res => res.json())
                .then(data => {
                    const wrapper = document.getElementById('qrCodeWrapper');
                    if (data.status === "Waiting for Scan") {
                        wrapper.innerHTML = `<canvas id="qrCanvas"></canvas><p class="text-warning mt-2 fw-semibold">Harap Scan QR Code</p>`;
                        QRCode.toCanvas(document.getElementById('qrCanvas'), data.qr, {
                            width: 200,
                            margin: 2,
                            color: {
                                dark: '#0f172a',
                                light: '#ffffff'
                            }
                        });
                    } else if (data.status === "Connected") {
                        wrapper.innerHTML = `<div class="text-success py-3"><i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i><h5 class="mt-3 fw-bold">Bot Terhubung & Aktif</h5></div>`;
                    } else {
                        wrapper.innerHTML = `<p class="text-danger fw-semibold"><i class="bi bi-arrow-repeat spin"></i> Bot Terputus / Loading...</p>`;
                    }
                })
                .catch(() => {
                    document.getElementById('qrCodeWrapper').innerHTML = `<p class="text-danger py-3"><i class="bi bi-x-circle fs-1 mb-2 d-block"></i>Gagal konek ke Server Bot (Node.js)</p>`;
                });
        }

        setInterval(checkBotStatus, 3000);
        checkBotStatus();

        document.addEventListener("DOMContentLoaded", function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alertNode) {
                setTimeout(function() {
                    const alert = new bootstrap.Alert(alertNode);
                    alert.close();
                }, 3000);
            });
        });
    </script>
</body>

</html>
