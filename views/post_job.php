<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Đăng Tin Tuyển Dụng Mới";
require_once __DIR__ . '/../include/header.php';

// Các biến thông báo hiển thị (Backend sẽ gán giá trị thật khi xử lý logic)
$errorMsg   = $errorMsg ?? '';
$successMsg = $successMsg ?? '';
?>

<!-- GIAO DIỆN FORM ĐĂNG TIN TUYỂN DỤNG (FRONTEND CHUẨN ROSE/PASTEL) -->
<div class="container py-4">
    <!-- TIÊU ĐỀ TRANG & NÚT QUAY LẠI -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-plus-circle text-cupid me-2"></i>Đăng Tin Tuyển Dụng Mới
            </h4>
            <p class="text-muted small mb-0">Tạo bài đăng để tiếp cận hàng ngàn ứng viên tiềm năng trên Jobio.</p>
        </div>
        <div>
            <a href="employer_dashboard.php" class="btn btn-outline-rose btn-sm fw-semibold px-3 py-2">
                <i class="bi bi-arrow-left me-1"></i> Quay lại trang quản lý
            </a>
        </div>
    </div>

    <!-- KHUNG FORM NHẬP THÔNG TIN -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            
            <!-- KHU VỰC HIỂN THỊ THÔNG BÁO (DÀNH CHO BACKEND) -->
            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger alert-dismissible fade show fw-semibold" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($errorMsg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success alert-dismissible fade show fw-semibold" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($successMsg) ?>
                </div>
            <?php endif; ?>

            <!-- FORM ĐĂNG TIN -->
            <form action="post_job.php" method="POST">
                
                <div class="row g-3">
                    <!-- 1. TIÊU ĐỀ BÀI ĐĂNG (name="tieu_de") -->
                    <div class="col-12">
                        <label for="tieu_de" class="form-label fw-bold text-dark">Tiêu đề bài tuyển dụng <span class="text-danger">*</span></label>
                        <input type="text" name="tieu_de" id="tieu_de" class="form-control" placeholder="Ví dụ: Lập trình viên PHP / MySQL, Chuyên viên Marketing..." required>
                    </div>

                    <!-- 2. ĐỊA ĐIỂM LÀM VIỆC (name="dia_diem") -->
                    <div class="col-md-6 col-lg-3">
                        <label for="dia_diem" class="form-label fw-bold text-dark">Địa điểm làm việc <span class="text-danger">*</span></label>
                        <select name="dia_diem" id="dia_diem" class="form-select" required>
                            <option value="">-- Chọn địa điểm --</option>
                            <option value="Ninh Kiều, Cần Thơ">Ninh Kiều, Cần Thơ</option>
                            <option value="Cái Răng, Cần Thơ">Cái Răng, Cần Thơ</option>
                            <option value="Bình Thủy, Cần Thơ">Bình Thủy, Cần Thơ</option>
                            <option value="TP.HCM">TP. Hồ Chí Minh</option>
                            <option value="Hà Nội">Hà Nội</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>

                    <!-- 3. MỨC LƯƠNG (name="muc_luong") -->
                    <div class="col-md-6 col-lg-3">
                        <label for="muc_luong" class="form-label fw-bold text-dark">Mức lương <span class="text-danger">*</span></label>
                        <select name="muc_luong" id="muc_luong" class="form-select" required>
                            <option value="Thỏa thuận">Thỏa thuận</option>
                            <optgroup label="Việt Nam Đồng">
                                <option value="5 - 8 triệu / tháng">5 - 8 triệu / tháng</option>
                                <option value="8 - 12 triệu / tháng">8 - 12 triệu / tháng</option>
                                <option value="10 - 15 triệu / tháng">10 - 15 triệu / tháng</option>
                                <option value="15 - 20 triệu / tháng">15 - 20 triệu / tháng</option>
                                <option value="Trên 20 triệu / tháng">Trên 20 triệu / tháng</option>
                            </optgroup>
                            <optgroup label="Đô la Mỹ (USD)">
                                <option value="500 - 1,000 USD / tháng">500 - 1,000 USD / tháng</option>
                                <option value="Trên 1,000 USD / tháng">Trên 1,000 USD / tháng</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- 4. HÌNH THỨC LÀM VIỆC (name="loai_hinh") -->
                    <div class="col-md-6 col-lg-3">
                        <label for="loai_hinh" class="form-label fw-bold text-dark">Hình thức làm việc</label>
                        <select name="loai_hinh" id="loai_hinh" class="form-select">
                            <option value="Toàn thời gian">Toàn thời gian</option>
                            <option value="Bán thời gian">Bán thời gian</option>
                            <option value="Thực tập">Thực tập</option>
                            <option value="Remote / Làm từ xa">Remote / Làm từ xa</option>
                        </select>
                    </div>

                    <!-- 5. HẠN NỘP HỒ SƠ (name="han_nop") -->
                    <div class="col-md-6 col-lg-3">
                        <label for="han_nop" class="form-label fw-bold text-dark">Hạn nộp hồ sơ</label>
                        <input type="date" name="han_nop" id="han_nop" class="form-control">
                    </div>

                    <!-- 6. MÔ TẢ CÔNG VIỆC (name="mo_ta") -->
                    <div class="col-12">
                        <label for="mo_ta" class="form-label fw-bold text-dark">Mô tả công việc <span class="text-danger">*</span></label>
                        <textarea name="mo_ta" id="mo_ta" rows="4" class="form-control" placeholder="Mô tả công việc chi tiết..." required></textarea>
                    </div>

                    <!-- 7. YÊU CẦU ỨNG VIÊN (name="yeu_cau") -->
                    <div class="col-12">
                        <label for="yeu_cau" class="form-label fw-bold text-dark">Yêu cầu ứng viên</label>
                        <textarea name="yeu_cau" id="yeu_cau" rows="3" class="form-control" placeholder="Kỹ năng chuyên môn, kinh nghiệm, bằng cấp..."></textarea>
                    </div>

                    <!-- 8. QUYỀN LỢI ĐƯỢC HƯỞNG (name="quyen_loi") -->
                    <div class="col-12">
                        <label for="quyen_loi" class="form-label fw-bold text-dark">Quyền lợi được hưởng</label>
                        <textarea name="quyen_loi" id="quyen_loi" rows="3" class="form-control" placeholder="Chế độ bảo hiểm, thưởng lễ tết, môi trường làm việc, du lịch..."></textarea>
                    </div>
                </div>

                <!-- NÚT GỬI FORM -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="employer_dashboard.php" class="btn btn-light border px-4 fw-semibold">Hủy bỏ</a>
                    <button type="submit" class="btn btn-rose px-4 fw-semibold">
                        <i class="bi bi-send me-1"></i> Đăng tin ngay
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
// 2. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>