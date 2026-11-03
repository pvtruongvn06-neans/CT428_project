<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$userRole   = strtolower(trim($_SESSION['vai_tro'] ?? ''));

$isEmployer = $isLoggedIn && ($userRole === 'nha_tuyen_dung' || $userRole === 'employer');
$userName   = $_SESSION['ho_ten'] ?? ($isEmployer ? 'Nhà tuyển dụng' : 'Ứng viên');

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' – Jobio' : 'Jobio – Input Talent, Output Success' ?></title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png?v=2">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- CSS DÙNG CHUNG CỦA DỰ ÁN -->
    <link rel="stylesheet" href="../assets/css/style.css?v=2">
</head>
<body>

<!-- NAVBAR DÙNG CHUNG -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2 shadow-sm">
    <div class="container">
        <!-- LOGO + SLOGAN (Bấm logo: Nhà tuyển dụng về Trang quản lý, Ứng viên/Khách về Trang chủ) -->
        <a class="navbar-brand d-flex align-items-center" href="<?= $isEmployer ? 'employer_dashboard.php' : 'index.php' ?>">
            <img src="../assets/images/icon.png?v=2" alt="Logo Jobio" width="40" height="40" class="me-2">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-4 lh-1 text-cupid">JOBIO</span>
                <span class="brand-slogan-sm">Input Talent, Output Success</span>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <!-- MENU CHÍNH (ẨN HOÀN TOÀN KHI LA NHÀ TUYỂN DỤNG) -->
            <?php if ($isEmployer): ?>
                <div class="me-auto"></div>
            <?php else: ?>
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'index.php' ? 'active-rose' : '' ?>" href="index.php">
                            <i class="bi bi-house-door me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'job_list.php' ? 'active-rose' : '' ?>" href="job_list.php">
                            <i class="bi bi-briefcase me-1"></i> Việc làm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'employers.php' ? 'active-rose' : '' ?>" href="employers.php">
                            <i class="bi bi-building me-1"></i> Nhà tuyển dụng
                        </a>
                    </li>
                </ul>
            <?php endif; ?>

            <!-- ĐĂNG NHẬP | ĐĂNG KÝ HOẶC MENU TÀI KHOẢN -->
            <div class="d-flex align-items-center">
                <?php if (!$isLoggedIn): ?>
                    <div class="auth-header-links px-2">
                        <a href="login.php" class="nav-auth-link">Đăng nhập</a>
                        <span class="auth-divider">|</span>
                        <a href="register.php?role=candidate" class="nav-auth-link">Đăng ký</a>
                    </div>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn btn-light border btn-sm dropdown-toggle fw-semibold d-flex align-items-center gap-2 px-3" 
                                style="border-radius: 8px 2px 8px 2px;"
                                type="button" 
                                data-bs-toggle="dropdown" 
                                data-bs-display="static" 
                                aria-expanded="false">
                            <i class="bi <?= $isEmployer ? 'bi-building text-cupid' : 'bi-person-circle text-cupid' ?> fs-6"></i>
                            <span><?= htmlspecialchars($userName) ?></span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2">
                            <?php if ($isEmployer): ?>
                                <li>
                                    <a class="dropdown-item py-2 fw-semibold" href="employer_dashboard.php">
                                        <i class="bi bi-speedometer2 me-2 text-cupid"></i>Trang quản lý tuyển dụng
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a class="dropdown-item py-2 fw-semibold" href="my_applications.php">
                                        <i class="bi bi-clock-history me-2 text-cupid"></i>Lịch sử nộp CV
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

<div class="main-content"></div>