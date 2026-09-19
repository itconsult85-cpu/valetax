document.addEventListener('DOMContentLoaded', function () {
    const tableWrapper = document.getElementById('joined-users-table-wrapper');
    const table = document.getElementById('joined-users-table');

    if (tableWrapper && table) {
        const formatDetail = (row) => `<div class="joined-detail"><div><strong>ID Telegram</strong><span>${escapeHtml(row.user_id || '-')}</span></div><div><strong>Mulai daftar</strong><span>${escapeHtml(row.started_at || '-')}</span></div><div><strong>Selesai daftar</strong><span>${escapeHtml(row.completed_at || '-')}</span></div><div><strong>Dikirim admin</strong><span>${escapeHtml(row.admin_sent_at || 'Belum ada tracking')}</span></div><div><strong>Aktif terakhir</strong><span>${escapeHtml(row.last_active || '-')}</span></div></div>`;
        const ajaxUrl = tableWrapper.dataset.url;

        const renderFallbackRows = (rows) => {
            const tbody = table.tBodies[0] || table.createTBody();
            tbody.innerHTML = '';
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center text-secondary py-4">Belum ada anggota yang selesai bergabung</td></tr>';
                return;
            }
            rows.forEach((row, index) => {
                const tr = document.createElement('tr');
                const values = [index + 1, row.user_name || 'Tanpa nama', row.phone_number || '-', Number(row.current_step || 0), `${Number(row.screenshots_sent || 0)}/2`, row.started_at || '-', row.completed_at || '-', row.admin_sent_at || 'Belum ada tracking', row.last_active || '-'];
                tr.innerHTML = '<td></td>' + values.map((value, column) => `<td>${column === 1 ? `<strong>${escapeHtml(value)}</strong><small class="d-block text-secondary">${escapeHtml(row.user_id || '-')}</small>` : escapeHtml(value)}</td>`).join('');
                tr.dataset.detail = formatDetail(row);
                tbody.appendChild(tr);
            });
        };

        const loadAllRows = () => fetch(`${ajaxUrl}?all=1&_=${Date.now()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, cache: 'no-store' })
            .then(response => { if (!response.ok) throw new Error(`HTTP ${response.status}`); return response.json(); });

        const initialiseTable = (rows) => {
            if (typeof DataTable !== 'function') {
                renderFallbackRows(rows);
                return;
            }
            new DataTable(table, {
                    data: rows,
                    responsive: { details: { type: 'column', target: 0 } },
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    order: [[7, 'desc']],
                    columnDefs: [{ targets: 0, className: 'control', orderable: false, searchable: false, data: null, defaultContent: '' }],
                    columns: [
                        { data: null, responsivePriority: 1 },
                        { data: 'id', className: 'text-muted', responsivePriority: 2 },
                        { data: 'user_name', responsivePriority: 1, render: (data, type, row) => `<strong>${escapeHtml(data || 'Tanpa nama')}</strong><small class="d-block text-secondary">${escapeHtml(row.user_id || '-')}</small>` },
                        { data: 'phone_number', responsivePriority: 2, render: data => escapeHtml(data || '-') },
                        { data: 'current_step', responsivePriority: 3, render: data => `<span class="badge text-bg-success"><i class="bi bi-check2 me-1"></i>${Number(data || 0)}</span>` },
                        { data: 'screenshots_sent', responsivePriority: 4, render: data => `${Number(data || 0)}/2` },
                        { data: 'started_at', responsivePriority: 6, render: data => escapeHtml(data || '-') },
                        { data: 'completed_at', responsivePriority: 5, render: data => escapeHtml(data || '-') },
                        { data: 'admin_sent_at', responsivePriority: 7, render: data => escapeHtml(data || 'Belum ada tracking') },
                        { data: 'last_active', responsivePriority: 8, render: data => escapeHtml(data || '-') }
                    ],
                    language: { search: 'Cari:', searchPlaceholder: 'Nama atau nomor...', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_–_END_ dari _TOTAL_ data', infoEmpty: 'Belum ada data', emptyTable: 'Belum ada anggota yang selesai bergabung', zeroRecords: 'Data tidak ditemukan', processing: 'Memuat data...', paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' } },
                    createdRow: function (row, data) { row.dataset.detail = formatDetail(data); }
                });
        };

        loadAllRows()
            .then(json => initialiseTable(Array.isArray(json.data) ? json.data : []))
            .catch(error => { console.error('Gagal memuat seluruh data anggota:', error); renderFallbackRows([]); });
    }

    const chatPage = document.getElementById('chat-history-page');
    if (chatPage) {
        window.loadChat = function (element) {
            document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            const phone = element.dataset.phone;
            document.getElementById('activeChatName').textContent = element.dataset.name || 'User';
            document.getElementById('activeChatPhone').textContent = phone;
            const box = document.getElementById('chatHistory');
            box.innerHTML = '<div class="text-center text-muted py-5"><div class="spinner-border spinner-border-sm me-2"></div>Memuat percakapan...</div>';
            fetch(`${chatPage.dataset.detailUrl}${encodeURIComponent(phone)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => { if (!response.ok) throw new Error('HTTP ' + response.status); return response.json(); })
                .then(data => {
                    box.innerHTML = '';
                    if (!Array.isArray(data) || data.length === 0) { box.innerHTML = '<div class="text-center text-muted py-5">Data chat kosong.</div>'; return; }
                    data.forEach(chat => {
                        const bubble = document.createElement('div');
                        bubble.className = `bubble ${chat.sender === 'bot' ? 'bubble-bot' : 'bubble-user'}`;
                        const message = document.createElement('div');
                        message.textContent = chat.message || '';
                        const time = document.createElement('small');
                        time.className = 'd-block opacity-75 mt-1';
                        time.textContent = chat.created_at || '';
                        bubble.append(message, time);
                        box.appendChild(bubble);
                    });
                    box.scrollTop = box.scrollHeight;
                }).catch(() => { box.innerHTML = '<div class="text-center text-danger py-5">Gagal memuat percakapan.</div>'; });
        };
    }

    const statusBox = document.getElementById('telegram-bot-status');
    if (statusBox) {
        const url = statusBox.dataset.url;
        const check = () => fetch(`${url}?_=${Date.now()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, cache: 'no-store' })
            .then(response => { if (!response.ok) throw new Error(`HTTP ${response.status}`); return response.json(); })
            .then(data => {
                const active = data.active === true || data.status === 'Connected' || data.status === 'online' || String(data.status || '').toLowerCase().includes('connected');
                statusBox.innerHTML = `<div class="status-indicator ${active ? 'online' : 'offline'}"><i class="bi ${active ? 'bi-check-circle-fill' : 'bi-x-circle-fill'}"></i><div><strong>${active ? 'Bot Telegram Aktif' : 'Bot Telegram Tidak Aktif'}</strong><small class="d-block text-secondary">PM2: ${escapeHtml(data.pm2_status || data.status || 'unknown')}</small></div></div><div class="small text-secondary mt-3">Process: <code>${escapeHtml(data.process_name || 'bot_tele_valetax')}</code></div>`;
            }).catch(error => {
                console.error('Gagal membaca status bot:', error);
                statusBox.innerHTML = '<div class="status-indicator offline"><i class="bi bi-x-circle-fill"></i><div><strong>Status tidak dapat dibaca</strong><small class="d-block text-secondary">Endpoint status bot tidak merespons</small></div></div>';
            });
        check();
        setInterval(check, 10000);
    }
});

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value == null ? '' : String(value);
    return div.innerHTML;
}
