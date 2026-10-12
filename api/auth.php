
<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

// Ham tra ket qua JSON va ket thuc request
function jsonResponse(array $data, int $httpCode = 200): void
{
    http_response_code($httpCode);

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
    );

    exit;
}

try {
    // Ket noi CSDL: auth.php nam trong thu muc api/
    require_once __DIR__ . '/../config/database.php';

    // Bao dam file ket noi cung cap bien $pdo
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new RuntimeException(
            'Bien ket noi PDO chua duoc khoi tao.'
        );
    }

    // Lay action tren URL
    $action = $_GET['action'] ?? '';

    // Doc JSON; neu khong co JSON thi thu lay du lieu POST
    $rawBody = file_get_contents('php://input');
    $decoded = json_decode($rawBody, true);

    $input = is_array($decoded) ? $decoded : $_POST;

    switch ($action) {

        // =====================================
        // DANG KY
        // =====================================

        case 'register':

            // 1. Lay du lieu tu request
            $ho_ten = trim($input['ho_ten'] ?? '');
            $email = trim($input['email'] ?? '');
            $mat_khau = $input['mat_khau'] ?? '';
            $vai_tro = $input['vai_tro'] ?? '';
            $ten_cong_ty = trim($input['company_name'] ?? '');

            // 2. Kiem tra du lieu
            if (
                $ho_ten === '' ||
                !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                !is_string($mat_khau) ||
                strlen($mat_khau) < 6 ||
                !in_array(
                    $vai_tro,
                    ['ung_vien', 'nha_tuyen_dung'],
                    true
                ) ||
                (
                    $vai_tro === 'nha_tuyen_dung' &&
                    $ten_cong_ty === ''
                )
            ) {
                jsonResponse([
                    'status' => 'error',
                    'message' => 'Du lieu dang ky khong hop le.'
                ], 400);
            }

            // 3. Kiem tra email da ton tai chua
            $stmt = $pdo->prepare(
                'SELECT ID
         FROM NguoiDung
         WHERE Email = :email
         LIMIT 1'
            );

            $stmt->execute([
                'email' => $email
            ]);

            if ($stmt->fetch()) {
                jsonResponse([
                    'status' => 'error',
                    'message' => 'Email da duoc dang ky.'
                ], 409);
            }

            // 4. Bam mat khau truoc khi luu
            $hashed_password = password_hash(
                $mat_khau,
                PASSWORD_DEFAULT
            );

            // 5. Luu tai khoan va ten cong ty vao CSDL
            $insert = $pdo->prepare(
                'INSERT INTO NguoiDung
            (HoTen, TenCongTy, Email, MatKhau, VaiTro)
         VALUES
            (:hoten, :congty, :email, :pass, :role)'
            );

            try {
                $insert->execute([
                    'hoten' => $ho_ten,
                    'congty' => $vai_tro === 'nha_tuyen_dung'
                        ? $ten_cong_ty
                        : null,
                    'email' => $email,
                    'pass' => $hashed_password,
                    'role' => $vai_tro
                ]);
            } catch (PDOException $e) {
                // Ma 23000 thuong lien quan den vi pham rang buoc CSDL
                if ($e->getCode() === '23000') {
                    jsonResponse([
                        'status' => 'error',
                        'message' => 'Email da duoc dang ky hoac du lieu vi pham rang buoc.'
                    ], 409);
                }

                throw $e;
            }

            // 6. Thong bao dang ky thanh cong
            jsonResponse([
                'status' => 'success',
                'message' => 'Dang ky tai khoan thanh cong.'
            ]);

            break;


        // =====================================
        // DANG NHAP
        // =====================================
        case 'login':

            $email = trim($input['email'] ?? '');
            $mat_khau = $input['mat_khau'] ?? '';

            if ($email === '' || $mat_khau === '') {
                jsonResponse([
                    'status' => 'error',
                    'message' => 'Vui long nhap email va mat khau.'
                ], 400);
            }

            $stmt = $pdo->prepare(
                'SELECT ID, HoTen, Email, MatKhau, VaiTro
                 FROM NguoiDung
                 WHERE Email = :email
                 LIMIT 1'
            );

            $stmt->execute(['email' => $email]);

            $user = $stmt->fetch();

            if (
                $user &&
                password_verify($mat_khau, $user['MatKhau'])
            ) {
                // Tao Session ID moi sau khi dang nhap
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['ho_ten'] = $user['HoTen'];
                $_SESSION['vai_tro'] = $user['VaiTro'];

                jsonResponse([
                    'status' => 'success',
                    'message' => 'Dang nhap thanh cong.',
                    'data' => [
                        'id' => $user['ID'],
                        'ho_ten' => $user['HoTen'],
                        'vai_tro' => $user['VaiTro']
                    ]
                ]);
            }

            jsonResponse([
                'status' => 'error',
                'message' => 'Email hoac mat khau khong chinh xac.'
            ], 401);

            break;


        // =====================================
        // KIEM TRA SESSION
        // =====================================
        case 'check_session':

            if (
                isset(
                    $_SESSION['user_id'],
                    $_SESSION['ho_ten'],
                    $_SESSION['vai_tro']
                )
            ) {
                jsonResponse([
                    'status' => 'success',
                    'user' => [
                        'id' => $_SESSION['user_id'],
                        'ho_ten' => $_SESSION['ho_ten'],
                        'vai_tro' => $_SESSION['vai_tro']
                    ]
                ]);
            }

            jsonResponse([
                'status' => 'unauthenticated'
            ], 401);

            break;


        // =====================================
        // DANG XUAT
        // =====================================
        case 'logout':

            $_SESSION = [];

            // Xoa cookie Session neu PHP dang su dung cookie
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();

                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            session_destroy();

            jsonResponse([
                'status' => 'success',
                'message' => 'Da dang xuat.'
            ]);

            break;


        default:

            jsonResponse([
                'status' => 'error',
                'message' => 'Yeu cau khong hop le.'
            ], 400);
    }
} catch (Throwable $e) {

    // Ghi chi tiet loi vao log, khong dua thong tin noi bo ra trinh duyet
    error_log($e->__toString());

    jsonResponse([
        'status' => 'error',
        'message' => 'May chu gap loi. Vui long thu lai sau.'
    ], 500);
}
