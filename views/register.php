<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu đã đăng nhập rồi thì chuyển hướng về Trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Lấy vai trò mặc định từ URL (candidate hoặc employer)
$role = $_GET['role'] ?? 'candidate';
if (!in_array($role, ['candidate', 'employer'])) {
    $role = 'candidate';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản – Jobio</title>
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
        .role-tab-btn {
            border: 1px solid #e2e8f0;
            background: #f8f9fa;
            color: #6c757d;
            font-weight: 600;
            padding: 10px;
            width: 50%;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .role-tab-btn.active {
            background: #0d3b66;
            color: #ffffff;
            border-color: #0d3b66;
        }
        .btn-orange { background-color: #f39c12; color: #fff; font-weight: 600; }
        .btn-orange:hover { background-color: #e08e0b; color: #fff; }
    </style>
</head>
<body>

    <!-- NAVBAR NGẮN GỌN -->
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

    <!-- FORM ĐĂNG KÝ -->
    <div class="container my-auto py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <h4 class="fw-bold m-0">TẠO TÀI KHOẢN MỚI</h4>
                        <small class="text-white-50">Kết nối cơ hội nghề nghiệp cùng Jobio</small>
                    </div>
                    
                    <div class="p-4">
                        <!-- Chọn Vai trò: Ứng viên hay Nhà tuyển dụng -->
                        <div class="d-flex gap-2 mb-4">
                            <button type="button" class="role-tab-btn <?= $role === 'candidate' ? 'active' : '' ?>" onclick="switchRole('candidate')">
                                <i class="bi bi-person me-1"></i> Ứng Viên
                            </button>
                            <button type="button" class="role-tab-btn <?= $role === 'employer' ? 'active' : '' ?>" onclick="switchRole('employer')">
                                <i class="bi bi-building me-1"></i> Nhà Tuyển Dụng
                            </button>
                        </div>

                        <!-- Thông báo lỗi/thành công từ JS -->
                        <div id="auth-alert" class="d-none alert py-2 fs-7 mb-3"></div>

                        <form id="register-form" action="../api/auth.php?action=register" method="POST">
                            <input type="hidden" name="role" id="role-input" value="<?= $role ?>">

                            <!-- Họ và tên / Người đại diện -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small" id="label-fullname">
                                    <?= $role === 'employer' ? 'Họ tên người đại diện' : 'Họ và tên' ?> <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" name="fullname" class="form-control" placeholder="Nguyễn Văn A" required>
                                </div>
                            </div>

                            <!-- Tên công ty (Chỉ hiện khi là Nhà tuyển dụng) -->
                            <div class="mb-3 <?= $role === 'candidate' ? 'd-none' : '' ?>" id="company-field">
                                <label class="form-label fw-semibold text-dark small">Tên Công ty / Doanh nghiệp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                                    <input type="text" name="company_name" id="company_name_input" class="form-control" placeholder="Công ty TNHH Jobio Việt Nam" <?= $role === 'employer' ? 'required' : '' ?>>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Địa chỉ Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                                </div>
                            </div>

                            <!-- Mật khẩu -->
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required minlength="6">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-orange w-100 py-2.5 fw-bold rounded-3 shadow-sm mb-3">
                                <i class="bi bi-person-plus me-1"></i> Đăng ký tài khoản
                            </button>
                        </form>

                        <div class="text-center pt-2 border-top">
                            <span class="small text-muted">Đã có tài khoản?</span>
                            <a href="login.php" class="small fw-semibold text-decoration-none ms-1" style="color: #0d3b66;">Đăng nhập</a>
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
    <script>
        // Hàm chuyển đổi linh hoạt vai trò Ứng viên / NTD
        function switchRole(selectedRole) {
            document.getElementById('role-input').value = selectedRole;
            
            const companyField = document.getElementById('company-field');
            const companyInput = document.getElementById('company_name_input');
            const labelFullname = document.getElementById('label-fullname');
            
            const buttons = document.querySelectorAll('.role-tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            if (selectedRole === 'employer') {
                buttons[1].classList.add('active');
                companyField.classList.remove('d-none');
                companyInput.required = true;
                labelFullname.innerHTML = 'Họ tên người đại diện <span class="text-danger">*</span>';
            } else {
                buttons[0].classList.add('active');
                companyField.classList.add('d-none');
                companyInput.required = false;
                labelFullname.innerHTML = 'Họ và tên <span class="text-danger">*</span>';
            }
        }
    </script>
    <script src="../assets/js/auth.js"></script>
</body>
</html>