<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/auth_check.php';

$keyword = isset($_GET['keyword']) ? sanitizeInput($_GET['keyword']) : '';

// 2. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Danh Sách Nhà Tuyển Dụng";
require_once __DIR__ . '/../include/header.php';
?>

<!-- HEADER BANNER & BỘ LỌC CÔNG TY -->
<section class="hero-banner text-center">
    <div class="container">
        <h2 class="fw-bold mb-2">DANH SÁCH NHÀ TUYỂN DỤNG</h2>
        <p class="hero-slogan mb-4">Khám phá các doanh nghiệp uy tín hàng đầu đang tuyển dụng trên hệ thống Jobio</p>
        
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <form action="employers.php" method="GET">
                    <div class="search-container d-flex align-items-center">
                        <i class="bi bi-search fs-5 ms-2 me-2" style="color: var(--veranda-blue);"></i>
                        <input type="text" name="keyword" class="form-control search-input" placeholder="Nhập tên công ty, doanh nghiệp cần tìm..." value="<?= htmlspecialchars($keyword) ?>">
                        <button type="submit" class="btn btn-rose px-4 py-2 fw-semibold flex-shrink-0">
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
                <div class="top-employer-card">
                    <div class="top-employer-logo">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 class="top-employer-name">Công Ty TNHH Công Nghệ Jobio</h5>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-geo-alt text-cupid me-1"></i>Ninh Kiều, Cần Thơ
                    </div>
                    <span class="top-employer-count mb-3">
                        <i class="bi bi-briefcase me-1"></i>5 vị trí tuyển dụng
                    </span>
                    <a href="employer_detail.php?id=1" class="btn btn-outline-rose btn-sm w-100 fw-semibold">
                        Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Công ty 2 -->
            <div class="col-md-4 col-sm-6">
                <div class="top-employer-card">
                    <div class="top-employer-logo">
                        <i class="bi bi-building-gear"></i>
                    </div>
                    <h5 class="top-employer-name">Tập Đoàn Truyền Thông ĐBSCL</h5>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-geo-alt text-cupid me-1"></i>Cái Răng, Cần Thơ
                    </div>
                    <span class="top-employer-count mb-3">
                        <i class="bi bi-briefcase me-1"></i>3 vị trí tuyển dụng
                    </span>
                    <a href="employer_detail.php?id=2" class="btn btn-outline-rose btn-sm w-100 fw-semibold">
                        Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Công ty 3 -->
            <div class="col-md-4 col-sm-6">
                <div class="top-employer-card">
                    <div class="top-employer-logo">
                        <i class="bi bi-bank"></i>
                    </div>
                    <h5 class="top-employer-name">Ngân Hàng Thương Mại Cần Thơ</h5>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-geo-alt text-cupid me-1"></i>Bình Thủy, Cần Thơ
                    </div>
                    <span class="top-employer-count mb-3">
                        <i class="bi bi-briefcase me-1"></i>8 vị trí tuyển dụng
                    </span>
                    <a href="employer_detail.php?id=3" class="btn btn-outline-rose btn-sm w-100 fw-semibold">
                        Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Công ty 4 -->
            <div class="col-md-4 col-sm-6">
                <div class="top-employer-card">
                    <div class="top-employer-logo">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h5 class="top-employer-name">Công Ty Giải Pháp Software Tech</h5>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-geo-alt text-cupid me-1"></i>Ninh Kiều, Cần Thơ
                    </div>
                    <span class="top-employer-count mb-3">
                        <i class="bi bi-briefcase me-1"></i>4 vị trí tuyển dụng
                    </span>
                    <a href="employer_detail.php?id=4" class="btn btn-outline-rose btn-sm w-100 fw-semibold">
                        Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Công ty 5 -->
            <div class="col-md-4 col-sm-6">
                <div class="top-employer-card">
                    <div class="top-employer-logo">
                        <i class="bi bi-basket3"></i>
                    </div>
                    <h5 class="top-employer-name">Tập Đoàn Nông Nghiệp Xanh</h5>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-geo-alt text-cupid me-1"></i>Ô Môn, Cần Thơ
                    </div>
                    <span class="top-employer-count mb-3">
                        <i class="bi bi-briefcase me-1"></i>2 vị trí tuyển dụng
                    </span>
                    <a href="employer_detail.php?id=5" class="btn btn-outline-rose btn-sm w-100 fw-semibold">
                        Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Công ty 6 -->
            <div class="col-md-4 col-sm-6">
                <div class="top-employer-card">
                    <div class="top-employer-logo">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="top-employer-name">Trung Tâm Dịch Vụ Khách Hàng Jobio</h5>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-geo-alt text-cupid me-1"></i>Ninh Kiều, Cần Thơ
                    </div>
                    <span class="top-employer-count mb-3">
                        <i class="bi bi-briefcase me-1"></i>6 vị trí tuyển dụng
                    </span>
                    <a href="employer_detail.php?id=6" class="btn btn-outline-rose btn-sm w-100 fw-semibold">
                        Xem chi tiết công ty <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
// 3. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>