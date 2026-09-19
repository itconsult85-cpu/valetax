<<<<<<< HEAD
</div> <!-- /.container-fluid -->
</div> <!-- /.app-content -->
</main> <!-- /.app-main -->

<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">AdminLTE 4</div>
    <strong>Circle Republic Trader</strong>
</footer>
</div> <!-- /.app-wrapper -->

<!-- 1. jQuery (Wajib untuk DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- 2. OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<!-- 3. Popper & Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<!-- 4. AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/adminlte@4.0.0-beta2/dist/js/adminlte.min.js"></script>

<!-- 5. DataTables -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

<!-- 6. Inisialisasi OverlayScrollbars (Bawaan AdminLTE) -->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true
    };
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        const isMobile = window.innerWidth <= 992;
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== 'undefined' && !isMobile) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll
                },
            });
        }
    });
</script>

<!-- 7. Script Kustom Aplikasi -->
<script src="<?= base_url('assets/js/dashboard-data.js') ?>"></script>
=======
            </div>
        </div>
    </main>
    <footer class="app-footer"><strong>Circle Republic Trader</strong><span class="float-end d-none d-sm-inline">AdminLTE 4</span></footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/adminlte4@4.0.0-rc.7.20260519/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.7/js/responsive.bootstrap5.min.js"></script>
<script src="<?= base_url('assets/js/adminlte-app.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard-data.js') ?>"></script>
<script src="<?= base_url('assets/js/legacy-pages.js') ?>"></script>
>>>>>>> d2fdd78c20117783a7c60d84a2f9f1be61d02fa1
</body>

</html>