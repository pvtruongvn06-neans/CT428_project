<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/auth_check.php';

// Lấy từ khóa và địa điểm tìm kiếm từ URL
$keyword  = isset($_GET['keyword']) ? sanitizeInput($_GET['keyword']) : '';
$location = isset($_GET['location']) ? sanitizeInput($_GET['location']) : '';

// 2. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Danh Sách Việc Làm";
require_once __DIR__ . '/../include/header.php';
?>

<!-- THANH LỌC TÌM KIẾM ĐẦU TRANG (BAN BÁM CHUẨN HERO BANNER) -->
<section class="hero-banner text-center py-4">
    <div class="container">
        <form action="job_list.php" method="GET" id="search-job-form">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="search-container d-flex align-items-center">
                        <i class="bi bi-search fs-5 ms-2 me-2" style="color: var(--veranda-blue);"></i>
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
                        
                        <button type="submit" class="btn btn-rose px-4 py-2 fw-semibold flex-shrink-0">
                            <i class="bi bi-search me-1"></i> Lọc kết quả
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- DANH SÁCH TẤT CẢ VIỆC LÀM -->
<section class="py-5">
    <div class="container">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <h4 class="section-title-main fs-5 m-0">
                    <i class="bi bi-briefcase-fill me-2 text-cupid"></i>TẤT CẢ VIỆC LÀM TUYỂN DỤNG
                </h4>
                <small class="text-muted">Tìm kiếm các cơ hội nghề nghiệp tốt nhất tại Jobio</small>
            </div>
            <span class="badge bg-lychee text-cupid fw-semibold px-3 py-2" style="border-radius: 6px 2px 6px 2px; border: 1px solid var(--sky-cloud);">
                <?= !empty($keyword) ? 'Kết quả cho: "' . htmlspecialchars($keyword) . '"' : 'Tất cả vị trí' ?>
            </span>
        </div>

        <!-- Khối chứa danh sách việc làm -->
        <div id="job-list" class="row g-3">
            
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
            <!-- Card 1 -->
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

            <!-- Card 2 -->
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

            <!-- Card 3 -->
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

            <!-- Card 4 -->
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

            <!-- Card 5 -->
            <div class="col-md-6">
                <div class="job-featured-card">
                    <div class="job-logo-box"><i class="bi bi-code-slash"></i></div>
                    <div class="flex-grow-1 pe-4">
                        <a href="job_detail.php?id=5" class="job-title">Lập Trình Viên Frontend (ReactJS / VueJS)</a>
                        <div class="job-company">Công Ty Giải Pháp Phần Mềm Mekong</div>
                        <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>12 - 18 triệu</div>
                        <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Ninh Kiều, Cần Thơ</div>
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
                        <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i>Ô Môn, Cần Thơ</div>
                    </div>
                    <i class="bi bi-heart job-heart-btn" title="Lưu việc làm"></i>
                </div>
            </div>

        </div>

        <!-- Nút kêu gọi Đăng ký dành cho người dùng chưa đăng nhập -->
        <?php if (!$isLoggedIn): ?>
        <div class="mt-5 p-4 bg-white border text-center shadow-sm" style="border-radius: 12px 2px 12px 2px; border-color: var(--sky-cloud) !important;">
            <h5 class="fw-bold text-dark mb-2">Bạn muốn nộp hồ sơ ứng tuyển vào các công việc trên?</h5>
            <p class="text-muted small mb-3">Đăng ký tài khoản Ứng viên Jobio chỉ trong 1 phút để ứng tuyển trực tiếp đến nhà tuyển dụng.</p>
            <a href="register.php?role=candidate" class="btn btn-rose px-4 py-2 fw-semibold">
                <i class="bi bi-person-plus me-1"></i> Đăng ký tài khoản ngay
            </a>
        </div>
        <?php endif; ?>

    <!-- Script Bootstrap & JS dự án -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/job_board.js"></script>
    <script>
        function handleLogout(e) {
            if (e) e.preventDefault();
            fetch('../api/auth.php?action=logout')
                .then(res => res.json())
                .then(data => {
                    window.location.href = 'index.php';
                })
                .catch(err => {
                    window.location.href = 'index.php';
                });
        }
    </script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const keyword = urlParams.get('keyword') || '';
    const location = urlParams.get('location') || '';

    const jobListContainer = document.getElementById('job-list');
    if (!jobListContainer) return;

    // Hiển thị trạng thái đang tải
    jobListContainer.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';

    // Gọi API lấy danh sách việc làm đã lọc
    fetch(`../api/jobs_public.php?keyword=${encodeURIComponent(keyword)}&location=${encodeURIComponent(location)}`)
        .then(res => res.json())
        .then(res => {
            jobListContainer.innerHTML = '';

            if (res.status === 'success' && res.data && res.data.length > 0) {
                res.data.forEach(job => {
                    jobListContainer.innerHTML += `
                        <div class="col-md-6">
                            <div class="job-featured-card" onclick="window.location.href='job_detail.php?id=${job.ID}';" style="cursor: pointer;">
                                <div class="job-logo-box"><i class="bi bi-briefcase"></i></div>
                                <div class="flex-grow-1 pe-4">
                                    <a href="job_detail.php?id=${job.ID}" class="job-title" onclick="event.stopPropagation();">${escapeHtml(job.TieuDe)}</a>
                                    <div class="job-company">${escapeHtml(job.TenNhaTuyenDung || 'Doanh nghiệp')}</div>
                                    <div class="job-salary"><i class="bi bi-cash-stack me-1"></i>${escapeHtml(job.Luong || 'Thỏa thuận')}</div>
                                    <div class="job-location"><i class="bi bi-geo-alt text-primary me-1"></i>${escapeHtml(job.DiaDiem)}</div>
                                </div>
                                <i class="bi bi-heart job-heart-btn" title="Lưu việc làm" onclick="event.stopPropagation();"></i>
                            </div>
                        </div>
                    `;
                });
            } else {
                jobListContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
                        <h5 class="text-secondary">Không tìm thấy việc làm nào phù hợp${keyword ? ' với từ khóa "' + escapeHtml(keyword) + '"' : ''}.</h5>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error('Lỗi tải dữ liệu:', err);
            jobListContainer.innerHTML = '<div class="col-12 text-center text-danger py-4">Có lỗi xảy ra khi tải danh sách việc làm.</div>';
        });

    function escapeHtml(str) {
        return str ? String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
    }
});
</script>

</body>
</html>
    </div>
</section>

<?php
// 3. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>
