<?php
session_start();

// Thiết lập múi giờ Việt Nam
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

    // 1. Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'], $_SESSION['vai_tro'])) {
        jsonResponse([
            'status' => 'error',
            'message' => 'Vui lòng đăng nhập.'
        ], 401);
    }

    // 2. Kiểm tra phân quyền: Chỉ nhà tuyển dụng mới được quản lý tin
    if ($_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
        jsonResponse([
            'status' => 'error',
            'message' => 'Bạn không có quyền quản lý tin.'
        ], 403);
    }

    $userId = (int) $_SESSION['user_id'];
    $method = $_SERVER['REQUEST_METHOD'];

    // Đọc dữ liệu từ Body (JSON hoặc Form)
    $rawBody = file_get_contents('php://input');
    $decoded = json_decode($rawBody, true);
    $input = is_array($decoded) ? $decoded : $_POST;

    // Lấy ID thông minh: Ưu tiên URL (?id=x), nếu không có thì đọc từ Body JSON
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id && isset($input['id'])) {
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
    }

    // 3. XEM DANH SÁCH TIN ĐÃ ĐĂNG (GET)
    if ($method === 'GET') {
        $stmt = $pdo->prepare(
            'SELECT ID, TieuDe, MoTa, YeuCau, DiaDiem, HanNop, ID_NhaTuyenDung, CreatedAt
             FROM TinTuyenDung
             WHERE ID_NhaTuyenDung = :user_id
             ORDER BY ID DESC'
        );

        $stmt->execute(['user_id' => $userId]);

        jsonResponse([
            'status' => 'success',
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
    }

    // 4. THÊM TIN TUYỂN DỤNG MỚI (POST)
    if ($method === 'POST') {
        $tieuDe = trim($input['TieuDe'] ?? '');
        $moTa = trim($input['MoTa'] ?? '');
        $yeuCau = trim($input['YeuCau'] ?? '');
        $diaDiem = trim($input['DiaDiem'] ?? '');
        $hanNop = trim($input['HanNop'] ?? '');

        if ($tieuDe === '' || $moTa === '' || $yeuCau === '' || $diaDiem === '' || $hanNop === '') {
            jsonResponse([
                'status' => 'error',
                'message' => 'Vui lòng nhập đầy đủ thông tin.'
            ], 400);
        }

        $date = DateTime::createFromFormat('!Y-m-d', $hanNop);

        if (!$date || $date->format('Y-m-d') !== $hanNop) {
            jsonResponse([
                'status' => 'error',
                'message' => 'Hạn nộp phải có dạng YYYY-MM-DD.'
            ], 400);
        }

        if ($hanNop < date('Y-m-d')) {
            jsonResponse([
                'status' => 'error',
                'message' => 'Hạn nộp không được nằm trong quá khứ.'
            ], 400);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO TinTuyenDung
                (TieuDe, MoTa, YeuCau, DiaDiem, HanNop, ID_NhaTuyenDung)
             VALUES
                (:tieu_de, :mo_ta, :yeu_cau, :dia_diem, :han_nop, :user_id)'
        );

        $stmt->execute([
            'tieu_de' => $tieuDe,
            'mo_ta' => $moTa,
            'yeu_cau' => $yeuCau,
            'dia_diem' => $diaDiem,
            'han_nop' => $hanNop,
            'user_id' => $userId
        ]);

        jsonResponse([
            'status' => 'success',
            'message' => 'Tạo tin tuyển dụng thành công.',
            'data' => ['id' => (int) $pdo->lastInsertId()]
        ], 201);
    }

    // --- CÁC PHƯƠNG THỨC CẦN ID (PUT / DELETE) ---

    // Kiểm tra phương thức HTTP hợp lệ
    if (!in_array($method, ['PUT', 'DELETE'], true)) {
        jsonResponse([
            'status' => 'error',
            'message' => 'Phương thức HTTP không được hỗ trợ.'
        ], 405);
    }

    // Kiểm tra ID bài viết
    if (!$id || $id < 1) {
        jsonResponse([
            'status' => 'error',
            'message' => 'ID tin tuyển dụng không hợp lệ.'
        ], 400);
    }

    // 5. Kiểm tra tin có thuộc Nhà tuyển dụng này không
    $check = $pdo->prepare(
        'SELECT ID FROM TinTuyenDung WHERE ID = :id AND ID_NhaTuyenDung = :user_id'
    );
    $check->execute(['id' => $id, 'user_id' => $userId]);

    if (!$check->fetch()) {
        jsonResponse([
            'status' => 'error',
            'message' => 'Không tìm thấy tin hoặc bạn không có quyền thao tác.'
        ], 404);
    }

    // 6. SỬA TIN TUYỂN DỤNG (PUT)
    if ($method === 'PUT') {
        $tieuDe = trim($input['TieuDe'] ?? '');
        $moTa = trim($input['MoTa'] ?? '');
        $yeuCau = trim($input['YeuCau'] ?? '');
        $diaDiem = trim($input['DiaDiem'] ?? '');
        $hanNop = trim($input['HanNop'] ?? '');

        if ($tieuDe === '' || $moTa === '' || $yeuCau === '' || $diaDiem === '' || $hanNop === '') {
            jsonResponse([
                'status' => 'error',
                'message' => 'Cần gửi đầy đủ thông tin để cập nhật.'
            ], 400);
        }

        $date = DateTime::createFromFormat('!Y-m-d', $hanNop);
        if (!$date || $date->format('Y-m-d') !== $hanNop || $hanNop < date('Y-m-d')) {
            jsonResponse([
                'status' => 'error',
                'message' => 'Hạn nộp không hợp lệ.'
            ], 400);
        }

        $stmt = $pdo->prepare(
            'UPDATE TinTuyenDung
             SET TieuDe = :tieu_de,
                 MoTa = :mo_ta,
                 YeuCau = :yeu_cau,
                 DiaDiem = :dia_diem,
                 HanNop = :han_nop
             WHERE ID = :id AND ID_NhaTuyenDung = :user_id'
        );

        $stmt->execute([
            'tieu_de' => $tieuDe,
            'mo_ta' => $moTa,
            'yeu_cau' => $yeuCau,
            'dia_diem' => $diaDiem,
            'han_nop' => $hanNop,
            'id' => $id,
            'user_id' => $userId
        ]);

        jsonResponse([
            'status' => 'success',
            'message' => 'Cập nhật tin tuyển dụng thành công.'
        ]);
    }

    // 7. XÓA TIN TUYỂN DỤNG (DELETE)
    if ($method === 'DELETE') {
        $stmt = $pdo->prepare(
            'DELETE FROM TinTuyenDung WHERE ID = :id AND ID_NhaTuyenDung = :user_id'
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);

        jsonResponse([
            'status' => 'success',
            'message' => 'Xóa tin tuyển dụng thành công.'
        ]);
    }

} catch (Throwable $e) {
    error_log($e->getMessage());

    jsonResponse([
        'status' => 'error',
        'message' => 'Lỗi máy chủ. Vui lòng thử lại sau.'
    ], 500);
}