<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU & HÀM TIỆN ÍCH
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/auth_check.php';

// 2. LẤY ID VIỆC LÀM TỪ URL
$jobId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// 3. THIẾT LẬP TIÊU ĐỀ & NHÚNG HEADER DÙNG CHUNG
$pageTitle = "Chi Tiết Việc Làm";
require_once __DIR__ . '/../include/header.php';
?>

<!-- NỘI DUNG CHÍNH (XỬ LÝ ĐIỀU KIỆN ĐĂNG NHẬP) -->
<div class="container py-5">

    <?php if (!$isLoggedIn): ?>
        <!-- ================= KHU VỰC 1: KHI CHƯA ĐĂNG NHẬP ================= -->
        <div class="auth-required-card">
            <div class="lock-icon-box">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            
            <h4 class="fw-bold text-dark mb-2">Vui lòng Đăng nhập hoặc Đăng ký</h4>
            <p class="text-muted fs-6 mb-4">
                Bạn cần có tài khoản trên hệ thống <strong>Jobio</strong> để xem toàn bộ thông tin yêu cầu tuyển dụng và thực hiện nộp hồ sơ CV trực tuyến.
            </p>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="login.php?redirect=job_detail.php?id=<?= $jobId ?>" class="btn btn-rose px-4 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập ngay
                </a>
                
                <a href="register.php?role=candidate" class="btn btn-outline-rose px-4 py-2 fw-semibold">
                    <i class="bi bi-person-plus me-1"></i> Đăng ký tài khoản Ứng viên
                </a>
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="job_list.php" class="text-decoration-none text-muted small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách việc làm
                </a>
            </div>
        </div>

    <?php else: ?>
        <!-- ================= KHU VỰC 2: KHI ĐÃ ĐĂNG NHẬP ================= -->
        <div class="row g-4">
            
            <!-- Cột trái: Chi tiết việc làm -->
            <div class="col-lg-8">
                <div class="bg-white border p-4 mb-4" style="border-radius: 12px 2px 12px 2px; border-color: var(--sky-cloud) !important;">
                    <div class="d-md-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 85px; height: 85px; background-color: var(--lychee); border: 1px solid var(--sky-cloud); border-radius: 12px 2px 12px 2px; color: var(--cupid-pink); font-size: 36px;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h1 class="fs-3 fw-bold text-dark mb-1">Lập Trình Viên PHP / MySQL</h1>
                            <div class="text-muted fw-semibold mb-2">Công Ty TNHH Công Nghệ Jobio</div>
                            <div class="fs-5 fw-bold text-cupid">
                                <i class="bi bi-cash-stack me-1"></i>10 - 15 triệu / tháng
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 py-3 border-top border-bottom text-muted small mb-4">
                        <span><i class="bi bi-geo-alt text-veranda me-1"></i>Ninh Kiều, Cần Thơ</span>
                        <span><i class="bi bi-clock me-1"></i>Toàn thời gian</span>
                        <span><i class="bi bi-calendar-event text-cupid me-1"></i>Hạn nộp: 30/11/2026</span>
                    </div>

                    <!-- Mô tả công việc -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3 ps-2" style="border-left: 4px solid var(--cupid-pink);">Mô tả công việc</h5>
                        <ul class="text-secondary lh-lg">
                            <li>Tham gia phát triển và bảo trì các dự án Web Application trên nền tảng PHP / MySQL.</li>
                            <li>Phối hợp cùng team Frontend để tích hợp giao diện HTML/CSS/JS qua chuẩn RESTful API.</li>
                            <li>Tối ưu hóa câu lệnh truy vấn CSDL MySQL và đảm bảo tính bảo mật hệ thống.</li>
                            <li>Thực hiện các công việc khác theo sự phân công của Quản lý dự án.</li>
                        </ul>
                    </div>

                    <!-- Yêu cầu ứng viên -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3 ps-2" style="border-left: 4px solid var(--cupid-pink);">Yêu cầu ứng viên</h5>
                        <ul class="text-secondary lh-lg">
                            <li>Thành thạo ngôn ngữ PHP, nắm vững kiến thức về lập trình hướng đối tượng (OOP).</li>
                            <li>Có kinh nghiệm làm việc với CSDL MySQL (PDO, Prepared Statements, Indexing).</li>
                            <li>Biết sử dụng Git, HTML5, CSS3, JavaScript / AJAX / Bootstrap 5.</li>
                            <li>Có tư duy logic tốt, chủ động trong công việc và chịu được áp lực tiến độ.</li>
                        </ul>
                    </div>

                    <!-- Quyền lợi -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3 ps-2" style="border-left: 4px solid var(--cupid-pink);">Quyền lợi được hưởng</h5>
                        <ul class="text-secondary lh-lg">
                            <li>Mức lương cạnh tranh: 10 - 15 triệu/tháng + thưởng theo hiệu suất dự án.</li>
                            <li>Được hưởng đầy đủ chế độ BHYT, BHXH, BHTN theo Luật lao động.</li>
                            <li>Môi trường làm việc trẻ trung, năng động, trang thiết bị hiện đại.</li>
                            <li>Được tham gia các khóa đào tạo nâng cao chuyên môn hàng năm.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Box nộp CV & Thông tin công ty -->
            <div class="col-lg-4">
                <div class="bg-white border p-4 sticky-top" style="top: 90px; border-radius: 12px 2px 12px 2px; border-color: var(--sky-cloud) !important;">
                    <h5 class="fw-bold text-dark mb-3">Ứng tuyển vị trí này</h5>
                    <p class="text-muted small mb-4">Gửi đường dẫn CV / Hồ sơ ứng tuyển của bạn trực tiếp tới Nhà tuyển dụng.</p>

                    <!-- Nút kích hoạt Modal nộp CV -->
                    <button type="button" class="btn btn-rose w-100 py-2.5 fw-semibold shadow-sm mb-3" data-bs-toggle="modal" data-bs-target="#applyModal">
                        <i class="bi bi-send me-1"></i> Nộp hồ sơ ứng tuyển
                    </button>

                    <a href="job_list.php" class="btn btn-outline-secondary w-100 py-2 fw-semibold btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Xem việc làm khác
                    </a>

                    <hr class="my-4">

                    <h6 class="fw-bold text-dark mb-2">Thông tin nhà tuyển dụng</h6>
                    <div class="small text-muted mb-2"><i class="bi bi-building me-2 text-cupid"></i>Công Ty TNHH Công Nghệ Jobio</div>
                    <div class="small text-muted mb-2"><i class="bi bi-geo-alt me-2 text-veranda"></i>123 Đường 3/2, Q. Ninh Kiều, Cần Thơ</div>
                    <div class="small text-muted"><i class="bi bi-envelope me-2 text-cupid"></i>hr@jobio.vn</div>
                </div>
            </div>

    </div>

    <!-- MODAL FORM NỘP CV (HIỆN KHI ĐÃ ĐĂNG NHẬP VÀ BẤM NỘP CV) -->
