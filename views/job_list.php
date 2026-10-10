<?php
// Bật session để kiểm tra trạng thái đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['ho_ten'] ?? 'Tài khoản';

// Lấy từ khóa và địa điểm tìm kiếm từ URL (nếu có từ trang chủ truyền sang)
$keyword = $_GET['keyword'] ?? '';
$location = $_GET['location'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Việc Làm – Jobio</title>
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

        /* Header Tìm Kiếm */
        .search-page-header {
            background: linear-gradient(135deg, #0a2540 0%, #12518e 100%);
            color: white;
            padding: 35px 0;
        }
        .search-box-inner {
            background: #ffffff;
            border-radius: 50px;
            padding: 6px 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .search-input { border: none; box-shadow: none !important; font-size: 14.5px; }
        .search-select { border: none; border-left: 1px solid #e2e8f0; border-radius: 0; box-shadow: none !important; font-size: 14px; color: #555; }

        /* Tiêu đề section */
        .section-title-main { font-weight: 800; color: #0d3b66; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Thẻ việc làm đồng bộ Tông màu Jobio */
        .job-featured-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            transition: all 0.25s ease-in-out;
            height: 100%;
        }
        .job-featured-card:hover {
            box-shadow: 0 8px 20px rgba(13, 59, 102, 0.08);
            border-color: #0284c7;
            transform: translateY(-3px);
        }
        .job-logo-box {
            width: 70px;
            height: 70px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d3b66;
            font-size: 26px;
            transition: all 0.25s ease;
        }
        .job-featured-card:hover .job-logo-box {
            background-color: #e7f1ff;
            border-color: #0284c7;
            color: #0284c7;
        }
        .job-title {
            font-size: 16px;
            font-weight: 700;
            color: #0d3b66;
            margin-bottom: 3px;
            line-height: 1.3;
            text-decoration: none;
            display: block;
        }
        .job-title:hover { color: #0284c7; }
        .job-company {
            font-size: 13.5px;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .job-salary {
            font-size: 14px;
            font-weight: 700;
            color: #e08e0b;
            margin-bottom: 3px;
        }
        .job-location {
            font-size: 13px;
            color: #6c757d;
        }
        .job-heart-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            color: #adb5bd;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .job-heart-btn:hover {
            color: #dc3545;
            transform: scale(1.2);
        }
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

                    <?php if (!$isLoggedIn): ?>
                        <!-- CHƯA ĐĂNG NHẬP -->
                        <li class="nav-item">
                            <a class="nav-link active fw-semibold text-primary" href="job_list.php"><i class="bi bi-briefcase me-1"></i> Việc làm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="index.php#top-employers"><i class="bi bi-building me-1"></i> Nhà tuyển dụng</a>
                        </li>

                    <?php elseif ($userRole === 'employer'): ?>
                        <!-- ĐÃ ĐĂNG NHẬP: NHÀ TUYỂN DỤNG -->
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-primary" href="employer_dashboard.php">
                                <i class="bi bi-plus-circle me-1"></i> Quản lý tin tuyển dụng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-success" href="manage_applicants.php">
                                <i class="bi bi-people me-1"></i> Quản lý trạng thái tuyển dụng
                            </a>
                        </li>

                    <?php elseif ($userRole === 'candidate'): ?>
                        <!-- ĐÃ ĐĂNG NHẬP: ỨNG VIÊN -->
                        <li class="nav-item">
                            <a class="nav-link active fw-semibold text-primary" href="job_list.php"><i class="bi bi-briefcase me-1"></i> Việc làm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="my_applications.php">
                                <i class="bi bi-file-earmark-check me-1"></i> Hồ sơ đã ứng tuyển
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- NÚT TÀI KHOẢN / ĐĂNG NHẬP GÓC PHẢI -->
                <div class="d-flex gap-2 align-items-center">
                    <?php if (!$isLoggedIn): ?>
                        <a href="login.php" class="btn btn-orange btn-sm px-3 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                        </a>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-plus me-1"></i> Đăng ký
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="register.php?role=candidate"><i class="bi bi-person me-2"></i>Tài khoản Ứng viên</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="register.php?role=employer"><i class="bi bi-building me-2"></i>Tài khoản Nhà tuyển dụng</a></li>
                            </ul>
                        </div>

                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-light border btn-sm dropdown-toggle fw-semibold d-flex align-items-center gap-2 px-3" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-6 text-primary"></i>
                                <span><?= htmlspecialchars($userName) ?></span>
                                <span class="badge bg-secondary-subtle text-dark border ms-1" style="font-size: 10px;">
                                    <?= $userRole === 'employer' ? 'Nhà tuyển dụng' : 'Ứng viên' ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <?php if ($userRole === 'employer'): ?>
                                    <li><a class="dropdown-item" href="employer_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Bảng điều khiển NTD</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="my_applications.php"><i class="bi bi-clock-history me-2"></i>Lịch sử nộp CV</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger fw-semibold" href="../api/auth.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. THANH LỌC TÌM KIẾM ĐẦU TRANG -->
    <section class="search-page-header">
        <div class="container">
            <form action="job_list.php" method="GET" id="search-job-form">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="search-box-inner d-flex align-items-center">
                            <i class="bi bi-search text-muted fs-5 ms-2 me-2"></i>
                            <input type="text" name="keyword" id="keyword" class="form-control search-input" 
                                   placeholder="Tên công việc, vị trí, kỹ năng tuyển dụng..." 
                                   value="<?= htmlspecialchars($keyword) ?>">
                            
                            <div class="d-none d-md-block flex-shrink-0 me-2" style="width: 200px;">
                                <select class="form-select search-select" name="location" id="location">
                                    <option value="">📍 Tất cả địa điểm</option>
                                    <option value="Cần Thơ" <?= $location === 'Cần Thơ' ? 'selected' : '' ?>>Cần Thơ</option>
                                    <option value="TP.HCM" <?= $location === 'TP.HCM' ? 'selected' : '' ?>>TP. Hồ Chí Minh</option>
                                    <option value="Hà Nội" <?= $location === 'Hà Nội' ? 'selected' : '' ?>>Hà Nội</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold flex-shrink-0">
                                <i class="bi bi-search me-1"></i> Lọc kết quả
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- 3. DANH SÁCH TẤT CẢ VIỆC LÀM -->
    <section class="py-5">
        <div class="container">
            
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h4 class="section-title-main fs-5 m-0"><i class="bi bi-briefcase-fill text-primary me-2"></i>TẤT CẢ VIỆC LÀM TUYỂN DỤNG</h4>
                    <small class="text-muted">Tìm kiếm các cơ hội nghề nghiệp tốt nhất tại Jobio</small>
                </div>
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2 rounded-pill">
                    <?= !empty($keyword) ? 'Kết quả cho: "' . htmlspecialchars($keyword) . '"' : 'Tất cả vị trí' ?>
                </span>
            </div>

            <!-- Khối chứa danh sách việc làm (Có sẵn dữ liệu mẫu khi chưa gọi API) -->
            <div id="job-list" class="row g-3">
                
                <!-- Card 1 -->
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-laptop"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=1" class="job-title">Lập Trình Viên PHP / MySQL</a>
                            <div class="job-company">Công Ty TNHH Công Nghệ Jobio</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>10 - 15 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=2" class="job-title">Chuyên Viên Marketing Online</a>
                            <div class="job-company">Tập Đoàn Truyền Thông ĐBSCL</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>8 - 12 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Cái Răng, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-calculator"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=3" class="job-title">Kế Toán Tổng Hợp</a>
                            <div class="job-company">Ngân Hàng Thương Mại Cần Thơ</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>9 - 11 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Bình Thủy, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-headset"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=4" class="job-title">Nhân Viên Chăm Sóc Khách Hàng</a>
                            <div class="job-company">Trung Tâm Dịch Vụ Khách Hàng Jobio</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>7 - 9 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-code-slash"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=5" class="job-title">Lập Trình Viên Frontend (ReactJS / VueJS)</a>
                            <div class="job-company">Công Ty Giải Pháp Phần Mềm Mekong</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>12 - 18 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-person-gear"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=6" class="job-title">Chuyên Viên Hành Chính Nhân Sự</a>
                            <div class="job-company">Tập Đoàn Nông Nghiệp Xanh</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>8 - 10 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ô Môn, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

            </div>

            <!-- Nút kêu gọi Đăng ký dành cho người dùng chưa đăng nhập -->
            <?php if (!$isLoggedIn): ?>
            <div class="mt-5 p-4 bg-white border rounded-3 text-center shadow-sm">
                <h5 class="fw-bold text-dark mb-2">Bạn muốn nộp hồ sơ ứng tuyển vào các công việc trên?</h5>
                <p class="text-muted fs-7 mb-3">Đăng ký tài khoản Ứng viên Jobio chỉ trong 1 phút để ứng tuyển trực tiếp đến nhà tuyển dụng.</p>
                <a href="register.php?role=candidate" class="btn btn-primary btn-sm px-4 fw-bold">
                    <i class="bi bi-person-plus me-1"></i> Đăng ký tài khoản ngay
                </a>
            </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- 4. FOOTER CHUẨN DỰ ÁN & THƯƠNG HIỆU JOBIO -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h5 class="fw-bold mb-0 text-white">JOBIO</h5>
                    <p class="small text-white-50 mb-1"><i>Input Talent, Output Success</i></p>
                    <small class="text-white-50">Dự án Phát triển Hệ thống Web (CT428)</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-white-50 d-block">Quản lý thực thể: NguoiDung, TinTuyenDung, HoSoUngTuyen</small>
                    <small class="text-white-50">&copy; 2026 Jobio Recruitment Portal. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script Bootstrap & JS dự án -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/job_board.js"></script>
</body>
</html>