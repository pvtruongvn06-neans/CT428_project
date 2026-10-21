<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy thông tin trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$userRole   = strtolower(trim($_SESSION['vai_tro'] ?? $_SESSION['role'] ?? ''));

// Kiểm tra vai trò Nhà tuyển dụng
$isEmployer = $isLoggedIn && (
    strpos($userRole, 'tuyen') !== false || 
    strpos($userRole, 'employer') !== false || 
    $userRole === 'nha_tuyen_dung'
);

$userName = $_SESSION['ho_ten'] ?? $_SESSION['user_name'] ?? 'Tài khoản';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    <title>Lịch sử ứng tuyển - Jobio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- 1. THANH MENU (HEADER TÍCH HỢP PHÂN QUYỀN) -->
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

    <!-- Container nội dung chính -->
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Lịch sử nộp CV của bạn</h4>
            <a href="index.php" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-search me-1"></i>Tìm việc mới</a>
        </div>

        <!-- Bảng danh sách bài đã ứng tuyển -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Công việc</th>
                                <th>Công ty</th>
                                <th>Ngày nộp</th>
                                <th>Trạng thái</th>
                                <th class="pe-3 text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="applications-list">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                    Đang tải lịch sử ứng tuyển...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jobs.js"></script>

    <!-- Đoạn JS Đăng xuất trực tiếp không lo bị lỗi file JS bên ngoài -->
    <script>
        function handleLogout(e) {
            if (e) e.preventDefault();
            fetch('../api/auth.php?action=logout')
                .then(res => res.json())
                .then(data => { window.location.href = 'index.php'; })
                .catch(err => { window.location.href = 'index.php'; });
        }
    </script>
</body>
</html>