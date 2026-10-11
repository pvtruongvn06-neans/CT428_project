<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu đã đăng nhập rồi thì chuyển hướng về Trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Lấy trang cần chuyển hướng sau khi đăng nhập thành công (nếu có)
$redirect = $_GET['redirect'] ?? 'index.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập – Jobio</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png?v=2">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        .auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(13, 59, 102, 0.08);
            overflow: hidden;
        }
        .auth-header {
            background: linear-gradient(135deg, #0a2540 0%, #12518e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .btn-orange { background-color: #f39c12; color: #fff; font-weight: 600; }
        .btn-orange:hover { background-color: #e08e0b; color: #fff; }
    </style>
</head>
<body>

    <!-- NAVBAR NGÀN GỌN -->
    <nav class="navbar navbar-light bg-white border-bottom py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../assets/images/icon.png?v=2" alt="Logo Jobio" width="40" height="40" class="me-2">
                <div>
                    <span class="lh-1 fw-bold text-dark" style="color: #0d3b66 !important;">JOBIO</span>
                    <span class="small text-muted d-block" style="font-size: 11px;">Input Talent, Output Success</span>
                </div>
            </a>
            <a href="index.php" class="text-decoration-none text-muted fw-semibold small">
                <i class="bi bi-arrow-left me-1"></i> Trang chủ
            </a>
        </div>
    </nav>

    <!-- FORM ĐĂNG NHẬP -->
    <div class="container my-auto py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="auth-card">
                    <div class="auth-header">
                        <img src="../assets/images/icon.png?v=2" alt="Logo Jobio" width="55" height="55" class="mb-2">
                        <h4 class="fw-bold m-0">ĐĂNG NHẬP</h4>
                        <small class="text-white-50">Chào mừng bạn quay lại với Jobio</small>
                    </div>
                    
                    <div class="p-4">
                        <!-- Thông báo lỗi/thành công từ JS -->
                        <div id="auth-alert" class="d-none alert py-2 fs-7 mb-3"></div>

                        <form id="login-form" action="../api/auth.php?action=login" method="POST">
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Địa chỉ Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold text-dark small mb-0">Mật khẩu <span class="text-danger">*</span></label>
                                    <a href="#" class="text-decoration-none small text-muted" onclick="alert('Vui lòng liên hệ Quản trị viên để khôi phục mật khẩu.');">Quên mật khẩu?</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-input-label small text-muted" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3 shadow-sm mb-3" style="background-color: #0d3b66; border: none;">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                            </button>
                        </form>

                        <div class="text-center pt-2 border-top">
                            <span class="small text-muted">Chưa có tài khoản?</span>
                            <a href="register.php" class="small fw-semibold text-decoration-none ms-1" style="color: #0284c7;">Đăng ký ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container text-center">
            <small class="text-white-50">&copy; 2026 Jobio Recruitment Portal. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/auth.js"></script>
</body>
</html>