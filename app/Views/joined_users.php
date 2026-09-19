<?= $this->include('layouts/adminlte_header', [
    'title' => 'Anggota Bergabung | Circle Republic Trader',
    'heading' => 'Data Anggota',
    'activeMenu' => 'joined',
]) ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-semibold">Anggota yang Sudah Bergabung</h1>
        <p class="text-secondary mb-0">Data user yang telah menyelesaikan pendaftaran dan memiliki bukti tersimpan.</p>
    </div>
    <a class="btn btn-primary" href="<?= site_url('JoinedUsers/download') ?>">
        <i class="bi bi-file-earmark-spreadsheet me-2"></i>Download XLSX
    </a>
</div>

<div class="card border-0 shadow-sm" id="joined-users-table-wrapper" data-url="<?= site_url('JoinedUsers/data') ?>">
    <div class="card-header bg-white py-3">
        <h3 class="card-title fw-semibold mb-0"><i class="bi bi-table me-2 text-primary"></i>Datasheet Anggota</h3>
    </div>
    <div class="card-body">
        <!-- Class 'nowrap' wajib ada untuk memicu tombol plus (+) -->
        <table id="joined-users-table" class="table table-hover table-striped align-middle nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nomor Telepon</th>
                    <th>Progress</th>
                    <th>Screenshot</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Dikirim Admin</th>
                    <th>Aktif Terakhir</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->include('layouts/adminlte_footer') ?>