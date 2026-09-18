/* dashboard_utama.php */

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
    

/* bot_settings.php */

        document.querySelectorAll('.btn-edit-state').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modalStateTitle').innerText = 'Edit Tahap: ' + this.getAttribute('data-name');
                document.getElementById('state_id').value = this.getAttribute('data-id');
                document.getElementById('state_level').value = this.getAttribute('data-level');
                document.getElementById('state_name').value = this.getAttribute('data-name');
                document.getElementById('state_keywords').value = this.getAttribute('data-keywords');
                document.getElementById('state_reply').value = this.getAttribute('data-reply');
                document.getElementById('state_fallback').value = this.getAttribute('data-fallback');

                const previewDiv = document.getElementById('media-preview');
                previewDiv.innerHTML = '';
                document.getElementById('preview-container').classList.add('d-none');

                const vid = this.getAttribute('data-video');
                const img = this.getAttribute('data-image');

                if (vid || img) {
                    document.getElementById('preview-container').classList.remove('d-none');
                    if (vid) previewDiv.innerHTML += `<video src="${vid}" width="100"></video>`;
                    if (img) previewDiv.innerHTML += `<img src="${img}" width="100">`;
                }
            });
        });

        document.querySelector('.btn-success').addEventListener('click', function() {
            document.getElementById('modalStateTitle').innerText = 'Tambah Tahap Baru';
            document.getElementById('state_id').value = '';
            document.getElementById('state_level').value = '';
            document.getElementById('state_name').value = '';
            document.getElementById('state_keywords').value = '';
            document.getElementById('state_reply').value = '';
            document.getElementById('state_fallback').value = '';
            document.getElementById('media-preview').innerHTML = '';
            document.getElementById('preview-container').classList.add('d-none');
        });

        function previewMedia(input, type) {
            const previewContainer = document.getElementById('preview-container');
            const previewDiv = document.getElementById('media-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                previewContainer.classList.remove('d-none');

                reader.onload = function(e) {
                    previewDiv.innerHTML = '';

                    if (type === 'video') {
                        previewDiv.innerHTML += `<div class="border p-1"><video src="${e.target.result}" width="80" height="60"></video><br><small>Video Baru</small></div>`;
                    } else {
                        previewDiv.innerHTML += `<div class="border p-1"><img src="${e.target.result}" width="80" height="60" class="object-fit-cover"><br><small>Gambar Baru</small></div>`;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    

        const modalKonfirmasi = document.getElementById('modalKonfirmasi');
        modalKonfirmasi.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const urlHapus = button.getAttribute('data-url');
            const btnConfirm = document.getElementById('btnHapusConfirm');
            btnConfirm.setAttribute('href', urlHapus);
        });
    

        document.addEventListener("DOMContentLoaded", function() {
            const alerts = document.querySelectorAll('.alert-dismissible');

            alerts.forEach(function(alertNode) {
                setTimeout(function() {
                    const alert = new bootstrap.Alert(alertNode);
                    alert.close();
                }, 3000);
            });
        });
    

/* bot_autorespon.php */

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
    

/* riwayat_chat.php */

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
    

/* settings_view.php */
