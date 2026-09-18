<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Chat - Circle Republic Trader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', system-ui, sans-serif;
            color: #0f172a;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .nav-link {
            color: #64748b;
        }

        .nav-link.active {
            color: #0d6efd !important;
            font-weight: 600;
        }

        .chat-container {
            flex-grow: 1;
            overflow: hidden;
            display: flex;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .user-list {
            width: 300px;
            border-right: 1px solid #e2e8f0;
            overflow-y: auto;
            background: #f8fafc;
        }

        .user-item {
            cursor: pointer;
            transition: background-color 0.2s;
            border-bottom: 1px solid #e2e8f0;
        }

        .user-item:hover,
        .user-item.active {
            background-color: #e2e8f0;
        }

        .chat-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #efeae2;
            background-image: url('https://w0.peakpx.com/wallpaper/818/148/HD-wallpaper-whatsapp-background-cool-dark-green-new-theme-whatsapp.jpg');
            background-blend-mode: overlay;
            background-size: cover;
        }

        .chat-header {
            background: #ffffff;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            z-index: 10;
        }

        .chat-history {
            flex-grow: 1;
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .bubble {
            max-width: 70%;
            padding: 10px 15px;
            font-size: 0.9rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .bubble-user {
            background-color: #ffffff;
            align-self: flex-start;
            border-radius: 0px 15px 15px 15px;
        }

        .bubble-bot {
            background-color: #d1e7dd;
            align-self: flex-end;
            border-radius: 15px 0px 15px 15px;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadChat(element) {
            document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
            element.classList.add('active');

            const phone = element.getAttribute('data-phone');
            const name = element.getAttribute('data-name');
            const chatHistoryBox = document.getElementById('chatHistory');

            document.getElementById('activeChatName').innerText = name;
            document.getElementById('activeChatPhone').innerText = phone;

            chatHistoryBox.innerHTML = '<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2">Memuat percakapan...</p></div>';

            // Memanggil endpoint dari Controller baru
            fetch(`<?= site_url('ChatHistory/getDetailChat/') ?>${phone}`)
                .then(response => response.json())
                .then(data => {
                    chatHistoryBox.innerHTML = '';

                    if (data.length === 0) {
                        chatHistoryBox.innerHTML = '<div class="text-center mt-5 text-muted">Data chat kosong.</div>';
                        return;
                    }

                    data.forEach(chat => {
                        const bubble = document.createElement('div');
                        if (chat.sender === 'bot') {
                            bubble.className = 'bubble bubble-bot';
                        } else {
                            bubble.className = 'bubble bubble-user';
                        }

                        bubble.innerHTML = chat.message;
                        chatHistoryBox.appendChild(bubble);
                    });

                    chatHistoryBox.scrollTop = chatHistoryBox.scrollHeight;
                })
                .catch(error => {
                    console.error('Error:', error);
                    chatHistoryBox.innerHTML = '<div class="text-center mt-5 text-danger">Gagal memuat percakapan.</div>';
                });
        }
    </script>
</body>

</html>