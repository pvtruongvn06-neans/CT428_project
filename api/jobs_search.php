<?php
// api/jobs_public.php
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

    $keyword  = trim($_GET['keyword'] ?? '');
    $location = trim($_GET['location'] ?? '');

    $sql = "SELECT t.ID, t.TieuDe, t.MoTa, t.YeuCau, t.DiaDiem, t.HanNop, u.HoTen AS TenNhaTuyenDung 
            FROM TinTuyenDung t 
            JOIN NguoiDung u ON t.ID_NhaTuyenDung = u.ID 
            WHERE t.HanNop >= CURDATE()";
    $params = [];

    if ($keyword !== '') {
        $sql .= " AND (t.TieuDe LIKE :keyword OR t.MoTa LIKE :keyword)";
        $params['keyword'] = "%$keyword%";
    }

    if ($location !== '') {
        $sql .= " AND t.DiaDiem LIKE :location";
        $params['location'] = "%$location%";
    }

    $sql .= " ORDER BY t.ID DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    jsonResponse([
        'status' => 'success',
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    jsonResponse(['status' => 'error', 'message' => 'Lỗi máy chủ.'], 500);
}