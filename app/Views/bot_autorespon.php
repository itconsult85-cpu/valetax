<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Auto-Respon & Konfigurasi Pintar - CIrcle Republic Trader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .data-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .data-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.04);
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

        .config-panel {
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-radius: 16px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold" href="#"><i class="bi bi-robot me-2"></i>Circle Republic Trader BOT</a>
            <div class="d-flex align-items-center gap-3">
                <ul class="navbar-nav flex-row gap-3">
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('AdminDashboard') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('BotSettings') ?>">Pengaturan Bot</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= site_url('BotAutoRespon') ?>">Auto-Respon</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('ChatHistory') ?>">Riwayat Chat</a></li>
                </ul>
                <div class="vr"></div>
                <a href="<?= site_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <?php if (session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-pill px-4 py-2 mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('pesan') ?>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="config-panel p-4 mb-5 shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-stopwatch text-teal fs-4 me-2" style="color: #0d9488;"></i>
                <h4 class="fw-bold tracking-tight mb-0" style="color: #0f766e;">Konfigurasi Auto Follow-Up</h4>
            </div>
            <p class="text-muted small mb-4">Bot akan otomatis mengirim pesan pancingan jika user terdiam di tengah pendaftaran.</p>

            <form action="<?= site_url('BotAutoRespon/simpanCerdas') ?>" method="POST">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card card-body border-0 shadow-sm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-muted">Durasi Tunggu (Menit)</label>
                                    <input type="number" class="form-control" name="TIMEOUT_DURATION" value="<?= esc($config['TIMEOUT_DURATION'] ?? 5) ?>" required>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-label small fw-semibold text-muted">Pesan Pancingan Follow-up</label>
                                    <textarea class="form-control" name="TIMEOUT_MESSAGE" rows="2" required><?= esc($config['TIMEOUT_MESSAGE'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-teal rounded-pill px-4 fw-semibold shadow-sm" style="background-color: #0d9488; color: white;">
                        <i class="bi bi-save me-2"></i>Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>

        <hr class="mb-5 text-muted">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold tracking-tight mb-1">Universal Rule Engine (Pencegat & FAQ)</h4>
                <p class="text-muted small mb-0">Atur tindakan bot jika user mengetik di luar alur.</p>
            </div>
            <div class="d-flex gap-2">
                <!-- Tombol Baru untuk Tambah Aksi -->
                <button class="btn btn-outline-secondary rounded-pill px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAksi">
                    <i class="bi bi-gear me-1"></i> Kelola Tindakan
                </button>
                <!-- Tombol Tambah FAQ (Sudah ada) -->
                <button class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm btn-tambah" data-bs-toggle="modal" data-bs-target="#modalFaq">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Pencegat
                </button>
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($faqs as $faq): ?>
                <div class="col-md-6">
                    <div class="card data-card p-4 h-100 shadow-sm">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="badge bg-warning text-dark border rounded-pill px-3 py-2 small fw-semibold"><i class="bi bi-key-fill me-1"></i> Kata Kunci</span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item btn-edit" href="#"
                                            data-id="<?= $faq['id'] ?>"
                                            data-key="<?= esc($faq['keywords']) ?>"
                                            data-reply="<?= esc($faq['reply_message']) ?>"
                                            data-action="<?= esc($faq['action_type']) ?>"
                                            data-bs-toggle="modal" data-bs-target="#modalFaq">
                                            <i class="bi bi-pencil me-2 text-primary"></i>Edit
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="<?= site_url('BotAutoRespon/hapus/' . $faq['id']) ?>" onclick="return confirm('Yakin hapus auto-respon ini?')"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="font-monospace small bg-light p-2 rounded border text-primary" style="word-break: break-all;"><?= esc($faq['keywords']) ?></p>
                        <hr class="text-muted">

                        <?php
                        $badgeClass = 'bg-secondary';
                        $actionText = 'Hanya Balas';
                        $icon = 'bi-chat-dots';
                        $actionType = $faq['action_type'] ?? 'reply_only';

                        if ($actionType === 'send_tutorial') {
                            $badgeClass = 'bg-info text-dark';
                            $actionText = 'Kirim Panduan';
                            $icon = 'bi-journal-video';
                        }
                        if ($actionType === 'step_back') {
                            $badgeClass = 'bg-warning text-dark';
                            $actionText = 'Mundur 1 Tahap';
                            $icon = 'bi-arrow-left-circle';
                        }
                        if ($actionType === 'reset_state') {
                            $badgeClass = 'bg-danger';
                            $actionText = 'Reset Ulang';
                            $icon = 'bi-arrow-counterclockwise';
                        }
                        ?>
                        <p class="small fw-semibold text-muted mb-2">
                            Balasan & Tindakan:
                            <span class="badge <?= $badgeClass ?> ms-2 fw-normal rounded-pill px-2">
                                <i class="bi <?= $icon ?> me-1"></i><?= $actionText ?>
                            </span>
                        </p>

                        <p class="small text-dark mb-0" style="white-space: pre-wrap;"><?= esc($faq['reply_message']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- MODAL TAMBAH ACTION TYPE -->
    <div class="modal fade" id="modalAksi" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">Tambah Tindakan Lanjutan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= site_url('BotAutoRespon/simpanAksi') ?>" method="POST">
                    <div class="modal-body p-4">
                        <div class="alert alert-info small border-0 bg-info-subtle text-info-emphasis rounded-3">
                            <i class="bi bi-info-circle-fill me-1"></i> <b>Penting:</b> Setelah menambah aksi di sini, pastikan Anda menambahkan blok <code>case "kode_aksi":</code> di dalam file <code>index.js</code> agar bot bisa menjalankannya.
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Kode Aksi (Tanpa Spasi)</label>
                            <input type="text" class="form-control font-monospace" name="action_code" placeholder="contoh: kirim_promo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Label (Tampil di Menu Dropdown)</label>
                            <input type="text" class="form-control" name="action_label" placeholder="contoh: Balas & Kirim Info Promo" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-secondary rounded-pill w-100 fw-semibold py-2 shadow-sm">Tambahkan ke Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFaq" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0" id="modalTitle">Tambah Auto-Respon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= site_url('BotAutoRespon/simpan') ?>" method="POST">
                    <input type="hidden" name="id" id="faq_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Variasi Kata Kunci (Pisahkan dengan koma)</label>
                            <textarea class="form-control font-monospace text-primary" name="keywords" id="faq_keywords" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Pesan Balasan</label>
                            <textarea class="form-control" name="reply_message" id="faq_reply" rows="4" required></textarea>
                            <small class="text-muted" style="font-size:0.75rem;">Gunakan pemisah <b>|</b> untuk balasan acak (Spintax). Tag: <b>{userName}</b>.</small>
                        </div>

                        <div class="mb-3 p-3 bg-light border rounded">
                            <label class="form-label small fw-semibold text-dark"><i class="bi bi-gear-wide-connected me-2 text-primary"></i>Tindakan Bot Lanjutan</label>
                            <select class="form-select border-primary-subtle" name="action_type" id="faq_action" required>
                                <?php foreach ($action_types as $act): ?>
                                    <option value="<?= esc($act['action_code']) ?>"><?= esc($act['action_label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary rounded-pill w-100 fw-semibold py-2 shadow-sm">Simpan Aturan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modalTitle').innerText = 'Edit Aturan';
                document.getElementById('faq_id').value = this.getAttribute('data-id');
                document.getElementById('faq_keywords').value = this.getAttribute('data-key');
                document.getElementById('faq_reply').value = this.getAttribute('data-reply');
                document.getElementById('faq_action').value = this.getAttribute('data-action') || 'reply_only';
            });
        });
        document.querySelector('.btn-tambah').addEventListener('click', function() {
            document.getElementById('modalTitle').innerText = 'Tambah Aturan Baru';
            document.getElementById('faq_id').value = '';
            document.getElementById('faq_keywords').value = '';
            document.getElementById('faq_reply').value = '';
            document.getElementById('faq_action').value = 'reply_only';
        });

        document.addEventListener("DOMContentLoaded", function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alertNode) {
                setTimeout(function() {
                    new bootstrap.Alert(alertNode).close();
                }, 3000);
            });
        });
    </script>
</body>

</html>