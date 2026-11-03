<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/auth_check.php';

// Bắt buộc phải là Ứng viên mới được xem trang này (Tự động chuyển hướng nếu chưa đăng nhập/sai quyền)
requireCandidate();

// LẤY DỮ LIỆU LỊCH SỬ NỘP CV TRỰC TIẾP TỪ CSDL (KHÔNG CẦN DÙNG AJAX TRÁNH LỖI HOÀI SPINNER)
$userId = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;
$applications = [];

if (isset($pdo) && $pdo instanceof PDO) {
    try {
        $sql = "SELECT h.*, 
                       t.TieuDe AS tieu_de, t.ID AS id_tin,
                       u.HoTen AS ten_cong_ty, u.ho_ten AS ten_cong_ty_alt
                FROM HoSoUngTuyen h
                LEFT JOIN TinTuyenDung t ON (h.ID_TinTuyenDung = t.ID OR h.id_tin_tuyen_dung = t.id)
                LEFT JOIN NguoiDung u ON (t.ID_NguoiDung = u.ID OR t.id_nguoi_dung = u.id)
                WHERE h.ID_UngVien = :userId OR h.id_ung_vien = :userId
                ORDER BY h.ID DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Lỗi lịch sử nộp CV: " . $e->getMessage());
    }
}

// 2. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Lịch Sử Nộp CV";
require_once __DIR__ . '/../include/header.php';
?>

<!-- NỘI DUNG CHÍNH TRANG LỊCH SỬ NỘP CV -->
<div class="container py-4">
    
    <!-- HEADER TIÊU ĐỀ & NÚT TÌM VIỆC -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-clock-history text-cupid me-2"></i>Lịch sử nộp CV của bạn
            </h4>
            <p class="text-muted small mb-0">Theo dõi trạng thái tất cả các hồ sơ ứng tuyển bạn đã gửi đến Nhà tuyển dụng.</p>
        </div>
        <div>
            <a href="job_list.php" class="btn btn-outline-rose btn-sm fw-semibold px-3 py-2">
                <i class="bi bi-search me-1"></i> Tìm việc mới
            </a>
        </div>
    </div>

    <!-- BẢNG DANH SÁCH BÀI ĐÃ ỨNG TUYỂN -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px 2px 12px 2px; border: 1px solid var(--sky-cloud) !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Công việc</th>
                            <th>Công ty</th>
                            <th>Ngày nộp</th>
                            <th>Trạng thái</th>
                            <th class="pe-4 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                            <!-- HIỂN THỊ THÔNG BÁO KHI CHƯA NỘP HỒ SƠ/KHÔNG THẤY CV -->
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                                    <p class="text-muted fw-semibold mb-0">Bạn chưa ứng tuyển cho công ty nào.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($applications as $app): ?>
                                <?php 
                                    $tieuDe    = $app['tieu_de'] ?? $app['TieuDe'] ?? 'Chưa cập nhật tiêu đề';
                                    $congTy    = $app['ten_cong_ty'] ?? $app['ten_cong_ty_alt'] ?? 'Chưa rõ công ty';
                                    $rawDate   = $app['NgayNop'] ?? $app['ngay_nop'] ?? null;
                                    $ngayNop   = !empty($rawDate) ? date('d/m/Y', strtotime($rawDate)) : 'Chưa xác định';
                                    $trangThai = $app['TrangThai'] ?? $app['trang_thai'] ?? 'Chờ duyệt';
                                    $jobId     = $app['id_tin'] ?? $app['ID_TinTuyenDung'] ?? 0;
                                ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-secondary"><?= $stt++ ?></td>
                                    <td>
                                        <a href="job_detail.php?id=<?= $jobId ?>" class="fw-bold text-dark text-decoration-none hover-rose">
                                            <?= htmlspecialchars($tieuDe) ?>
                                        </a>
                                    </td>
                                    <td class="text-secondary small"><?= htmlspecialchars($congTy) ?></td>
                                    <td class="text-secondary small"><?= $ngayNop ?></td>
                                    <td>
                                        <?php if ($trangThai === 'Đã duyệt' || $trangThai === 'Trúng tuyển'): ?>
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold">
                                                <i class="bi bi-check-circle me-1"></i><?= htmlspecialchars($trangThai) ?>
                                            </span>
                                        <?php elseif ($trangThai === 'Từ chối'): ?>
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-semibold">
                                                <i class="bi bi-x-circle me-1"></i>Từ chối
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-semibold">
                                                <i class="bi bi-clock me-1"></i>Chờ duyệt
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="job_detail.php?id=<?= $jobId ?>" class="btn btn-sm btn-outline-rose rounded-pill px-3">
                                            Xem bài đăng
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

<?php
// 3. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>