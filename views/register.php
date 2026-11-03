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

// Lấy vai trò mặc định từ URL (candidate hoặc employer)
$role = isset($_GET['role']) ? sanitizeInput($_GET['role']) : 'candidate';
if (!in_array($role, ['candidate', 'employer'])) {
    $role = 'candidate';
}

// 2. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Đăng Ký Tài Khoản";
require_once __DIR__ . '/../include/header.php';
?>

<!-- FORM ĐĂNG KÝ -->
<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="bg-white border shadow-sm" style="border-radius: 12px 2px 12px 2px; border-color: var(--sky-cloud) !important; overflow: hidden;">
                
                <!-- Header Thẻ Đăng Ký Gradient Ocean Pastel -->
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, var(--veranda-blue) 0%, var(--cupid-pink) 100%);">
                    <h4 class="fw-bold m-0">TẠO TÀI KHOẢN MỚI</h4>
                    <small class="text-white-50">Kết nối cơ hội nghề nghiệp cùng Jobio</small>
                </div>

                <div class="p-4">
                    <!-- Chọn Vai trò: Ứng viên hay Nhà tuyển dụng -->
                    <div class="d-flex gap-2 mb-4">
                        <button type="button" 
                                id="btn-role-candidate"
                                class="btn w-50 py-2 fw-semibold <?= $role === 'candidate' ? 'btn-rose' : 'btn-light border' ?>"
                                style="border-radius: 8px 2px 8px 2px;"
                                onclick="switchRole('candidate')">
                            <i class="bi bi-person me-1"></i> Ứng Viên
                        </button>
                        <button type="button" 
                                id="btn-role-employer"
                                class="btn w-50 py-2 fw-semibold <?= $role === 'employer' ? 'btn-rose' : 'btn-light border' ?>"
                                style="border-radius: 8px 2px 8px 2px;"
                                onclick="switchRole('employer')">
                            <i class="bi bi-building me-1"></i> Nhà Tuyển Dụng
                        </button>
                    </div>

                    <!-- Thông báo lỗi/thành công từ JS -->
                    <div id="auth-alert" class="d-none alert py-2 fs-7 mb-3" style="border-radius: 6px 2px 6px 2px;"></div>

                    <form id="register-form" action="../api/auth.php?action=register" method="POST">
                        <input type="hidden" name="vai_tro" id="role-input" value="<?= $role ?>">

                        <!-- Họ và tên / Người đại diện -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small" id="label-fullname">
                                <?= $role === 'employer' ? 'Họ tên người đại diện' : 'Họ và tên' ?> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="ho_ten" class="form-control border-start-0" placeholder="Nguyễn Văn A" required>
                            </div>
                        </div>

                        <!-- Tên công ty (Chỉ hiện khi là Nhà tuyển dụng) -->
                        <div class="mb-3 <?= $role === 'candidate' ? 'd-none' : '' ?>" id="company-field">
                            <label class="form-label fw-semibold text-dark small">Tên Công ty / Doanh nghiệp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-building"></i></span>
                                <input type="text" name="company_name" id="company_name_input" class="form-control border-start-0" placeholder="Công ty TNHH Jobio Việt Nam" <?= $role === 'employer' ? 'required' : '' ?>>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Địa chỉ Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control border-start-0" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="mat_khau" id="password-input" class="form-control border-start-0" placeholder="••••••••" required minlength="6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="confirm_password" id="confirm-password-input" class="form-control border-start-0" placeholder="••••••••" required minlength="6">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-rose w-100 py-2.5 fw-semibold shadow-sm mb-3">
                            <i class="bi bi-person-plus me-1"></i> Đăng ký tài khoản
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <span class="small text-muted">Đã có tài khoản?</span>
                        <a href="login.php" class="small fw-semibold text-decoration-none ms-1 text-cupid">Đăng nhập ngay</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- SCRIPT CHUYỂN ROLE & XỬ LÝ ĐĂNG KÝ VIA API -->
<script>
function switchRole(selectedRole) {
    document.getElementById('role-input').value = selectedRole;

    const companyField  = document.getElementById('company-field');
    const companyInput  = document.getElementById('company_name_input');
    const labelFullname = document.getElementById('label-fullname');
    const btnCandidate  = document.getElementById('btn-role-candidate');
    const btnEmployer   = document.getElementById('btn-role-employer');

    if (selectedRole === 'employer') {
        btnEmployer.className  = 'btn w-50 py-2 fw-semibold btn-rose';
        btnCandidate.className = 'btn w-50 py-2 fw-semibold btn-light border';
        companyField.classList.remove('d-none');
        companyInput.required = true;
        labelFullname.innerHTML = 'Họ tên người đại diện <span class="text-danger">*</span>';
    } else {
        btnCandidate.className = 'btn w-50 py-2 fw-semibold btn-rose';
        btnEmployer.className  = 'btn w-50 py-2 fw-semibold btn-light border';
        companyField.classList.add('d-none');
        companyInput.required = false;
        labelFullname.innerHTML = 'Họ và tên <span class="text-danger">*</span>';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('register-form');
    const alertBox     = document.getElementById('auth-alert');

    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const pass        = document.getElementById('password-input').value;
            const confirmPass = document.getElementById('confirm-password-input').value;

            if (pass !== confirmPass) {
                if (alertBox) {
                    alertBox.className = 'alert alert-danger py-2 fs-7 mb-3 d-block';
                    alertBox.textContent = 'Mật khẩu xác nhận không khớp!';
                }
                return;
            }

            const formData = new FormData(registerForm);

            fetch('../api/auth.php?action=register', {
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
                        alertBox.textContent = 'Đăng ký tài khoản thành công! Đang chuyển hướng tới trang đăng nhập...';
                    }
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1200);
                } else {
                    if (alertBox) {
                        alertBox.className = 'alert alert-danger py-2 fs-7 mb-3 d-block';
                        alertBox.textContent = data.message || 'Đăng ký thất bại!';
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