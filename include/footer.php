</div> <!-- Kết thúc .main-content (đã được mở ở include/header.php) -->

<!-- FOOTER CHUẨN DÙNG CHUNG -->
<footer class="bg-dark text-white pt-4 pb-3 mt-auto">
    <div class="container text-center text-md-start">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h5 class="fw-bold text-uppercase text-rose">Jobio</h5>
                <p class="small text-white-50 m-0">Nền tảng kết nối ứng viên tài năng và nhà tuyển dụng hàng đầu.</p>
            </div>
            <div class="col-md-6 text-md-end mb-3">
                <p class="small text-white-50 m-0">&copy; 2026 Jobio Project. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script xử lý Logout dùng chung cho toàn bộ các trang -->
<script>
    function handleLogout(e) {
        if (e) e.preventDefault();
        fetch('../api/auth.php?action=logout')
            .then(() => { window.location.href = 'index.php'; })
            .catch(() => { window.location.href = 'index.php'; });
    }
</script>
</body>
</html>