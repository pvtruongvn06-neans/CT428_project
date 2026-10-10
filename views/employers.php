<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['ho_ten'] ?? 'Tài khoản';

$keyword = $_GET['keyword'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhà Tuyển Dụng Hàng Đầu – Jobio</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png?v=2">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Arial, sans-serif; }
        
        .navbar-brand { font-weight: 900; font-size: 22px; color: #0d3b66 !important; }
        .brand-slogan-sm { font-size: 11px; color: #6c757d; font-style: italic; display: block; }
        .nav-link { font-weight: 600; color: #333 !important; font-size: 14px; }
        .btn-orange { background-color: #f39c12; color: #fff; font-weight: 600; }

        .employer-hero {
            background: linear-gradient(135deg, #0a2540 0%, #12518e 100%);
            color: white;
            padding: 40px 0;
        }

        .search-box-inner {
            background: #ffffff;
            border-radius: 50px;
            padding: 6px 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .employer-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px 20px;
            text-align: center;
            transition: all 0.25s ease-in-out;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .employer-card:hover {
            box-shadow: 0 10px 25px rgba(13, 59, 102, 0.1);
            border-color: #0284c7;
            transform: translateY(-4px);
        }
        .employer-logo-box {
            width: 75px;
            height: 75px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            margin: 0 auto 15px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d3b66;
            font-size: 32px;
        }
        .employer-name {
            font-size: 17px;
            font-weight: 700;
            color: #0d3b66;
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .employer-location {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 12px;
        }
        .job-count-badge {
            background-color: #e7f1ff;
            color: #0284c7;
            font-weight: 600;
            font-size: 12.5px;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
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

    <!-- HEADER BANNER & BỘ LỌC CÔNG TY -->
    <section class="employer-hero text-center">
        <div class="container">
            <h2 class="fw-bold mb-2">DANH SÁCH NHÀ TUYỂN DỤNG</h2>
            <p class="text-white-50 mb-4">Khám phá các doanh nghiệp uy tín hàng đầu đang tuyển dụng trên hệ thống Jobio</p>
            
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <form action="employers.php" method="GET">
                        <div class="search-box-inner d-flex align-items-center">
                            <i class="bi bi-search text-muted fs-5 ms-2 me-2"></i>
                            <input type="text" name="keyword" class="form-control border-0 shadow-none" placeholder="Nhập tên công ty, doanh nghiệp cần tìm..." value="<?= htmlspecialchars($keyword) ?>">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold flex-shrink-0">
                                Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- DANH SÁCH CÁC DOANH NGHIỆP -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                
                <!-- Công ty 1 -->
                <div class="col-md-4 col-sm-6">
                    <div class="employer-card">
                        <div>
                            <div class="employer-logo-box"><i class="bi bi-building"></i></div>
                            <h5 class="employer-name">Công Ty TNHH Công Nghệ Jobio</h5>
                            <div class="employer-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                            <span class="job-count-badge"><i class="bi bi-briefcase me-1"></i>5 vị trí tuyển dụng</span>
                        </div>
                        <a href="employer_detail.php?id=1" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100 mt-2">
                            Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Công ty 2 -->
                <div class="col-md-4 col-sm-6">
                    <div class="employer-card">
                        <div>
                            <div class="employer-logo-box"><i class="bi bi-building-gear"></i></div>
                            <h5 class="employer-name">Tập Đoàn Truyền Thông ĐBSCL</h5>
                            <div class="employer-location"><i class="bi bi-geo-alt text-primary me-1"></i>Cái Răng, Cần Thơ</div>
                            <span class="job-count-badge"><i class="bi bi-briefcase me-1"></i>3 vị trí tuyển dụng</span>
                        </div>
                        <a href="employer_detail.php?id=2" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100 mt-2">
                            Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Công ty 3 -->
                <div class="col-md-4 col-sm-6">
                    <div class="employer-card">
                        <div>
                            <div class="employer-logo-box"><i class="bi bi-bank"></i></div>
                            <h5 class="employer-name">Ngân Hàng Thương Mại Cần Thơ</h5>
                            <div class="employer-location"><i class="bi bi-geo-alt text-primary me-1"></i>Bình Thủy, Cần Thơ</div>
                            <span class="job-count-badge"><i class="bi bi-briefcase me-1"></i>8 vị trí tuyển dụng</span>
                        </div>
                        <a href="employer_detail.php?id=3" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100 mt-2">
                            Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Công ty 4 -->
                <div class="col-md-4 col-sm-6">
                    <div class="employer-card">
                        <div>
                            <div class="employer-logo-box"><i class="bi bi-laptop"></i></div>
                            <h5 class="employer-name">Công Ty Giải Pháp Software Tech</h5>
                            <div class="employer-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                            <span class="job-count-badge"><i class="bi bi-briefcase me-1"></i>4 vị trí tuyển dụng</span>
                        </div>
                        <a href="employer_detail.php?id=4" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100 mt-2">
                            Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Công ty 5 -->
                <div class="col-md-4 col-sm-6">
                    <div class="employer-card">
                        <div>
                            <div class="employer-logo-box"><i class="bi bi-basket3"></i></div>
                            <h5 class="employer-name">Tập Đoàn Nông Nghiệp Xanh</h5>
                            <div class="employer-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ô Môn, Cần Thơ</div>
                            <span class="job-count-badge"><i class="bi bi-briefcase me-1"></i>2 vị trí tuyển dụng</span>
                        </div>
                        <a href="employer_detail.php?id=5" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100 mt-2">
                            Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Công ty 6 -->
                <div class="col-md-4 col-sm-6">
                    <div class="employer-card">
                        <div>
                            <div class="employer-logo-box"><i class="bi bi-headset"></i></div>
                            <h5 class="employer-name">Trung Tâm Dịch Vụ Khách Hàng Jobio</h5>
                            <div class="employer-location"><i class="bi bi-geo-alt text-primary me-1"></i>Ninh Kiều, Cần Thơ</div>
                            <span class="job-count-badge"><i class="bi bi-briefcase me-1"></i>6 vị trí tuyển dụng</span>
                        </div>
                        <a href="employer_detail.php?id=6" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold w-100 mt-2">
                            Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
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