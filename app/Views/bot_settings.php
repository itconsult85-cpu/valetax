<?= $this->include('layouts/adminlte_header', [
    'title' => 'Pengaturan Bot - Circle Republic Trader',
    'heading' => 'Pengaturan Bot - Circle Republic Trader',
    'activeMenu' => '',
]) ?>
<div class="container py-5">
        <?php if (session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-pill px-4 py-2 mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('pesan') ?>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <h3 class="fw-bold tracking-tight mb-1">Pengaturan Mesin Bot</h3>
            <p class="text-muted small">Kelola variabel tautan dan bangun alur percakapan dinamis (State Machine).</p>
        </div>

        <div class="card metric-card p-4 mb-5 shadow-sm">
            <h5 class="fw-bold mb-4"><i class="bi bi-globe me-2 text-primary"></i>Variabel Sistem & Tautan</h5>
            <form action="<?= site_url('BotSettings/updateGlobals') ?>" method="POST">

                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Tautan & Kontak Utama</h6>
                <div class="row g-3 mb-4">
                    <?php foreach ($globals as $global): ?>
                        <!-- Jika Key berawalan LINK_ atau ADMIN_ -->
                        <?php if (strpos($global['key_name'], 'LINK_') === 0 || strpos($global['key_name'], 'ADMIN_') === 0 || $global['key_name'] === 'KATA_SAPAAN'): ?>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted"><?= esc($global['label'] ?? $global['key_name']) ?></label>
                                <input type="text" class="form-control" name="config[<?= $global['key_name'] ?>]" value="<?= esc($global['key_value']) ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- TAMBAHAN: SEGMEN PENGATURAN NEGOSIASI -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">Pengaturan Negosiasi (Valetax)</h6>
                <div class="row g-3 mb-4">
                    <?php foreach ($globals as $global): ?>
                        <?php if (strpos($global['key_name'], 'NEGO_') === 0): ?>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted"><?= esc($global['label'] ?? $global['key_name']) ?></label>
                                <?php if ($global['key_name'] === 'NEGO_KEYWORDS'): ?>
                                    <input type="text" class="form-control" name="config[<?= $global['key_name'] ?>]" value="<?= esc($global['key_value']) ?>">
                                <?php else: ?>
                                    <textarea class="form-control" name="config[<?= $global['key_name'] ?>]" rows="4"><?= esc($global['key_value']) ?></textarea>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-floppy me-1"></i> Simpan Variabel Sistem
                    </button>
                </div>
            </form>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold tracking-tight mb-1">Alur Tanya Jawab (States)</h4>
                <p class="text-muted small mb-0">Bot akan membaca tahap secara berurutan sesuai nomor tahap.</p>
            </div>
            <button class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalState">
                <i class="bi bi-plus-circle-fill me-2"></i> Tambah Tahap Baru
            </button>
        </div>

        <div class="row g-3">
            <?php if (empty($flows)): ?>
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada alur pesan yang dibuat.
                </div>
            <?php endif; ?>

            <?php foreach ($flows as $flow): ?>
                <div class="col-12">
                    <div class="card data-card p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                            <div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 small fw-semibold mb-2">
                                    Tahap <?= esc($flow['step_level']) ?>
                                </span>

                                <h5 class="fw-bold mb-0 text-dark"><?= esc($flow['step_name']) ?></h5>

                                <div class="row g-2 mt-2">
                                    <?php if (!empty($flow['fallback_video_url'])): ?>
                                        <div class="col-auto">
                                            <div class="position-relative border rounded p-1 bg-light">
                                                <video width="80" height="60" src="<?= $flow['fallback_video_url'] ?>"></video>

                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle p-0"
                                                    style="width: 22px; height: 22px; font-size: 12px; line-height: 1;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalKonfirmasi"
                                                    data-url="<?= site_url('BotSettings/hapusMedia/' . $flow['id'] . '/video') ?>">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($flow['fallback_image_url'])): ?>
                                        <div class="col-auto">
                                            <div class="position-relative border rounded p-1 bg-light">
                                                <img src="<?= $flow['fallback_image_url'] ?>" width="80" height="60" class="object-fit-cover">

                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle p-0"
                                                    style="width: 22px; height: 22px; font-size: 12px; line-height: 1;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalKonfirmasi"
                                                    data-url="<?= site_url('BotSettings/hapusMedia/' . $flow['id'] . '/image') ?>">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold btn-edit-state"
                                    data-id="<?= $flow['id'] ?>"
                                    data-level="<?= $flow['step_level'] ?>"
                                    data-name="<?= htmlspecialchars($flow['step_name'], ENT_QUOTES) ?>"
                                    data-keywords="<?= htmlspecialchars($flow['trigger_keywords'], ENT_QUOTES) ?>"
                                    data-reply="<?= htmlspecialchars($flow['reply_message'], ENT_QUOTES) ?>"
                                    data-fallback="<?= htmlspecialchars($flow['fallback_message'], ENT_QUOTES) ?>"

                                    data-video="<?= esc($flow['fallback_video_url']) ?>"
                                    data-image="<?= esc($flow['fallback_image_url']) ?>"

                                    data-bs-toggle="modal"
                                    data-bs-target="#modalState">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalKonfirmasi"
                                    data-url="<?= site_url('BotSettings/deleteFlow/' . $flow['id']) ?>">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-md-3 border-end">
                                <p class="text-muted small fw-semibold mb-1"><i class="bi bi-key text-warning"></i> Trigger Keywords</p>
                                <p class="mb-0 text-dark small font-monospace bg-light p-2 rounded border text-wrap" style="word-break: break-all;">
                                    <?= esc($flow['trigger_keywords']) ?>
                                </p>
                            </div>
                            <div class="col-md-5 border-end">
                                <p class="text-muted small fw-semibold mb-1"><i class="bi bi-chat-dots text-success"></i> Pesan Sukses (Lanjut)</p>
                                <p class="mb-0 text-dark small" style="white-space: pre-wrap;"><?= esc($flow['reply_message']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small fw-semibold mb-1"><i class="bi bi-exclamation-triangle text-danger"></i> Pesan Gagal (Fallback)</p>
                                <p class="mb-0 text-dark small" style="white-space: pre-wrap;"><?= esc($flow['fallback_message']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="modal fade" id="modalState" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0" id="modalStateTitle">Tambah Tahap Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="<?= site_url('BotSettings/saveFlow') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="state_id" name="id">
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-muted">No. Urut Tahap</label>
                                <input type="number" class="form-control" id="state_level" name="step_level" required placeholder="Cth: 1">
                            </div>
                            <div class="col-md-9">
                                <label class="form-label small fw-semibold text-muted">Nama Tahap (Hanya penanda)</label>
                                <input type="text" class="form-control" id="state_name" name="step_name" required placeholder="Cth: Tanya Minat & MIFX">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Kata Kunci / Jawaban User (Pisahkan dengan koma)</label>
                            <textarea class="form-control font-monospace text-primary" id="state_keywords" name="trigger_keywords" rows="2" placeholder="mau, iya, lanjut, sudah, beres" required></textarea>
                            <small class="text-muted" style="font-size: 0.75rem;">Jika pesan user mengandung salah satu kata di atas, bot akan mengirim Pesan Sukses.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Pesan Sukses (Jika keyword cocok)</label>
                            <textarea class="form-control" id="state_reply" name="reply_message" rows="5" required></textarea>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                Anda dapat menggunakan variabel otomatis: <b>{LINK_MIFX}</b>, <b>{LINK_VALETAX}</b>, atau <b>{userName}</b>.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Pesan Gagal (Jika user mengetik hal lain)</label>
                            <textarea class="form-control bg-light" id="state_fallback" name="fallback_message" rows="2" required></textarea>
                        </div>

                        <div class="row g-3 mb-3 mt-3 p-3 bg-light rounded border">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Video Panduan (MP4)</label>
                                <input type="file" class="form-control" name="fallback_video" accept="video/mp4" onchange="previewMedia(this, 'video')">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Gambar Hasil Sukses</label>
                                <input type="file" class="form-control" name="fallback_image" accept="image/*" onchange="previewMedia(this, 'image')">
                            </div>

                            <div id="preview-container" class="mt-2 d-none col-12">
                                <label class="small text-muted fw-bold">Preview Media:</label>
                                <div id="media-preview" class="d-flex gap-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                            <i class="bi bi-floppy me-1"></i> Simpan Tahap
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Umum -->
    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="btnHapusConfirm" class="btn btn-danger rounded-pill px-4">Ya, Hapus Sekarang</a>
                </div>
            </div>
        </div>
    </div>

<?= $this->include('layouts/adminlte_footer') ?>
