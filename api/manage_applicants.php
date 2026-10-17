<?php
// api/manage_applicants.php
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

    if (!isset($_SESSION['user_id'], $_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
        jsonResponse(['status' => 'error', 'message' => 'Không có quyền truy cập.'], 403);
    }

    $userId = (int) $_SESSION['user_id'];
    $method = $_SERVER['REQUEST_METHOD'];

    // LẤY DANH SÁCH ỨNG VIÊN (GET)
    if ($method === 'GET') {
        $stmt = $pdo->prepare(
            'SELECT h.ID AS ID_HoSo, h.FileCV, h.TrangThai, h.CreatedAt AS NgayNop,
                    u.HoTen AS TenUngVien, u.Email, u.SoDienThoai,
                    t.TieuDe AS TenTinTuyenDung
             FROM HoSoUngTuyen h
             JOIN TinTuyenDung t ON h.ID_TinTuyenDung = t.ID
             JOIN NguoiDung u ON h.ID_UngVien = u.ID
             WHERE t.ID_NhaTuyenDung = :user_id
             ORDER BY h.ID DESC'
        );
        $stmt->execute(['user_id' => $userId]);

        jsonResponse(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    // CẬP NHẬT TRẠNG THÁI HỒ SƠ (PUT)
    if ($method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $hoSoId = filter_var($input['id_hoso'] ?? null, FILTER_VALIDATE_INT);
        $trangThai = trim($input['trang_thai'] ?? '');

        if (!$hoSoId || !in_array($trangThai, ['cho_duyet', 'phu_hop', 'tu_choi'], true)) {
            jsonResponse(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.'], 400);
        }

        $stmt = $pdo->prepare(
            'UPDATE HoSoUngTuyen h
             JOIN TinTuyenDung t ON h.ID_TinTuyenDung = t.ID
             SET h.TrangThai = :trang_thai
             WHERE h.ID = :hoso_id AND t.ID_NhaTuyenDung = :user_id'
        );
        $stmt->execute(['trang_thai' => $trang_thai, 'hoso_id' => $hoSoId, 'user_id' => $userId]);

        jsonResponse(['status' => 'success', 'message' => 'Cập nhật trạng thái hồ sơ thành công.']);
    }

    jsonResponse(['status' => 'error', 'message' => 'Phương thức không hỗ trợ.'], 405);

} catch (Throwable $e) {
    error_log($e->getMessage());
    jsonResponse(['status' => 'error', 'message' => 'Lỗi máy chủ.'], 500);
}