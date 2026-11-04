<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/auth_check.php';

// 2. LẤY ID NHÀ TUYỂN DỤNG TỪ URL
$employerId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// 3. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Chi Tiết Nhà Tuyển Dụng";
require_once __DIR__ . '/../include/header.php';
?>

<!-- NỘI DUNG CHÍNH (XỬ LÝ ĐIỀU KIỆN ĐĂNG NHẬP) -->
<?php if (!$isLoggedIn): ?>
    <!-- ================= KHU VỰC 1: KHI CHƯA ĐĂNG NHẬP ================= -->
    <div class="container py-5">
        <div class="auth-required-card">
            <div class="lock-icon-box">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            
            <h4 class="fw-bold text-dark mb-2">Vui lòng Đăng nhập hoặc Đăng ký</h4>
            <p class="text-muted fs-6 mb-4">
                Bạn cần có tài khoản trên hệ thống <strong>Jobio</strong> để xem chi tiết hồ sơ doanh nghiệp và danh sách các cơ hội việc làm liên quan.
            </p>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="login.php?redirect=employer_detail.php?id=<?= $employerId ?>" class="btn btn-rose px-4 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập ngay
                </a>
                
                <a href="register.php?role=candidate" class="btn btn-outline-rose px-4 py-2 fw-semibold">
                    <i class="bi bi-person-plus me-1"></i> Đăng ký tài khoản Ứng viên
                </a>
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="employers.php" class="text-decoration-none text-muted small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách nhà tuyển dụng
                </a>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- ================= KHU VỰC 2: KHI ĐÃ ĐĂNG NHẬP ================= -->
    <div class="company-cover"></div>

    <div class="container mb-5">
        <div class="company-header-card">
            <div class="d-md-flex align-items-center gap-4">
                <div class="company-logo-large mb-3 mb-md-0">
                    <i class="bi bi-building"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold text-dark mb-2">Công Ty TNHH Công Nghệ Jobio</h3>
                    <div class="d-flex flex-wrap gap-3 text-muted fs-7 mb-2">
                        <span><i class="bi bi-geo-alt text-cupid me-1"></i>Ninh Kiều, Cần Thơ</span>
                        <span><i class="bi bi-envelope text-cupid me-1"></i>contact@jobio.vn</span>
                        <span><i class="bi bi-globe text-cupid me-1"></i>www.jobio.vn</span>
                    </div>
                    <p class="text-secondary fs-7 m-0">
                        Jobio là đơn vị hàng đầu trong việc kết nối nguồn nhân lực chất lượng cao với các doanh nghiệp uy tín tại khu vực ĐBSCL.
                    </p>
                </div>
            </div>
        </div>

        <!-- DANH SÁCH VIỆC LÀM DO DOANH NGHIỆP NÀY ĐĂNG -->
        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h4 class="fw-bold text-uppercase m-0 text-dark">VIỆC LÀM CÙNG CÔNG TY</h4>
                <span class="badge bg-lychee text-cupid px-3 py-2" style="border-radius: 6px 2px 6px 2px;">Đang tuyển 2 vị trí</span>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="flex-grow-1 pe-3">
                            <a href="job_detail.php?id=1" class="job-title">Lập Trình Viên PHP / MySQL</a>
                            <div class="text-muted small my-1"><i class="bi bi-calendar3 me-1"></i>Hạn nộp: 30/11/2026</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>10 - 15 triệu</div>
                        </div>
                        <a href="job_detail.php?id=1" class="btn btn-outline-rose btn-sm px-3">
                            Xem việc làm
                        </a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="flex-grow-1 pe-3">
                            <a href="job_detail.php?id=4" class="job-title">Nhân Viên Chăm Sóc Khách Hàng</a>
                            <div class="text-muted small my-1"><i class="bi bi-calendar3 me-1"></i>Hạn nộp: 15/12/2026</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>7 - 9 triệu</div>
                        </div>
                        <a href="job_detail.php?id=4" class="btn btn-outline-rose btn-sm px-3">
                            Xem việc làm
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-start">
                <a href="employers.php" class="btn btn-outline-secondary btn-sm fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách nhà tuyển dụng
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// 4. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>