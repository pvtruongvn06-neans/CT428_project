<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & KIỂM TRA PHÂN QUYỀN
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/auth_check.php';

// Tự động kiểm tra session và bắt buộc phải là Nhà tuyển dụng (chuyển hướng nếu sai quyền)
requireEmployer();

$employerId = $_SESSION['user_id'];
$userName   = $_SESSION['ho_ten'] ?? $_SESSION['user_name'] ?? 'Nhà tuyển dụng';

// 2. TRUY VẤN DỮ LIỆU BÀI ĐĂNG VÀ HỒ SƠ CV
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

// 3. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Trang Quản Lý Tuyển Dụng";
require_once __DIR__ . '/../include/header.php';
?>

<!-- NỘI DUNG CHÍNH TRANG QUẢN LÝ -->
<div class="container py-4">
    
    <!-- HEADER TIÊU ĐỀ & NÚT ĐĂNG TIN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-speedometer2 text-cupid me-2"></i>Trang Quản Lý Tuyển Dụng
            </h3>
            <p class="text-muted small mb-0">Xin chào <strong><?= htmlspecialchars($userName) ?></strong>, quản lý các tin đăng tuyển dụng và xem hồ sơ ứng tuyển tại đây.</p>
        </div>
        <div>
            <a href="post_job.php" class="btn btn-rose fw-semibold px-4 py-2 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Đăng tin tuyển dụng mới
            </a>
        </div>
    </div>

    <!-- THỐNG KÊ NHANH (BẢNG MÀU OCEAN PASTEL) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Tổng tin đã đăng (Tone Cupid Pink) -->
        <div class="col-md-6">
            <div class="stat-card shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Tổng Tin Đã Đăng</span>
                    <h2 class="fw-bold text-dark m-0"><?= $totalJobs ?></h2>
                </div>
                <div class="stat-icon bg-lychee text-cupid">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Tổng số CV nhận được (Tone Veranda Blue) -->
        <div class="col-md-6">
            <div class="stat-card shadow-sm d-flex align-items-center justify-content-between" style="border-left-color: var(--veranda-blue);">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Tổng Số CV Nhận Được</span>
                    <h2 class="fw-bold text-dark m-0"><?= $totalApplications ?></h2>
                </div>
                <div class="stat-icon text-veranda" style="background-color: rgba(107, 177, 173, 0.15);">
                    <i class="bi bi-file-earmark-person-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- DANH SÁCH TIN TUYỂN DỤNG (CHỈ GIỮ 4 CỘT THEO YÊU CẦU) -->
    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="fw-bold text-dark m-0">
                <i class="bi bi-list-task text-cupid me-2"></i>Danh Sách Tin Tuyển Dụng
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4" style="width: 80px;">#</th>
                            <th scope="col">Tiêu đề bài tuyển dụng</th>
                            <th scope="col" style="width: 220px;">Hạn nộp</th>
                            <th scope="col" class="text-center pe-4" style="width: 150px;">Số CV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jobList)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="my-3">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Bạn chưa đăng bài tuyển dụng nào trên hệ thống.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($jobList as $job): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-secondary"><?= $stt++ ?></td>
                                    <td>
                                        <a href="job_detail.php?id=<?= $job['ID'] ?? $job['id'] ?>" class="fw-bold text-dark text-decoration-none hover-rose">
                                            <?= htmlspecialchars($job['TieuDe'] ?? $job['tieu_de'] ?? 'Chưa cập nhật tiêu đề') ?>
                                        </a>
                                    </td>
                                    <td class="text-secondary small">
                                        <?php 
                                            $hanNop = $job['HanNop'] ?? $job['han_nop'] ?? null;
                                            echo !empty($hanNop) ? date('d/m/Y', strtotime($hanNop)) : 'Không giới hạn';
                                        ?>
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge bg-rose-soft text-rose px-3 py-2 fw-semibold rounded-pill">
                                            <?= $job['SoCV'] ?? $job['so_cv'] ?? 0 ?> CV
                                        </span>
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

<?php
// 4. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>