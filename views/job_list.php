<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Lấy từ khóa và địa điểm tìm kiếm từ URL
$keyword  = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';

// 2. TRUY VẤN DANH SÁCH VIỆC LÀM TRỰC TIẾP QUA PDO
$jobList = [];
if (isset($pdo) && $pdo instanceof PDO) {
    try {
        $sql = "SELECT t.*, 
                       u.HoTen AS ten_nha_tuyen_dung, u.ho_ten AS ten_ntd_alt
                FROM TinTuyenDung t
                LEFT JOIN NguoiDung u ON (t.ID_NguoiDung = u.ID OR t.id_nguoi_dung = u.id)
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (t.TieuDe LIKE :kw OR t.tieu_de LIKE :kw OR t.MoTa LIKE :kw OR t.mo_ta LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($location)) {
            $sql .= " AND (t.DiaDiem LIKE :loc OR t.dia_diem LIKE :loc)";
            $params[':loc'] = '%' . $location . '%';
        }

        $sql .= " ORDER BY t.ID DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $jobList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Lỗi job_list: " . $e->getMessage());
    }
}

// 3. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Danh Sách Việc Làm";
require_once __DIR__ . '/../include/header.php';
?>

<!-- THANH LỌC TÌM KIẾM ĐẦU TRANG -->
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
                                <option value="TP.HCM" <?= ($location === 'TP.HCM' || $location === 'TP. Hồ Chí Minh') ? 'selected' : '' ?>>TP. Hồ Chí Minh</option>
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
                <?= !empty($keyword) ? 'Kết quả cho: "' . htmlspecialchars($keyword) . '"' : 'Tất cả vị trí (' . count($jobList) . ')' ?>
            </span>
        </div>

        <!-- Khối chứa danh sách việc làm -->
        <div id="job-list" class="row g-3">
            <?php if (empty($jobList)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
                    <h5 class="text-secondary">Không tìm thấy việc làm nào phù hợp<?= !empty($keyword) ? ' với từ khóa "' . htmlspecialchars($keyword) . '"' : '' ?>.</h5>
                </div>
            <?php else: ?>
                <?php foreach ($jobList as $job): ?>
                    <?php 
                        $jobId    = $job['ID'] ?? $job['id'] ?? 0;
                        $tieuDe   = $job['TieuDe'] ?? $job['tieu_de'] ?? 'Chưa cập nhật tiêu đề';
                        $congTy   = $job['ten_nha_tuyen_dung'] ?? $job['ten_ntd_alt'] ?? 'Doanh nghiệp';
                        $luong    = $job['MucLuong'] ?? $job['muc_luong'] ?? $job['Luong'] ?? 'Thỏa thuận';
                        $diaDiem  = $job['DiaDiem'] ?? $job['dia_diem'] ?? 'Chưa cập nhật';
                    ?>
                    <div class="col-md-6">
                        <div class="job-featured-card" onclick="window.location.href='job_detail.php?id=<?= $jobId ?>';" style="cursor: pointer;">
                            <div class="job-logo-box"><i class="bi bi-briefcase"></i></div>
                            <div class="flex-grow-1 pe-4">
                                <a href="job_detail.php?id=<?= $jobId ?>" class="job-title" onclick="event.stopPropagation();">
                                    <?= htmlspecialchars($tieuDe) ?>
                                </a>
                                <div class="job-company"><?= htmlspecialchars($congTy) ?></div>
                                <div class="job-salary"><i class="bi bi-cash-stack me-1"></i><?= htmlspecialchars($luong) ?></div>
                                <div class="job-location"><i class="bi bi-geo-alt text-veranda me-1"></i><?= htmlspecialchars($diaDiem) ?></div>
                            </div>
                            <i class="bi bi-heart job-heart-btn" title="Lưu việc làm" onclick="event.stopPropagation();"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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

    </div>
</section>

<?php
// 4. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>