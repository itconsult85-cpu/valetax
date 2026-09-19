<<<<<<< HEAD
document.addEventListener("DOMContentLoaded", function () {
  const tableEl = document.getElementById("joined-users-table-wrapper");
  if (!tableEl) return;

  $("#joined-users-table").DataTable({
    processing: true,
    serverSide: true,
    autoWidth: false, // Menghindari konflik flexbox Bootstrap
    responsive: true,
    ajax: {
      url: tableEl.getAttribute("data-url"),
      type: "GET",
    },
    columnDefs: [
      {
        targets: 0,
        className: "dtr-control", // Kolom 0 jadi tombol (+)
        orderable: false,
        searchable: false,
        data: null,
        defaultContent: "",
      },
    ],
    columns: [
      { data: null },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      { data: "user_name", name: "user_name", defaultContent: "-" },
      { data: "phone_number", name: "phone_number", defaultContent: "-" },
      { data: "current_step", name: "current_step", defaultContent: "0" },
      {
        data: "screenshots_sent",
        name: "screenshots_sent",
        defaultContent: "0",
      },
      { data: "started_at", name: "started_at", defaultContent: "-" },
      { data: "completed_at", name: "completed_at", defaultContent: "-" },
      {
        data: "admin_sent_at",
        name: "admin_sent_at",
        defaultContent: "Belum dikirim",
      },
      { data: "last_active", name: "last_active", defaultContent: "-" },
    ],
    order: [[7, "desc"]],
  });
});
=======
document.addEventListener('DOMContentLoaded', function () {
    const tableWrapper = document.getElementById('joined-users-table-wrapper');
    const table = document.getElementById('joined-users-table');
    if (tableWrapper && table && window.DataTable) {
        const formatDetail = (row) => `<div class="joined-detail"><div><strong>ID Telegram</strong><span>${escapeHtml(row.user_id || '-')}</span></div><div><strong>Mulai daftar</strong><span>${escapeHtml(row.started_at || '-')}</span></div><div><strong>Selesai daftar</strong><span>${escapeHtml(row.completed_at || '-')}</span></div><div><strong>Dikirim admin</strong><span>${escapeHtml(row.admin_sent_at || 'Belum ada tracking')}</span></div><div><strong>Aktif terakhir</strong><span>${escapeHtml(row.last_active || '-')}</span></div></div>`;
        const dt = new DataTable(table, {
            processing: true,
            serverSide: true,
            responsive: { details: { type: 'column', target: 0 } },
            ajax: { url: tableWrapper.dataset.url, dataSrc: 'data' },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[7, 'desc']],
            columnDefs: [{ targets: 0, className: 'control', orderable: false, searchable: false, data: null, defaultContent: '' }],
            columns: [
                { data: null },
                { data: 'id' },
                { data: 'user_name', render: (data, type, row) => `<strong>${escapeHtml(data || 'Tanpa nama')}</strong><small class="d-block text-secondary">${escapeHtml(row.user_id || '-')}</small>` },
                { data: 'phone_number', render: data => escapeHtml(data || '-') },
                { data: 'current_step', render: data => `<span class="badge text-bg-success"><i class="bi bi-check2 me-1"></i>${Number(data || 0)}</span>` },
                { data: 'screenshots_sent', render: data => `${Number(data || 0)}/2` },
                { data: 'started_at', render: data => escapeHtml(data || '-') },
                { data: 'completed_at', render: data => escapeHtml(data || '-') },
                { data: 'admin_sent_at', render: data => escapeHtml(data || 'Belum ada tracking') },
                { data: 'last_active', render: data => escapeHtml(data || '-') }
            ],
            language: { search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data', info: 'Menampilkan _START_–_END_ dari _TOTAL_ data', infoEmpty: 'Belum ada data', zeroRecords: 'Data tidak ditemukan', processing: 'Memuat data...', paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' } },
            createdRow: function (row, data) { row.dataset.detail = formatDetail(data); }
        });
        table.addEventListener('click', function (event) {
            const tr = event.target.closest('tbody tr');
            if (!tr || event.target.closest('td.control')) return;
            if (tr.classList.contains('child')) return;
        });
        document.addEventListener('click', function (event) {
            const control = event.target.closest('td.control');
            if (!control) return;
            const tr = control.closest('tr');
            if (tr && tr.dataset.detail) {
                const child = tr.nextElementSibling;
                if (!child || !child.classList.contains('child')) return;
            }
        });
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
        const check = () => fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json()).then(data => {
            const active = data.active === true || data.status === 'Connected' || data.status === 'online' || String(data.status || '').toLowerCase().includes('connected');
            statusBox.innerHTML = `<div class="status-indicator ${active ? 'online' : 'offline'}"><i class="bi ${active ? 'bi-check-circle-fill' : 'bi-x-circle-fill'}"></i><div><strong>${active ? 'Bot Telegram Aktif' : 'Bot Telegram Tidak Aktif'}</strong><small class="d-block text-secondary">PM2: ${escapeHtml(data.pm2_status || data.status || 'unknown')}</small></div></div><div class="small text-secondary mt-3">Process: <code>${escapeHtml(data.process_name || 'bot_tele_valetax')}</code></div>`;
        }).catch(() => { statusBox.innerHTML = '<div class="status-indicator offline"><i class="bi bi-x-circle-fill"></i><div><strong>Status tidak dapat dibaca</strong><small class="d-block text-secondary">Server bot tidak merespons</small></div></div>'; });
        check(); setInterval(check, 10000);
    }
});

function escapeHtml(value) { const div = document.createElement('div'); div.textContent = value == null ? '' : String(value); return div.innerHTML; }
>>>>>>> d2fdd78c20117783a7c60d84a2f9f1be61d02fa1
