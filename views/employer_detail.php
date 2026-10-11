<?php
// Bật session để kiểm tra trạng thái đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['ho_ten'] ?? 'Tài khoản';

// Lấy ID Nhà tuyển dụng từ URL
$employerId = $_GET['id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Nhà Tuyển Dụng – Jobio</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png?v=2">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Arial, sans-serif; }
        
        /* Navbar */
        .navbar-brand { font-weight: 900; font-size: 22px; color: #0d3b66 !important; letter-spacing: 0.5px; }
        .brand-slogan-sm { font-size: 11px; color: #6c757d; font-style: italic; display: block; }
        .nav-link { font-weight: 600; color: #333 !important; font-size: 14px; margin-right: 5px; }
        .nav-link:hover { color: #0284c7 !important; }
        .btn-orange { background-color: #f39c12; color: #fff; font-weight: 600; }
        .btn-orange:hover { background-color: #e08e0b; color: #fff; }

        /* Khung thông báo Yêu cầu đăng nhập (Lock Box) */
        .auth-required-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 45px 30px;
            box-shadow: 0 10px 30px rgba(13, 59, 102, 0.08);
            max-width: 620px;
            margin: 50px auto;
            text-align: center;
        }
        .lock-icon-box {
            width: 80px;
            height: 80px;
            background-color: #fef3c7;
            color: #d97706;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin: 0 auto 20px auto;
        }

        /* Banner & Profile Doanh Nghiệp khi đã đăng nhập */
        .company-cover {
            height: 160px;
            background: linear-gradient(135deg, #0a2540 0%, #12518e 100%);
        }
        .company-header-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 25px 30px;
            margin-top: -50px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }
        .company-logo-large {
            width: 95px;
            height: 95px;
            background-color: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d3b66;
            font-size: 42px;
            flex-shrink: 0;
        }

        /* Thẻ việc làm */
        .job-featured-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.25s ease-in-out;
            height: 100%;
        }
        .job-featured-card:hover {
            box-shadow: 0 8px 20px rgba(13, 59, 102, 0.08);
            border-color: #0284c7;
            transform: translateY(-3px);
        }
        .job-title { font-size: 16px; font-weight: 700; color: #0d3b66; text-decoration: none; display: block; }
        .job-title:hover { color: #0284c7; }
        .job-salary { font-size: 14px; font-weight: 700; color: #e08e0b; }
    </style>
</head>
<body>

    <!-- 1. THANH MENU (HEADER TÍCH HỢP PHÂN QUYỀN) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../assets/images/icon.png?v=2" alt="Logo Jobio" width="40" height="40" class="me-2">
                <div>
                    <span class="lh-1">JOBIO</span>
                    <span class="brand-slogan-sm">Input Talent, Output Success</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="index.php"><i class="bi bi-house-door me-1"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="job_list.php"><i class="bi bi-briefcase me-1"></i> Việc làm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold text-primary" href="employers.php"><i class="bi bi-building me-1"></i> Nhà tuyển dụng</a>
                    </li>
                </ul>

                <!-- NÚT TÀI KHOẢN / ĐĂNG NHẬP GÓC PHẢI -->
                <div class="d-flex gap-2 align-items-center">
                    <?php if (!$isLoggedIn): ?>
                        <a href="login.php" class="btn btn-orange btn-sm px-3 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                        </a>
                        <a href="register.php?role=candidate" class="btn btn-outline-primary btn-sm fw-semibold">
                            <i class="bi bi-person-plus me-1"></i> Đăng ký
                        </a>
                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-light border btn-sm dropdown-toggle fw-semibold d-flex align-items-center gap-2 px-3" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-6 text-primary"></i>
                                <span><?= htmlspecialchars($userName) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item text-danger fw-semibold" href="../api/auth.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. NỘI DUNG CHÍNH (XỬ LÝ ĐIỀU KIỆN ĐĂNG NHẬP) -->
    <?php if (!$isLoggedIn): ?>
        <!-- ================= KHU VỰC 1: KHI CHƯA ĐĂNG NHẬP ================= -->
        <div class="container py-5">
            <div class="auth-required-card">
                <div class="lock-icon-box">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                
                <h4 class="fw-bold text-dark mb-2">Vui lòng Đăng nhập hoặc Đăng ký</h4>
                <p class="text-muted fs-6 mb-4">
                    Bạn cần có tài khoản trên hệ thống **Jobio** để xem chi tiết hồ sơ doanh nghiệp và danh sách các cơ hội việc làm liên quan.
                </p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="login.php?redirect=employer_detail.php?id=<?= $employerId ?>" class="btn btn-orange px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập ngay
                    </a>
                    
                    <a href="register.php?role=candidate" class="btn btn-outline-primary px-4 py-2 fw-bold">
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
                            <span><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</span>
                            <span><i class="bi bi-envelope text-primary me-1"></i>contact@jobio.vn</span>
                            <span><i class="bi bi-globe text-primary me-1"></i>www.jobio.vn</span>
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
                    <h4 class="fw-bold text-uppercase m-0" style="color: #0d3b66;">VIỆC LÀM CÙNG CÔNG TY</h4>
                    <span class="badge bg-primary px-3 py-2 rounded-pill">Đang tuyển 2 vị trí</span>
                </div>

                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <div class="job-featured-card">
                            <div class="flex-grow-1 pe-3">
                                <a href="job_detail.php?id=1" class="job-title">Lập Trình Viên PHP / MySQL</a>
                                <div class="text-muted small my-1"><i class="bi bi-calendar3 me-1"></i>Hạn nộp: 30/11/2026</div>
                                <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>10 - 15 triệu</div>
                            </div>
                            <a href="job_detail.php?id=1" class="btn btn-outline-primary btn-sm rounded-pill px-3">
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
                            <a href="job_detail.php?id=4" class="btn btn-outline-primary btn-sm rounded-pill px-3">
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

    <!-- 3. FOOTER -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0">JOBIO</h5>
                    <small class="text-white-50">Input Talent, Output Success</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-white-50">&copy; 2026 Jobio Recruitment Portal. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>