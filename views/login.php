<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/auth_check.php';

// Nếu đã đăng nhập thì chuyển hướng về Trang chủ
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Lấy trang cần chuyển hướng sau khi đăng nhập thành công (nếu có)
$redirect = isset($_GET['redirect']) ? sanitizeInput($_GET['redirect']) : 'index.php';

// 2. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Đăng Nhập";
require_once __DIR__ . '/../include/header.php';
?>

<!-- FORM ĐĂNG NHẬP -->
<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="bg-white border shadow-sm" style="border-radius: 12px 2px 12px 2px; border-color: var(--sky-cloud) !important; overflow: hidden;">
                
                <!-- Header Thẻ Đăng Nhập Gradient Ocean Pastel -->
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, var(--veranda-blue) 0%, var(--cupid-pink) 100%);">
                    <h4 class="fw-bold m-0">ĐĂNG NHẬP</h4>
                    <small class="text-white-50">Chào mừng bạn quay lại với Jobio</small>
                </div>

                <div class="p-4">
                    <!-- Thông báo lỗi/thành công từ JS -->
                    <div id="auth-alert" class="d-none alert py-2 fs-7 mb-3" style="border-radius: 6px 2px 6px 2px;"></div>

                    <form id="login-form" action="../api/auth.php?action=login" method="POST">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Địa chỉ Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control border-start-0" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold text-dark small mb-0">Mật khẩu <span class="text-danger">*</span></label>
                                <a href="javascript:void(0)" class="text-decoration-none small text-muted" onclick="alert('Vui lòng liên hệ Quản trị viên để khôi phục mật khẩu.');">Quên mật khẩu?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                                <input type="password" name="mat_khau" class="form-control border-start-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-rose w-100 py-2 fw-semibold shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <span class="small text-muted">Chưa có tài khoản?</span>
                        <a href="register.php" class="small fw-semibold text-decoration-none ms-1 text-cupid">Đăng ký ngay</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- XỬ LÝ ĐĂNG NHẬP VIA API -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const alertBox  = document.getElementById('auth-alert');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);

            fetch('../api/auth.php?action=login', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (alertBox) {
                        alertBox.className = 'alert py-2 fs-7 mb-3 d-block';
                        alertBox.style.backgroundColor = 'var(--lychee)';
                        alertBox.style.color = 'var(--veranda-blue)';
                        alertBox.style.borderColor = 'var(--veranda-blue)';
                        alertBox.textContent = 'Đăng nhập thành công! Đang chuyển hướng...';
                    }
                    setTimeout(() => {
                        window.location.href = data.redirect || 'index.php';
                    }, 500);
                } else {
                    if (alertBox) {
                        alertBox.className = 'alert alert-danger py-2 fs-7 mb-3 d-block';
                        alertBox.textContent = data.message || 'Đăng nhập thất bại!';
                    }
                }
            })
            .catch(err => {
                console.error(err);
                if (alertBox) {
                    alertBox.className = 'alert alert-danger py-2 fs-7 mb-3 d-block';
                    alertBox.textContent = 'Có lỗi xảy ra, vui lòng thử lại!';
                }
            });
        });
    }
});
</script>

<?php
// 3. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>