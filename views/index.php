<?php
// 1. Khởi tạo session để kiểm tra trạng thái đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy thông tin người dùng từ Session
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? ''; // 'candidate' hoặc 'employer'
$userName = $_SESSION['ho_ten'] ?? 'Tài khoản';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobio – Input Talent, Output Success</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png?v=2">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">


</head>
<body>

    <!-- 1. THANH MENU (HEADER TÍCH HỢP PHÂN QUYỀN) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
        <div class="container">
            <!-- Brand Logo -->
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
                
                <!-- MENU ĐIỀU HƯỚNG THEO VAI TRÒ -->
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="index.php"><i class="bi bi-house-door me-1"></i> Trang chủ</a>
                    </li>

                    <?php if (!$isLoggedIn): ?>
                        <!-- CHƯA ĐĂNG NHẬP -->
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="job_list.php"><i class="bi bi-briefcase me-1"></i> Việc làm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="employers.php"><i class="bi bi-building me-1"></i> Nhà tuyển dụng</a>
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
                            <a class="nav-link fw-semibold" href="job_list.php"><i class="bi bi-briefcase me-1"></i> Việc làm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-primary" href="my_applications.php">
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

    <!-- 2. KHU VỰC HERO BANNER (ĐIỀU HƯỚNG TÌM KIẾM SANG job_list.php) -->
    <section class="hero-banner text-center">
        <div class="container">
            <h1 class="hero-brand-name mb-1">JOBIO</h1>
            <p class="hero-slogan mb-4">Input Talent, Output Success</p>
            
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <form action="job_list.php" method="GET">
                        <div class="search-container d-flex align-items-center">
                            <i class="bi bi-search text-muted fs-5 ms-2 me-2"></i>
                            <input type="text" name="keyword" id="keyword" class="form-control search-input" placeholder="Tên công việc, vị trí, kỹ năng tuyển dụng...">
                            
                            <div class="d-none d-md-block flex-shrink-0 me-2" style="width: 190px;">
                                <select class="form-select search-select" name="location" id="location">
                                    <option value="">📍 Tất cả địa điểm</option>
                                    <option value="Cần Thơ">Cần Thơ</option>
                                    <option value="TP.HCM">TP. Hồ Chí Minh</option>
                                    <option value="Hà Nội">Hà Nội</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold flex-shrink-0">
                                <i class="bi bi-search me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. KHỐI NỘI DUNG 1: VÌ SAO NÊN CHỌN JOBIO? -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="section-title-main">Vì sao nên chọn Jobio?</h3>
                <p class="section-subtitle-main">Giải pháp kết nối tuyển dụng hiệu quả, trực quan và tối ưu nhất</p>
            </div>
            
            <div class="row g-4 text-center">
                <div class="col-md-4 feature-card">
                    <div class="p-3">
                        <div class="feature-icon-box">
                            <i class="bi bi-journal-plus fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">ĐĂNG TIN TUYỂN DỤNG</h6>
                        <p class="text-muted fs-7">Nhà tuyển dụng khởi tạo và quản lý các tin tuyển dụng dễ dàng với thông tin vị trí, yêu cầu và hạn nộp rõ ràng.</p>
                    </div>
                </div>
                <div class="col-md-4 feature-card">
                    <div class="p-3">
                        <div class="feature-icon-box">
                            <i class="bi bi-file-earmark-text fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">NỘP HỒ SƠ ỨNG TUYỂN</h6>
                        <p class="text-muted fs-7">Ứng viên dễ dàng tra cứu công việc phù hợp và gửi đường dẫn CV trực tiếp đến doanh nghiệp chỉ với 1 thao tác.</p>
                    </div>
                </div>
                <div class="col-md-4 feature-card">
                    <div class="p-3">
                        <div class="feature-icon-box">
                            <i class="bi bi-clock-history fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">THEO DÕI TRẠNG THÁI</h6>
                        <p class="text-muted fs-7">Cập nhật tiến độ xử lý hồ sơ minh bạch giữa Ứng viên và Nhà tuyển dụng (Đã nộp, Đã duyệt, Từ chối).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- 3. KHỐI THỐNG KÊ HỆ THỐNG -->
    <section class="stats-section py-4">
        <div class="container">
            <div class="row g-3 text-center">
                <div class="col-md-4">
                    <div class="p-2">
                        <div class="stats-number"><i class="bi bi-briefcase text-primary me-2"></i>100+</div>
                        <div class="stats-label">Tin Tuyển Dụng Đang Mở</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-2 border-start border-end">
                        <div class="stats-number text-success"><i class="bi bi-file-earmark-person me-2"></i>500+</div>
                        <div class="stats-label">Hồ Sơ CV / Ứng Viên</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-2">
                        <div class="stats-number text-warning"><i class="bi bi-building me-2"></i>50+</div>
                        <div class="stats-label">Nhà Tuyển Dụng Đồng Hành</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<!-- 5. KHỐI NHÀ TUYỂN DỤNG HÀNG ĐẦU (CHUYỂN HƯỚNG SANG employer_detail.php) -->
