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
    <a class="btn btn-primary" href="<?= site_url('JoinedUsers/download') ?>"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Download XLSX</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary small">Total anggota</div><div class="fs-2 fw-semibold mt-1" id="joined-total"><?= number_format($total, 0, ',', '.') ?></div><div class="text-success small mt-2"><i class="bi bi-check-circle me-1"></i>Data selesai terdeteksi</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-secondary small">Tampilan data</div><div class="fs-5 fw-semibold mt-2">Server-side</div><div class="text-secondary small mt-2">Pencarian dan pagination dari database</div></div></div></div>
</div>

<div class="card border-0 shadow-sm" id="joined-users-table-wrapper" data-url="<?= site_url('JoinedUsers/data') ?>">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center"><h2 class="h6 mb-0 fw-semibold"><i class="bi bi-table me-2 text-primary"></i>Datasheet anggota</h2><span class="badge text-bg-light">Server-side DataTable</span></div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="joined-users-table" class="table table-hover table-striped align-middle w-100">
                <thead class="table-light"><tr><th></th><th>No</th><th>Nama</th><th>Nomor Telepon</th><th>Progress</th><th>Screenshot</th><th>Mulai</th><th>Selesai</th><th>Dikirim Admin</th><th>Aktif Terakhir</th></tr></thead>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 text-secondary small"><i class="bi bi-info-circle me-1"></i>Gunakan tombol plus pada baris untuk melihat detail lengkap pada layar kecil.</div>
</div>

<?= $this->include('layouts/adminlte_footer') ?>
