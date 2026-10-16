<?php
session_start();

date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json; charset=utf-8');

function jsonResponse(array $data, int $httpCode = 200): void
{
    http_response_code($httpCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    require_once __DIR__ . '/../config/database.php';

    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new RuntimeException('Biến kết nối PDO chưa được khởi tạo.');
    }

    // 1. Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'], $_SESSION['vai_tro'])) {
        jsonResponse([
            'status' => 'error',
            'message' => 'Vui lòng đăng nhập.'
        ], 401);
    }

    // 2. Kiểm tra quyền: Chỉ tài khoản Ứng viên mới được xem
    if ($_SESSION['vai_tro'] !== 'ung_vien') {
        jsonResponse([
            'status' => 'error',
            'message' => 'Bạn không có quyền truy cập lịch sử ứng tuyển.'
        ], 403);
    }

    $ungVienId = (int) $_SESSION['user_id'];
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method !== 'GET') {
        jsonResponse([
            'status' => 'error',
            'message' => 'Phương thức HTTP không được hỗ trợ.'
        ], 405);
    }

    // 3. Truy vấn lấy lịch sử nộp CV (JOIN với bảng TinTuyenDung và NguoiDung để lấy thông tin Công ty)
    $sql = "SELECT 
                h.ID AS ID_HoSo,
                h.DuongDanCV,
                h.NgayNop,
                h.TrangThai,
                t.ID AS ID_TinTuyenDung,
                t.TieuDe AS TieuDeTin,
                t.DiaDiem,
                t.HanNop,
                u.TenCongTy,
                u.HoTen AS TenNhaTuyenDung
            FROM HoSoUngTuyen h
            JOIN TinTuyenDung t ON h.ID_TinTuyenDung = t.ID
            JOIN NguoiDung u ON t.ID_NhaTuyenDung = u.ID
            WHERE h.ID_UngVien = :ung_vien_id
            ORDER BY h.NgayNop DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ung_vien_id' => $ungVienId]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Trả về kết quả
    jsonResponse([
        'status' => 'success',
        'data' => $applications
    ]);

} catch (Throwable $e) {
    error_log($e->__toString());

    jsonResponse([
        'status' => 'error',
        'message' => 'Lỗi máy chủ: ' . $e->getMessage()
    ], 500);
}