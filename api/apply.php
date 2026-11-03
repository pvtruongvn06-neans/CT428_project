<?php
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
    $role = strtolower(trim($_SESSION['vai_tro'] ?? $_SESSION['role'] ?? ''));
    if (!isset($_SESSION['user_id']) || ($role !== 'ung_vien' && $role !== 'job_seeker' && $role !== 'candidate')) {
        jsonResponse(['status' => 'error', 'message' => 'Vui lòng đăng nhập tài khoản Ứng viên.'], 403);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['status' => 'error', 'message' => 'Phương thức không được hỗ trợ.'], 405);
    }

    $userId = (int) $_SESSION['user_id'];
    $jobId  = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);
    $note   = isset($_POST['note']) ? trim($_POST['note']) : '';

    if (!$jobId) {
        jsonResponse(['status' => 'error', 'message' => 'Thiếu ID tin tuyển dụng.'], 400);
    }

    // 2. Lấy dữ liệu link CV từ ô input name="cv_url" của Form
    if (!isset($_POST['cv_url']) || empty(trim($_POST['cv_url']))) {
        jsonResponse(['status' => 'error', 'message' => 'Vui lòng nhập đường dẫn Google Drive / Link CV online.'], 400);
    }

    $cvUrl = trim($_POST['cv_url']);

    // 3. Kiểm tra xem ứng viên đã nộp hồ sơ cho tin này chưa
    $check = $pdo->prepare('SELECT id FROM applications WHERE job_id = :job_id AND user_id = :user_id');
    $check->execute(['job_id' => $jobId, 'user_id' => $userId]);
    if ($check->fetch()) {
        jsonResponse(['status' => 'error', 'message' => 'Bạn đã nộp hồ sơ ứng tuyển cho công việc này rồi!'], 400);
    }

    // 4. Lưu trực tiếp link CV vào bảng applications
    $stmt = $pdo->prepare(
        'INSERT INTO applications (job_id, user_id, cv_url, note, applied_at, status) 
         VALUES (:job_id, :user_id, :cv_url, :note, NOW(), "pending")'
    );
    $stmt->execute([
        'job_id'  => $jobId,
        'user_id' => $userId,
        'cv_url'  => $cvUrl,
        'note'    => $note
    ]);

    jsonResponse(['status' => 'success', 'message' => 'Nộp hồ sơ ứng tuyển thành công!'], 200);

} catch (PDOException $e) {
    jsonResponse(['status' => 'error', 'message' => 'Lỗi CSDL: ' . $e->getMessage()], 500);
} catch (Throwable $e) {
    error_log($e->getMessage());
    jsonResponse(['status' => 'error', 'message' => 'Lỗi máy chủ.'], 500);
}