<?php if ($isLoggedIn): ?>
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Nộp Hồ Sơ Ứng Tuyển</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="apply-form" action="../api/apply.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="job_id" value="<?= $jobId ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Họ và tên ứng viên</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($userName) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Đường dẫn Google Drive / Link CV online <span class="text-danger">*</span></label>
                        <input type="url" name="cv_url" class="form-control" placeholder="https://drive.google.com/file/d/your-cv..." required>
                        <div class="form-text">Hãy đảm bảo bạn đã mở quyền xem công khai cho link CV này.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Ghi chú cho nhà tuyển dụng (không bắt buộc)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Giới thiệu ngắn gọn về bản thân hoặc kinh nghiệm của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">Xác nhận nộp CV</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const applyForm = document.getElementById('apply-form');
    
    if (applyForm) {
        applyForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Ngăn trình duyệt nhảy sang trang API

            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            // Vô hiệu hóa nút bấm tránh spam nộp nhiều lần
            submitBtn.disabled = true;
            const originalBtnText = submitBtn.innerText;
            submitBtn.innerText = 'Đang gửi...';

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;

                if (data.status === 'success') {
                    // 1. Đóng Modal nộp CV
                    const modalElement = document.getElementById('applyModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }

                    // 2. Hiển thị thông báo thành công
                    alert(data.message);

                    // 3. Đặt lại thông tin Form về rỗng
                    form.reset();
                } else {
                    alert('⚠️ ' + data.message);
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
                alert('❌ Có lỗi xảy ra trong quá trình gửi, vui lòng thử lại!');
            });
        });
    }
});
</script>

    
        </div>
    <?php endif; ?>

</div>

<!-- MODAL FORM NỘP CV (HIỆN KHI ĐÃ ĐĂNG NHẬP VÀ BẤM NỘP CV) -->
<?php if ($isLoggedIn): ?>
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px 2px 12px 2px;">
            <div class="modal-header text-white" style="background-color: var(--cupid-pink);">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Nộp Hồ Sơ Ứng Tuyển</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="apply-form" action="../api/apply.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="job_id" value="<?= $jobId ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Họ và tên ứng viên</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($userName) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Đường dẫn Google Drive / Link CV online <span class="text-danger">*</span></label>
                        <input type="url" name="cv_url" class="form-control" placeholder="https://drive.google.com/file/d/your-cv..." required>
                        <div class="form-text">Hãy đảm bảo bạn đã mở quyền xem công khai cho link CV này.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Ghi chú cho nhà tuyển dụng (không bắt buộc)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Giới thiệu ngắn gọn về bản thân hoặc kinh nghiệm của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-rose btn-sm px-4 fw-semibold">Xác nhận nộp CV</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
// 4. NHÚNG FOOTER DÙNG CHUNG
require_once __DIR__ . '/../include/footer.php';
?>