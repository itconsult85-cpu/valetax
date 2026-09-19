<aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= site_url('AdminDashboard') ?>" class="brand-link text-decoration-none">
            <span class="brand-mark bg-primary rounded px-2 py-1"><i class="bi bi-robot text-white"></i></span>
            <span class="brand-text fw-light ms-2">Republic Trader</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!-- Wajib tambahkan data-lte-toggle="treeview" -->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="<?= site_url('AdminDashboard') ?>" class="nav-link">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('JoinedUsers') ?>" class="nav-link <?= ($activeMenu ?? '') === 'joined' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Anggota Bergabung</p>
                    </a>
                </li>

                <?php if (session()->get('role') === 'admin' || session()->get('username') === 'admin'): ?>
                    <li class="nav-header mt-3 text-uppercase fs-7 opacity-75">Administrasi</li>

                    <li class="nav-item">
                        <a href="<?= site_url('BotSettings') ?>" class="nav-link">
                            <i class="nav-icon bi bi-sliders"></i>
                            <p>Pengaturan Bot</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('BotAutoRespon') ?>" class="nav-link">
                            <i class="nav-icon bi bi-chat-square-text"></i>
                            <p>Auto-Respon</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('ChatHistory') ?>" class="nav-link">
                            <i class="nav-icon bi bi-clock-history"></i>
                            <p>Riwayat Chat</p>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
</aside>