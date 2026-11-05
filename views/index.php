<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. NHÚNG FILE CẤU HÌNH & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';

// 2. KHỞI TẠO VÀ LẤY TRẠNG THÁI ĐĂNG NHẬP
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$userRole   = strtolower(trim($_SESSION['vai_tro'] ?? $_SESSION['role'] ?? ''));
$isEmployer = $isLoggedIn && (
    strpos($userRole, 'tuyen') !== false || 
    strpos($userRole, 'employer') !== false || 
    $userRole === 'nha_tuyen_dung'
);
$userName   = $_SESSION['ho_ten'] ?? $_SESSION['user_name'] ?? 'Bạn';

// 3. PHÂN QUYỀN: Nhà tuyển dụng đăng nhập -> Tự động chuyển hướng sang trang quản lý
if ($isLoggedIn && $isEmployer) {
    header('Location: employer_dashboard.php');
    exit();
}

// 4. LẤY TỪ KHÓA & ĐỊA ĐIỂM TÌM KIẾM TỪ URL
$keyword  = isset($_GET['keyword']) ? sanitizeInput($_GET['keyword']) : '';
$location = isset($_GET['location']) ? sanitizeInput($_GET['location']) : '';

// 5. NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Trang Chủ – Nền Tảng Tuyển Dụng Jobio";
require_once __DIR__ . '/../include/header.php';
?>

