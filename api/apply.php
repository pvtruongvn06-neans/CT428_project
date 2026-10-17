<?php
// api/apply.php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json; charset=utf-8');

function jsonResponse(array $data, int $httpCode = 200): void {
    http_response_code($httpCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    require_once __DIR__ . '/../config/database.php';

    // 1. Kiểm tra quyền Ứng viên
    if (!isset($_SESSION['user_id'], $_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'ung_vien') {
        jsonResponse(['status' => 'error', 'message' => 'Vui lòng đăng nhập tài khoản Ứng viên.'], 403);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['status' => 'error', 'message' => 'Phương thức không được hỗ trợ.'], 405);
    }

    $userId = (int) $_SESSION['user_id'];
    $jobId  = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);

    if (!$jobId) {
        jsonResponse(['status' => 'error', 'message' => 'Thiếu ID tin tuyển dụng.'], 400);
    }

    // 2. Validate tệp CV
    if (!isset($_FILES['cv_file']) || $_FILES['cv_file']['error'] !== UPLOAD_ERR_OK) {
        jsonResponse(['status' => 'error', 'message' => 'Vui lòng đính kèm tệp CV.'], 400);
    }

    $file = $_FILES['cv_file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['pdf', 'docx'], true)) {
        jsonResponse(['status' => 'error', 'message' => 'Chỉ chấp nhận file .pdf hoặc .docx.'], 400);
    }

    if ($file['size'] > 5 * 1024 * 1024) { // Limit 5MB
        jsonResponse(['status' => 'error', 'message' => 'Dung lượng file không vượt quá 5MB.'], 400);
    }

    // 3. Đổi tên & Lưu file
    $uploadDir = __DIR__ . '/../uploads/cv/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = 'CV_' . $userId . '_' . uniqid() . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
        jsonResponse(['status' => 'error', 'message' => 'Không thể lưu file.'], 500);
    }

    // 4. Lưu vào CSDL
    $check = $pdo->prepare('SELECT ID FROM HoSoUngTuyen WHERE ID_TinTuyenDung = :job_id AND ID_UngVien = :user_id');
    $check->execute(['job_id' => $jobId, 'user_id' => $userId]);
    if ($check->fetch()) {
        jsonResponse(['status' => 'error', 'message' => 'Bạn đã nộp hồ sơ cho tin này rồi.'], 400);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO HoSoUngTuyen (ID_TinTuyenDung, ID_UngVien, FileCV, TrangThai) 
         VALUES (:job_id, :user_id, :file_cv, "cho_duyet")'
    );
    $stmt->execute(['job_id' => $jobId, 'user_id' => $userId, 'file_cv' => $fileName]);

    jsonResponse(['status' => 'success', 'message' => 'Nộp hồ sơ ứng tuyển thành công.'], 201);

} catch (Throwable $e) {
    error_log($e->getMessage());
    jsonResponse(['status' => 'error', 'message' => 'Lỗi máy chủ.'], 500);
}