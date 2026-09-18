<?= $this->include('layouts/adminlte_header', [
    'title' => 'Riwayat Chat - Circle Republic Trader',
    'heading' => 'Riwayat Chat - Circle Republic Trader',
    'activeMenu' => '',
]) ?>

<nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold" href="#"><i class="bi bi-robot me-2"></i>Circle Republic Trader BOT</a>
            <div class="d-flex align-items-center gap-3">
                <ul class="navbar-nav flex-row gap-3">
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('AdminDashboard') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('BotSettings') ?>">Pengaturan Bot</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('BotAutoRespon') ?>">Auto-Respon</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= site_url('ChatHistory') ?>">Riwayat Chat</a></li>
                </ul>
                <div class="vr"></div>
                <a href="<?= site_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4 flex-grow-1 d-flex flex-column h-100">
        <div class="mb-3">
            <h4 class="fw-bold tracking-tight mb-1">Monitoring Riwayat Chat</h4>
            <p class="text-muted small">Pantau percakapan bot dengan pengguna secara real-time untuk mendeteksi error respon.</p>
        </div>

        <div class="chat-container">
            <div class="user-list">
                <div class="p-3 bg-white border-bottom sticky-top">
                    <h6 class="fw-bold mb-0 text-muted"><i class="bi bi-people-fill me-2"></i>Daftar Chat</h6>
                </div>
                <div id="contactList">
                    <?php if (empty($users)): ?>
                        <div class="p-4 text-center text-muted small">Belum ada riwayat percakapan.</div>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <div class="user-item p-3" data-phone="<?= esc($user['phone_number']) ?>" data-name="<?= esc($user['user_name'] ?? 'User Baru') ?>" onclick="loadChat(this)">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark text-truncate" style="max-width: 150px;">
                                        <?= esc($user['user_name'] ?? 'User Baru') ?>
                                    </span>
                                </div>
                                <div class="small text-muted font-monospace"><i class="bi bi-telephone text-success me-1"></i><?= esc($user['phone_number']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="chat-area">
                <div class="chat-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-person-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" id="activeChatName">Pilih kontak...</h6>
                            <small class="text-muted" id="activeChatPhone">Klik salah satu kontak di sebelah kiri</small>
                        </div>
                    </div>
                </div>

                <div class="chat-history" id="chatHistory">
                    <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted">
                        <i class="bi bi-chat-square-text fs-1 mb-2"></i>
                        <p>Pilih chat untuk melihat riwayat percakapan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->include('layouts/adminlte_footer') ?>