<?php if ($isLoggedIn): ?>
    <!-- ========================================================================= -->
    <!-- TRẠNG THÁI 1: ỨNG VIÊN ĐÃ ĐĂNG NHẬP                                      -->
    <!-- ========================================================================= -->
    <section class="hero-banner py-4 text-center">
        <div class="container">
            <h3 class="fw-bold text-white mb-1">
                Xin chào, <span style="color: var(--lychee);"><?= htmlspecialchars($userName) ?></span>! 👋
            </h3>
            <p class="hero-slogan mb-4" style="font-size: 0.95rem;">Hôm nay bạn muốn tìm kiếm cơ hội nghề nghiệp nào?</p>

            <form action="job_list.php" method="GET" onsubmit="if(!this.keyword.value.trim()) { event.preventDefault(); }">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="search-container d-flex align-items-center">
                            <i class="bi bi-search fs-5 ms-2 me-2" style="color: var(--veranda-blue);"></i>

                            <!-- KHUNG CHỨA INPUT VÀ POPUP GỢI Ý -->
                            <div class="position-relative flex-grow-1">
                                <input type="text" name="keyword" id="keyword" class="form-control search-input" placeholder="Tên công việc, vị trí, kỹ năng tuyển dụng..." autocomplete="off" value="<?= htmlspecialchars($keyword ?? '') ?>">
                                <div id="search-suggestions" class="list-group position-absolute w-100 shadow-lg d-none" style="z-index: 10000; top: 110%; left: 0; text-align: left; max-height: 320px; overflow-y: auto;"></div>
                            </div>

                            <!-- CHỌN ĐỊA ĐIỂM -->
                            <div class="d-none d-md-block flex-shrink-0 me-2" style="width: 190px;">
                                <select class="form-select search-select" name="location" id="location">
                                    <option value="">📍 Tất cả địa điểm</option>
                                    <option value="Cần Thơ" <?= ($location === 'Cần Thơ') ? 'selected' : '' ?>>Cần Thơ</option>
                                    <option value="TP.HCM" <?= ($location === 'TP.HCM' || $location === 'TP. Hồ Chí Minh') ? 'selected' : '' ?>>TP. Hồ Chí Minh</option>
                                    <option value="Hà Nội" <?= ($location === 'Hà Nội') ? 'selected' : '' ?>>Hà Nội</option>
                                </select>
                            </div>

                            <!-- NÚT TÌM KIẾM ROSE -->
                            <button type="submit" class="btn btn-rose px-4 py-2 fw-semibold flex-shrink-0">
                                <i class="bi bi-search me-1"></i> Tìm việc ngay
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- VIỆC LÀM DÀNH CHO ỨNG VIÊN -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark m-0"><i class="bi bi-stars text-cupid me-2"></i>VIỆC LÀM DÀNH CHO BẠN</h4>
                    <small class="text-muted">Danh sách các vị trí đang mở tuyển mới nhất hôm nay</small>
                </div>
                <a href="job_list.php" class="btn btn-outline-rose btn-sm fw-semibold">
                    Xem tất cả việc làm <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-laptop"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=1" class="job-title">Lập Trình Viên PHP / MySQL</a>
                            <div class="job-company">Công Ty TNHH Công Nghệ Jobio</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>10 - 15 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Ninh Kiều, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=2" class="job-title">Chuyên Viên Marketing Online</a>
                            <div class="job-company">Tập Đoàn Truyền Thông ĐBSCL</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>8 - 12 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Cái Răng, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-calculator"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=3" class="job-title">Kế Toán Tổng Hợp</a>
                            <div class="job-company">Ngân Hàng Thương Mại Cần Thơ</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>9 - 11 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Bình Thủy, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-headset"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=4" class="job-title">Nhân Viên Chăm Sóc Khách Hàng</a>
                            <div class="job-company">Trung Tâm Dịch Vụ Khách Hàng Jobio</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>7 - 9 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Ninh Kiều, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php else: ?>
    <!-- ========================================================================= -->
    <!-- TRẠNG THÁI 2: KHÁCH CHƯA ĐĂNG NHẬP                                       -->
    <!-- ========================================================================= -->

    <!-- 1. HERO BANNER TÌM KIẾM -->
    <section class="hero-banner text-center py-5">
        <div class="container py-3">
            <h1 class="display-6 fw-bold text-white mb-2">TÌM KIẾM CƠ HỘI NGHỀ NGHIỆP CÙNG JOBIO</h1>
            <p class="hero-slogan mb-4">Kết nối hàng ngàn ứng viên tài năng với các nhà tuyển dụng hàng đầu</p>

            <form action="job_list.php" method="GET" onsubmit="if(!this.keyword.value.trim()) { event.preventDefault(); }">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="search-container d-flex align-items-center">
                            <i class="bi bi-search fs-5 ms-2 me-2" style="color: var(--veranda-blue);"></i>
                            
                            <div class="position-relative flex-grow-1">
                                <input type="text" name="keyword" id="keyword" class="form-control search-input" placeholder="Tên công việc, vị trí, kỹ năng tuyển dụng..." autocomplete="off" value="<?= htmlspecialchars($keyword) ?>">
                                <div id="search-suggestions" class="list-group position-absolute w-100 shadow-lg d-none" style="z-index: 10000; top: 110%; left: 0; text-align: left; max-height: 320px; overflow-y: auto;"></div>
                            </div>
                            
                            <div class="d-none d-md-block flex-shrink-0 me-2" style="width: 200px;">
                                <select class="form-select search-select" name="location">
                                    <option value="">📍 Tất cả địa điểm</option>
                                    <option value="Cần Thơ">Cần Thơ</option>
                                    <option value="TP.HCM">TP. Hồ Chí Minh</option>
                                    <option value="Hà Nội">Hà Nội</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-rose px-4 py-2 fw-semibold flex-shrink-0">
                                <i class="bi bi-search me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- 2. NHÀ TUYỂN DỤNG HÀNG ĐẦU -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark m-0">NHÀ TUYỂN DỤNG HÀNG ĐẦU</h4>
                    <small class="text-muted">Các doanh nghiệp uy tín hàng đầu đang tuyển dụng</small>
                </div>
                <a href="employers.php" class="btn btn-outline-rose btn-sm fw-semibold">
                    Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="top-employer-card">
                        <div class="top-employer-logo"><i class="bi bi-building"></i></div>
                        <h5 class="top-employer-name">Công Ty TNHH Công Nghệ Jobio</h5>
                        <div class="small text-muted mb-2"><i class="bi bi-geo-alt text-cupid me-1"></i>Ninh Kiều, Cần Thơ</div>
                        <span class="top-employer-count mb-3"><i class="bi bi-briefcase me-1"></i>5 vị trí tuyển dụng</span>
                        <a href="employer_detail.php?id=1" class="btn btn-outline-rose btn-sm w-100 fw-semibold">Xem công ty</a>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="top-employer-card">
                        <div class="top-employer-logo"><i class="bi bi-building-gear"></i></div>
                        <h5 class="top-employer-name">Tập Đoàn Truyền Thông ĐBSCL</h5>
                        <div class="small text-muted mb-2"><i class="bi bi-geo-alt text-cupid me-1"></i>Cái Răng, Cần Thơ</div>
                        <span class="top-employer-count mb-3"><i class="bi bi-briefcase me-1"></i>3 vị trí tuyển dụng</span>
                        <a href="employer_detail.php?id=2" class="btn btn-outline-rose btn-sm w-100 fw-semibold">Xem công ty</a>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="top-employer-card">
                        <div class="top-employer-logo"><i class="bi bi-bank"></i></div>
                        <h5 class="top-employer-name">Ngân Hàng Thương Mại Cần Thơ</h5>
                        <div class="small text-muted mb-2"><i class="bi bi-geo-alt text-cupid me-1"></i>Bình Thủy, Cần Thơ</div>
                        <span class="top-employer-count mb-3"><i class="bi bi-briefcase me-1"></i>8 vị trí tuyển dụng</span>
                        <a href="employer_detail.php?id=3" class="btn btn-outline-rose btn-sm w-100 fw-semibold">Xem công ty</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. VIỆC LÀM NỔI BẬT -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark m-0">VIỆC LÀM NỔI BẬT</h4>
                    <small class="text-muted">Các công việc hấp dẫn với mức lương cạnh tranh</small>
                </div>
                <a href="job_list.php" class="btn btn-outline-rose btn-sm fw-semibold">
                    Xem tất cả việc làm <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-laptop"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=1" class="job-title">Lập Trình Viên PHP / MySQL</a>
                            <div class="job-company">Công Ty TNHH Công Nghệ Jobio</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>10 - 15 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Ninh Kiều, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="job-featured-card">
                        <div class="job-logo-box"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="flex-grow-1 pe-4">
                            <a href="job_detail.php?id=2" class="job-title">Chuyên Viên Marketing Online</a>
                            <div class="job-company">Tập Đoàn Truyền Thông ĐBSCL</div>
                            <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>8 - 12 triệu</div>
                            <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Cái Răng, Cần Thơ</div>
                        </div>
                        <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. CTA BANNER KÊU GỌI ĐĂNG KÝ -->
    <section class="py-5 bg-light border-top">
        <div class="container py-2">
            <div class="row g-4 justify-content-center">
                <!-- THẺ DÀNH CHO ỨNG VIÊN -->
                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 border shadow-sm rounded-4 p-4 text-center bg-white">
                        <div class="card-body d-flex flex-column align-items-center justify-content-between p-2">
                            <div>
                                <div class="bg-rose-soft text-rose rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                    <i class="bi bi-person-fill fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-3">Dành Cho Ứng Viên</h4>
                                <p class="text-secondary small mb-4 px-2">
                                    Tạo tài khoản ứng viên, tìm kiếm việc làm mơ ước và ứng tuyển nhanh chóng.
                                </p>
                            </div>
                            <a href="register.php?role=candidate" class="btn btn-rose fw-bold rounded-3 px-4 py-2.5 w-100">
                                Đăng ký Ứng viên
                            </a>
                        </div>
                    </div>
                </div>

                <!-- THẺ DÀNH CHO NHÀ TUYỂN DỤNG -->
                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 border shadow-sm rounded-4 p-4 text-center bg-white">
                        <div class="card-body d-flex flex-column align-items-center justify-content-between p-2">
                            <div>
                                <div class="bg-rose-soft text-rose rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                    <i class="bi bi-building fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-3">Dành Cho Nhà Tuyển Dụng</h4>
                                <p class="text-secondary small mb-4 px-2">
                                    Đăng tin tuyển dụng, tiếp cận hàng ngàn hồ sơ ứng viên sáng giá cho doanh nghiệp.
                                </p>
                            </div>
                            <a href="register.php?role=employer" class="btn btn-outline-rose fw-bold rounded-3 px-4 py-2.5 w-100">
                                Đăng ký Nhà tuyển dụng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- SCRIPT GỢI Ý TÌM KIẾM AUTOCOMPLETE -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const keywordInput = document.getElementById('keyword');
    const suggestionsBox = document.getElementById('search-suggestions');
    let timeout = null;

    if (!keywordInput || !suggestionsBox) return;

    keywordInput.addEventListener('input', function () {
        clearTimeout(timeout);
        const kw = this.value.trim();

        if (kw.length < 2) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.add('d-none');
            return;
        }

        timeout = setTimeout(() => {
            fetch(`../api/jobs_public.php?keyword=${encodeURIComponent(kw)}`)
                .then(res => res.json())
                .then(res => {
                    suggestionsBox.innerHTML = '';

                    if (res.status === 'success' && res.data && res.data.length > 0) {
                        res.data.slice(0, 6).forEach(job => {
                            const item = document.createElement('a');
                            item.href = `job_detail.php?id=${job.ID}`;
                            item.className = 'list-group-item list-group-item-action py-2.5 px-3 border-bottom';
                            item.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark mb-1">${escapeHtml(job.TieuDe)}</div>
                                        <small class="text-muted"><i class="bi bi-building me-1"></i>${escapeHtml(job.TenNhaTuyenDung || 'Doanh nghiệp')}</small>
                                    </div>
                                    <small class="text-secondary"><i class="bi bi-geo-alt me-1"></i>${escapeHtml(job.DiaDiem)}</small>
                                </div>
                            `;
                            suggestionsBox.appendChild(item);
                        });
                        suggestionsBox.classList.remove('d-none');
                    } else {
                        suggestionsBox.innerHTML = '<div class="list-group-item text-muted text-center py-2.5">Không tìm thấy việc làm phù hợp</div>';
                        suggestionsBox.classList.remove('d-none');
                    }
                })
                .catch(err => console.error('Lỗi gợi ý:', err));
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!keywordInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.classList.add('d-none');
        }
    });

    function escapeHtml(str) {
        return str ? String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
    }
});
</script>

<?php
// 6. NHÚNG FOOTER DÙNG CHUNG (Cửa sổ footer đã có thẻ </body></html> và JS Bootstrap)
require_once __DIR__ . '/../include/footer.php';
?>