<section id="top-employers" class="py-5 bg-light border-bottom">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="section-title-main">Nhà tuyển dụng hàng đầu</h3>
        </div>

        <!-- 4 Thẻ Công ty nằm ngang -->
        <div class="row g-3">
            
            <!-- Ô 1 -->
            <div class="col-md-3 col-sm-6">
                <a href="employer_detail.php?id=1" class="text-decoration-none">
                    <div class="top-employer-card">
                        <div class="top-employer-logo"><i class="bi bi-building"></i></div>
                        <div class="top-employer-name">Công Ty Jobio</div>
                        <span class="top-employer-count">5 việc làm</span>
                    </div>
                </a>
            </div>

            <!-- Ô 2 -->
            <div class="col-md-3 col-sm-6">
                <a href="employer_detail.php?id=2" class="text-decoration-none">
                    <div class="top-employer-card">
                        <div class="top-employer-logo"><i class="bi bi-building-gear"></i></div>
                        <div class="top-employer-name">Tập Đoàn ĐBSCL</div>
                        <span class="top-employer-count">3 việc làm</span>
                    </div>
                </a>
            </div>

            <!-- Ô 3 -->
            <div class="col-md-3 col-sm-6">
                <a href="employer_detail.php?id=3" class="text-decoration-none">
                    <div class="top-employer-card">
                        <div class="top-employer-logo"><i class="bi bi-bank"></i></div>
                        <div class="top-employer-name">Ngân Hàng Cần Thơ</div>
                        <span class="top-employer-count">8 việc làm</span>
                    </div>
                </a>
            </div>

            <!-- Ô 4 -->
            <div class="col-md-3 col-sm-6">
                <a href="employer_detail.php?id=4" class="text-decoration-none">
                    <div class="top-employer-card">
                        <div class="top-employer-logo"><i class="bi bi-laptop"></i></div>
                        <div class="top-employer-name">Tech Solution</div>
                        <span class="top-employer-count">4 việc làm</span>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

    <!-- 6. KHỐI NỘI DUNG 3: NGÀNH NGHỀ TRỌNG ĐIỂM -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="section-title-main">Ngành Nghề Trọng Điểm</h3>
                <p class="section-subtitle-main">Hệ thống phân loại công việc rõ ràng giúp bạn dễ dàng lựa chọn</p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-4 feature-card">
                    <div class="p-4 bg-white border rounded-3 shadow-sm h-100">
                        <div class="feature-icon-box">
                            <i class="bi bi-laptop fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">CÔNG NGHỆ THÔNG TIN</h6>
                        <p class="text-muted fs-7 mb-0">Tuyển dụng Lập trình viên, Kiểm thử phần mềm, Quản trị hệ thống với nhiều cơ hội phát triển.</p>
                    </div>
                </div>
                <div class="col-md-4 feature-card">
                    <div class="p-4 bg-white border rounded-3 shadow-sm h-100">
                        <div class="feature-icon-box">
                            <i class="bi bi-graph-up-arrow fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">KINH DOANH & MARKETING</h6>
                        <p class="text-muted fs-7 mb-0">Nhiều vị trí Nhân viên kinh doanh, Chăm sóc khách hàng, Digital Marketing phù hợp mọi cấp độ.</p>
                    </div>
                </div>
                <div class="col-md-4 feature-card">
                    <div class="p-4 bg-white border rounded-3 shadow-sm h-100">
                        <div class="feature-icon-box">
                            <i class="bi bi-calculator fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">KẾ TOÁN & HÀNH CHÍNH</h6>
                        <p class="text-muted fs-7 mb-0">Tìm kiếm công việc Kế toán tổng hợp, Hành chính nhân sự tại Cần Thơ và khu vực ĐBSCL.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- 8. MỤC VIỆC LÀM NỔI BẬT (ĐÃ THÊM SỰ KIỆN CLICK TOÀN THẺ) -->
