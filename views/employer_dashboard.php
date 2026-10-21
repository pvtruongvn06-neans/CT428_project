<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. KẾT NỐI CƠ SỞ DỮ LIỆU
require_once __DIR__ . '/../config/database.php';

// 2. KIỂM TRA PHÂN QUYỀN NHÀ TUYỂN DỤNG
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$userRole   = strtolower(trim($_SESSION['vai_tro'] ?? $_SESSION['role'] ?? ''));

$isEmployer = $isLoggedIn && (
    strpos($userRole, 'tuyen') !== false || 
    strpos($userRole, 'employer') !== false || 
    $userRole === 'nha_tuyen_dung'
);

// Nếu chưa đăng nhập hoặc không phải Nhà tuyển dụng thì bảo mật chuyển về trang chủ
if (!$isLoggedIn || !$isEmployer) {
    header('Location: index.php');
    exit;
}

$employerId = $_SESSION['user_id'];
$userName   = $_SESSION['ho_ten'] ?? $_SESSION['user_name'] ?? 'Nhà tuyển dụng';

// 3. TRUY VẤN DỮ LIỆU BÀI ĐĂNG VÀ HỒ SƠ CV
$jobs              = [];
$totalJobs         = 0;
$totalApplications = 0;

if (isset($pdo) && $pdo instanceof PDO) {
    try {
        // Lấy danh sách tin đã đăng của Nhà tuyển dụng & đếm số CV từng tin
        $stmtJobs = $pdo->prepare("
            SELECT t.*, COUNT(h.ID) AS so_luong_cv
            FROM TinTuyenDung t
            LEFT JOIN HoSoUngTuyen h ON (t.ID = h.ID_TinTuyenDung OR t.id = h.id_tin_tuyen_dung)
            WHERE t.ID_NguoiDung = :emp_id OR t.id_nguoi_dung = :emp_id
            GROUP BY t.ID, t.id
            ORDER BY t.ID DESC
        ");
        $stmtJobs->execute([':emp_id' => $employerId]);
        $jobs = $stmtJobs->fetchAll(PDO::FETCH_ASSOC);

        $totalJobs = count($jobs);

        // Đếm tổng số CV đã nộp vào tất cả bài đăng
        $stmtTotalCv = $pdo->prepare("
            SELECT COUNT(h.ID) 
            FROM HoSoUngTuyen h
            JOIN TinTuyenDung t ON (h.ID_TinTuyenDung = t.ID OR h.id_tin_tuyen_dung = t.id)
            WHERE t.ID_NguoiDung = :emp_id OR t.id_nguoi_dung = :emp_id
        ");
        $stmtTotalCv->execute([':emp_id' => $employerId]);
        $totalApplications = (int) $stmtTotalCv->fetchColumn();

    } catch (PDOException $e) {
        error_log("Lỗi Dashboard: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Lý Tuyển Dụng – Jobio</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png?v=2">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* Đảm bảo Nav & Dropdown hiển thị đẹp không bị che khuất */
        nav.navbar { position: sticky !important; top: 0 !important; z-index: 10000 !important; overflow: visible !important; }
        .dropdown-menu { z-index: 10002 !important; position: absolute !important; }
        
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content { flex: 1; }
        
        /* Style Card Thống Kê & Bảng */
        .stat-card { 
            background: #ffffff; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            padding: 24px; 
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        }
        .stat-icon { 
            width: 52px; 
            height: 52px; 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 26px; 
        }
    </style>
</head>
<body>

    <!-- 1. THANH MENU (GIỮ CHÍNH XÁC MẪU NAV BẠN GỬI) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
        <div class="container">
            <!-- LOGO + SLOGAN -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../assets/images/icon.png?v=2" alt="Logo Jobio" width="40" height="40" class="me-2">
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-4 lh-1" style="color: #0b4b8b;">JOBIO</span>
                    <span class="fst-italic text-secondary" style="font-size: 0.75rem;">Input Talent, Output Success</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- CÁC MENU TRANG CHỦ, VIỆC LÀM, NHÀ TUYỂN DỤNG -->
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="index.php"><i class="bi bi-house-door me-1"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="job_list.php"><i class="bi bi-briefcase me-1"></i> Việc làm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="employers.php"><i class="bi bi-building me-1"></i> Nhà tuyển dụng</a>
                    </li>
                </ul>

                <!-- NÚT TÀI KHOẢN / ĐĂNG NHẬP GÓC PHẢI -->
                <div class="d-flex gap-2 align-items-center">
                    <?php if (!$isLoggedIn): ?>
                        <!-- KHI CHƯA ĐĂNG NHẬP -->
                        <a href="login.php" class="btn btn-orange btn-sm px-3 shadow-sm text-white fw-semibold" style="background-color: #f39c12; border: none;">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                        </a>
                        <a href="register.php?role=candidate" class="btn btn-outline-primary btn-sm fw-semibold">
                            <i class="bi bi-person-plus me-1"></i> Đăng ký
                        </a>
                    <?php else: ?>
                        <!-- KHI ĐÃ ĐĂNG NHẬP -->
                        <div class="dropdown">
                            <button class="btn btn-light border btn-sm dropdown-toggle fw-semibold d-flex align-items-center gap-2 px-3 rounded-pill" 
                                    type="button" 
                                    data-bs-toggle="dropdown" 
                                    data-bs-display="static" 
                                    aria-expanded="false">
                                <i class="bi <?= $isEmployer ? 'bi-building text-warning' : 'bi-person-circle text-primary' ?> fs-6"></i>
                                <span><?= htmlspecialchars($userName) ?></span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="z-index: 10002 !important;">
                                <?php if ($isEmployer): ?>
                                    <!-- DÀNH CHO NHÀ TUYỂN DỤNG -->
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold" href="employer_dashboard.php">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i>Trang quản lý tuyển dụng
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <!-- DÀNH CHO ỨNG VIÊN -->
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold" href="my_applications.php">
                                            <i class="bi bi-clock-history me-2 text-primary"></i>Lịch sử nộp CV
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item text-danger fw-semibold py-2" href="javascript:void(0)" onclick="handleLogout(event)">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. NỘI DUNG CHÍNH TRANG QUẢN LÝ -->
    <div class="main-content py-4">
        <div class="container">
            
            <!-- HEADER TIÊU ĐỀ & NÚT ĐĂNG TIN -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="bi bi-speedometer2 text-primary me-2"></i>Trang Quản Lý Tuyển Dụng
                    </h3>
                    <p class="text-muted small mb-0">Xin chào <strong><?= htmlspecialchars($userName) ?></strong>, quản lý các tin đăng tuyển dụng và xem hồ sơ ứng tuyển tại đây.</p>
                </div>
                <div>
                    <a href="post_job.php" class="btn btn-primary fw-semibold px-4 py-2 rounded-3 shadow-sm" style="background-color: #0b4b8b; border: none;">
                        <i class="bi bi-plus-circle me-1"></i> Đăng tin tuyển dụng mới
                    </a>
                </div>
            </div>

            <!-- THỐNG KÊ NHANH -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="stat-card shadow-sm d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Tổng Tin Đã Đăng</span>
                            <h2 class="fw-bold text-dark m-0"><?= $totalJobs ?></h2>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="stat-card shadow-sm d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Tổng Số CV Nhận Được</span>
                            <h2 class="fw-bold text-dark m-0"><?= $totalApplications ?></h2>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-file-earmark-person-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BẢNG DANH SÁCH TIN TUYỂN DỤNG -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-dark m-0">
                        <i class="bi bi-list-task me-2 text-primary"></i>Danh Sách Tin Tuyển Dụng
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Tiêu đề bài tuyển dụng</th>
                                    <th>Mức lương</th>
                                    <th>Hạn nộp</th>
                                    <th>Số CV</th>
                                    <th class="pe-4 text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($jobs)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                            Bạn chưa đăng bài tuyển dụng nào trên hệ thống.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($jobs as $index => $job): ?>
                                        <?php 
                                            $jobId    = $job['ID'] ?? $job['id'] ?? 0;
                                            $tieuDe   = $job['TieuDe'] ?? $job['tieu_de'] ?? 'Chưa đặt tiêu đề';
                                            $mucLuong = $job['MucLuong'] ?? $job['muc_luong'] ?? 'Thỏa thuận';
                                            $hanNop   = $job['HanNop'] ?? $job['han_nop'] ?? 'N/A';
                                            $soCv     = $job['so_luong_cv'] ?? 0;
                                        ?>
                                        <tr>
                                            <td class="ps-4 text-muted fw-semibold"><?= $index + 1 ?></td>
                                            <td>
                                                <a href="job_detail.php?id=<?= $jobId ?>" class="text-decoration-none fw-bold text-dark">
                                                    <?= htmlspecialchars($tieuDe) ?>
                                                </a>
                                            </td>
                                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($mucLuong) ?></span></td>
                                            <td class="small text-muted"><?= htmlspecialchars($hanNop) ?></td>
                                            <td>
                                                <span class="badge <?= $soCv > 0 ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                                    <?= $soCv ?> CV
                                                </span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="view_applicants.php?job_id=<?= $jobId ?>" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-people me-1"></i>Xem CV
                                                </a>
                                                <a href="edit_job.php?id=<?= $jobId ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-pencil me-1"></i>Sửa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 3. FOOTER CHUẨN JOBIO -->
    <footer class="bg-dark text-white pt-4 pb-3 mt-auto">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold text-uppercase" style="color: #38bdf8;">Jobio</h5>
                    <p class="small text-white-50 m-0">Nền tảng kết nối ứng viên tài năng và nhà tuyển dụng hàng đầu.</p>
                </div>
                <div class="col-md-6 text-md-end mb-3">
                    <p class="small text-white-50 m-0">&copy; 2026 Jobio Project. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleLogout(e) {
            if (e) e.preventDefault();
            fetch('../api/auth.php?action=logout')
                .then(() => { window.location.href = 'index.php'; })
                .catch(() => { window.location.href = 'index.php'; });
        }
    </script>
</body>
</html>