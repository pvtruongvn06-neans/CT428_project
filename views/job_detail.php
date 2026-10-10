<?php
// Bật session để kiểm tra trạng thái đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['ho_ten'] ?? 'Tài khoản';

// Lấy ID việc làm từ URL
$jobId = $_GET['id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Việc Làm – Jobio</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png?v=2">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Arial, sans-serif; }
        
        /* Navbar */
        .navbar-brand { font-weight: 900; font-size: 22px; color: #0d3b66 !important; letter-spacing: 0.5px; }
        .brand-slogan-sm { font-size: 11px; color: #6c757d; font-weight: 500; font-style: italic; display: block; }
        .nav-link { font-weight: 600; color: #333 !important; font-size: 14px; margin-right: 5px; }
        .nav-link:hover { color: #0284c7 !important; }
        .btn-orange { background-color: #f39c12; color: #fff; font-weight: 600; }
        .btn-orange:hover { background-color: #e08e0b; color: #fff; }

        /* Khung thông báo Yêu cầu đăng nhập */
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

        /* Thẻ chi tiết công việc khi đã đăng nhập */
        .job-detail-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        .company-logo-detail {
            width: 85px;
            height: 85px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d3b66;
            font-size: 36px;
            flex-shrink: 0;
        }
        .job-detail-title { font-size: 24px; font-weight: 800; color: #0d3b66; }
        .job-detail-salary { font-size: 18px; font-weight: 700; color: #e08e0b; }
        .section-sub-title { font-weight: 700; color: #0d3b66; border-left: 4px solid #0d3b66; padding-left: 10px; }
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
                        <a class="nav-link active fw-semibold text-primary" href="job_list.php"><i class="bi bi-briefcase me-1"></i> Việc làm</a>
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
                                <li><a class="dropdown-item" href="my_applications.php"><i class="bi bi-clock-history me-2"></i>Lịch sử nộp CV</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger fw-semibold" href="../api/auth.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. NỘI DUNG CHÍNH (XỬ LÝ ĐIỀU KIỆN ĐĂNG NHẬP) -->
    <div class="container py-5">

        <?php if (!$isLoggedIn): ?>
            <!-- ================= KHU VỰC 1: KHI CHƯA ĐĂNG NHẬP ================= -->
            <div class="auth-required-card">
                <div class="lock-icon-box">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                
                <h4 class="fw-bold text-dark mb-2">Vui lòng Đăng nhập hoặc Đăng ký</h4>
                <p class="text-muted fs-6 mb-4">
                    Bạn cần có tài khoản trên hệ thống **Jobio** để xem toàn bộ thông tin yêu cầu tuyển dụng và thực hiện nộp hồ sơ CV trực tuyến.
                </p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="login.php?redirect=job_detail.php?id=<?= $jobId ?>" class="btn btn-orange px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập ngay
                    </a>
                    
                    <a href="register.php?role=candidate" class="btn btn-outline-primary px-4 py-2 fw-bold">
                        <i class="bi bi-person-plus me-1"></i> Đăng ký tài khoản Ứng viên
                    </a>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="job_list.php" class="text-decoration-none text-muted small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách việc làm
                    </a>
                </div>
            </div>

        <?php else: ?>
            <!-- ================= KHU VỰC 2: KHI ĐÃ ĐĂNG NHẬP ================= -->
            <div class="row g-4">
                
                <!-- Cột trái: Chi tiết việc làm -->
                <div class="col-lg-8">
                    <div class="job-detail-card mb-4">
                        <div class="d-md-flex align-items-center gap-3 mb-4">
                            <div class="company-logo-detail mb-3 mb-md-0">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <h1 class="job-detail-title mb-1">Lập Trình Viên PHP / MySQL</h1>
                                <div class="text-muted fw-semibold mb-2">Công Ty TNHH Công Nghệ Jobio</div>
                                <div class="job-detail-salary"><i class="bi bi-cash-stack me-1"></i>10 - 15 triệu / tháng</div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 py-3 border-top border-bottom text-muted small mb-4">
                            <span><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</span>
                            <span><i class="bi bi-clock me-1"></i>Toàn thời gian</span>
                            <span><i class="bi bi-calendar-event me-1"></i>Hạn nộp: 30/11/2026</span>
                        </div>

                        <!-- Mô tả công việc -->
                        <div class="mb-4">
                            <h5 class="section-sub-title mb-3">Mô tả công việc</h5>
                            <ul class="text-secondary lh-lg">
                                <li>Tham gia phát triển và bảo trì các dự án Web Application trên nền tảng PHP / MySQL.</li>
                                <li>Phối hợp cùng team Frontend để tích hợp giao diện HTML/CSS/JS qua chuẩn RESTful API.</li>
                                <li>Tối ưu hóa câu lệnh truy vấn CSDL MySQL và đảm bảo tính bảo mật hệ thống.</li>
                                <li>Thực hiện các công việc khác theo sự phân công của Quản lý dự án.</li>
                            </ul>
                        </div>

                        <!-- Yêu cầu ứng viên -->
                        <div class="mb-4">
                            <h5 class="section-sub-title mb-3">Yêu cầu ứng viên</h5>
                            <ul class="text-secondary lh-lg">
                                <li>Thành thạo ngôn ngữ PHP, nắm vững kiến thức về lập trình hướng đối tượng (OOP).</li>
                                <li>Có kinh nghiệm làm việc với CSDL MySQL (PDO, Prepared Statements, Indexing).</li>
                                <li>Biết sử dụng Git, HTML5, CSS3, JavaScript / AJAX / Bootstrap 5.</li>
                                <li>Có tư duy logic tốt, chủ động trong công việc và chịu được áp lực tiến độ.</li>
                            </ul>
                        </div>

                        <!-- Quyền lợi -->
                        <div class="mb-4">
                            <h5 class="section-sub-title mb-3">Quyền lợi được hưởng</h5>
                            <ul class="text-secondary lh-lg">
                                <li>Mức lương cạnh tranh: 10 - 15 triệu/tháng + thưởng theo hiệu suất dự án.</li>
                                <li>Được hưởng đầy đủ chế độ BHYT, BHXH, BHTN theo Luật lao động.</li>
                                <li>Môi trường làm việc trẻ trung, năng động, trang thiết bị hiện đại.</li>
                                <li>Được tham gia các khóa đào tạo nâng cao chuyên môn hàng năm.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Box nộp CV & Thông tin công ty -->
                <div class="col-lg-4">
                    <div class="job-detail-card sticky-top" style="top: 90px;">
                        <h5 class="fw-bold text-dark mb-3">Ứng tuyển vị trí này</h5>
                        <p class="text-muted small mb-4">Gửi đường dẫn CV / Hồ sơ ứng tuyển của bạn trực tiếp tới Nhà tuyển dụng.</p>

                        <!-- Nút kích hoạt Modal nộp CV -->
                        <button type="button" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3 shadow-sm mb-3" data-bs-toggle="modal" data-bs-target="#applyModal">
                            <i class="bi bi-send me-1"></i> Nộp hồ sơ ứng tuyển
                        </button>

                        <a href="job_list.php" class="btn btn-outline-secondary w-100 py-2 fw-semibold btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Xem việc làm khác
                        </a>

                        <hr class="my-4">

                        <h6 class="fw-bold text-dark mb-2">Thông tin nhà tuyển dụng</h6>
                        <div class="small text-muted mb-2"><i class="bi bi-building me-2"></i>Công Ty TNHH Công Nghệ Jobio</div>
                        <div class="small text-muted mb-2"><i class="bi bi-geo-alt me-2"></i>123 Đường 3/2, Q. Ninh Kiều, Cần Thơ</div>
                        <div class="small text-muted"><i class="bi bi-envelope me-2"></i>hr@jobio.vn</div>
                    </div>
                </div>

            </div>
        <?php endif; ?>

    </div>

    <!-- MODAL FORM NỘP CV (HIỆN KHI ĐÃ ĐĂNG NHẬP VÀ BẤM NỘP CV) -->
    <?php if ($isLoggedIn): ?>
    <div class="modal fade" id="applyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Nộp Hồ Sơ Ứng Tuyển</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="apply-form" action="../api/apply.php" method="POST">
                    <div class="modal-body p-4">
                        <input type="hidden" name="job_id" value="<?= $jobId ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Họ và tên ứng viên</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($userName) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Đường dẫn Google Drive / Link CV online <span class="text-danger">*</span></label>
                            <input type="url" name="cv_url" class="form-control" placeholder="https://drive.google.com/file/d/your-cv..." required>
                            <div class="form-text">Hãy đảm bảo bạn đã mở quyền xem công khai cho link CV này.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Ghi chú cho nhà tuyển dụng (không bắt buộc)</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Giới thiệu ngắn gọn về bản thân hoặc kinh nghiệm của bạn..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">Xác nhận nộp CV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- 3. FOOTER CHUẨN DỰ ÁN -->
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