<section class="py-5">
    <div class="container">
        <h3 class="section-title-main text-center mb-4" style="font-size: 26px;">Việc làm nổi bật</h3>

        <!-- Danh sách 4 việc làm mẫu -->
        <div id="job-list" class="row g-3">
            
            <!-- Ô 1 -->
            <div class="col-md-6">
                <div class="job-featured-card" onclick="window.location.href='job_detail.php?id=1';" style="cursor: pointer;">
                    <div class="job-logo-box"><i class="bi bi-laptop"></i></div>
                    <div class="flex-grow-1 pe-3">
                        <a href="job_detail.php?id=1" class="job-title" onclick="event.stopPropagation();">Lập Trình Viên PHP / MySQL</a>
                        <div class="job-company">Công Ty TNHH Công Nghệ Jobio</div>
                        <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>10 - 15 triệu</div>
                        <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                    </div>
                    <i class="bi bi-heart job-heart-btn" title="Lưu việc làm" onclick="event.stopPropagation();"></i>
                </div>
            </div>

            <!-- Ô 2 -->
            <div class="col-md-6">
                <div class="job-featured-card" onclick="window.location.href='job_detail.php?id=2';" style="cursor: pointer;">
                    <div class="job-logo-box"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="flex-grow-1 pe-3">
                        <a href="job_detail.php?id=2" class="job-title" onclick="event.stopPropagation();">Chuyên Viên Marketing Online</a>
                        <div class="job-company">Tập Đoàn Truyền Thông ĐBSCL</div>
                        <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>8 - 12 triệu</div>
                        <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Cái Răng, Cần Thơ</div>
                    </div>
                    <i class="bi bi-heart job-heart-btn" title="Lưu việc làm" onclick="event.stopPropagation();"></i>
                </div>
            </div>

            <!-- Ô 3 -->
            <div class="col-md-6">
                <div class="job-featured-card" onclick="window.location.href='job_detail.php?id=3';" style="cursor: pointer;">
                    <div class="job-logo-box"><i class="bi bi-calculator"></i></div>
                    <div class="flex-grow-1 pe-3">
                        <a href="job_detail.php?id=3" class="job-title" onclick="event.stopPropagation();">Kế Toán Tổng Hợp</a>
                        <div class="job-company">Ngân Hàng Thương Mại Cần Thơ</div>
                        <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>9 - 11 triệu</div>
                        <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Bình Thủy, Cần Thơ</div>
                    </div>
                    <i class="bi bi-heart job-heart-btn" title="Lưu việc làm" onclick="event.stopPropagation();"></i>
                </div>
            </div>

            <!-- Ô 4 -->
            <div class="col-md-6">
                <div class="job-featured-card" onclick="window.location.href='job_detail.php?id=4';" style="cursor: pointer;">
                    <div class="job-logo-box"><i class="bi bi-headset"></i></div>
                    <div class="flex-grow-1 pe-3">
                        <a href="job_detail.php?id=4" class="job-title" onclick="event.stopPropagation();">Nhân Viên Chăm Sóc Khách Hàng</a>
                        <div class="job-company">Trung Tâm Dịch Vụ Khách Hàng Jobio</div>
                        <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>7 - 9 triệu</div>
                        <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                    </div>
                    <i class="bi bi-heart job-heart-btn" title="Lưu việc làm" onclick="event.stopPropagation();"></i>
                </div>
            </div>

        </div>

        <!-- Chân khối: Link chuyển sang trang job_list.php -->
        <div class="text-end mt-4">
            <a href="job_list.php" class="text-decoration-none fw-semibold" style="color: #0d3b66; font-size: 15px;">
                Xem thêm việc làm mới cập nhật <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

    <!-- 7. KHỐI NỘI DUNG 4: BẮT ĐẦU CÙNG JOBIO -->
    <section class="py-5 bg-light border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="section-title-main">Bắt Đầu Cùng Jobio</h3>
                <p class="section-subtitle-main">Đăng ký tài khoản ngay hôm nay để trải nghiệm dịch vụ</p>
            </div>

            <div class="row g-4 justify-content-center text-center">
                <div class="col-md-5">
                    <div class="p-4 border rounded-3 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-person-circle fs-1 text-primary mb-2"></i>
                            <h5 class="fw-bold text-dark">Dành Cho Ứng Viên</h5>
                            <p class="text-muted fs-7">Tạo tài khoản ứng viên, tìm kiếm việc làm mơ ước và ứng tuyển nhanh chóng.</p>
                        </div>
                        <div class="mt-3">
                            <a href="register.php?role=candidate" class="btn btn-primary btn-sm px-4 fw-bold">Đăng ký Ứng viên</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="p-4 border rounded-3 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-building fs-1 text-warning mb-2"></i>
                            <h5 class="fw-bold text-dark">Dành Cho Nhà Tuyển Dụng</h5>
                            <p class="text-muted fs-7">Đăng ký tài khoản doanh nghiệp để đăng tin tuyển dụng và đón nhận các ứng viên tiềm năng.</p>
                        </div>
                        <div class="mt-3">
                            <a href="register.php?role=employer" class="btn btn-orange btn-sm px-4 fw-bold">Đăng ký Nhà tuyển dụng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- 9. FOOTER CHUẨN DỰ ÁN & THƯƠNG HIỆU JOBIO -->
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