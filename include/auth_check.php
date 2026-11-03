<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 1. Kiểm tra trạng thái đã đăng nhập chưa
 */
if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

/**
 * 2. Lấy ID người dùng hiện tại
 */
if (!function_exists('getCurrentUserId')) {
    function getCurrentUserId(): int {
        return (int) ($_SESSION['user_id'] ?? 0);
    }
}

/**
 * 3. Kiểm tra xem người dùng có phải Nhà tuyển dụng không
 */
if (!function_exists('isEmployer')) {
    function isEmployer(): bool {
        if (!isLoggedIn()) return false;
        $role = strtolower(trim($_SESSION['vai_tro'] ?? ''));
        return $role === 'nha_tuyen_dung' || $role === 'employer';
    }
}

/**
 * 4. Bắt buộc đăng nhập (Chuyển hướng tới login.php nếu chưa)
 */
if (!function_exists('requireLogin')) {
    function requireLogin(): void {
        if (!isLoggedIn()) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['flash_message'] = "Vui lòng đăng nhập để tiếp tục!";
            $_SESSION['flash_type'] = "error";
            header("Location: login.php");
            exit();
        }
    }
}

/**
 * 5. Bắt buộc vai trò Nhà tuyển dụng
 */
if (!function_exists('requireEmployer')) {
    function requireEmployer(): void {
        requireLogin();
        if (!isEmployer()) {
            $_SESSION['flash_message'] = "Trang này chỉ dành cho Nhà tuyển dụng!";
            $_SESSION['flash_type'] = "error";
            header("Location: index.php");
            exit();
        }
    }
}

/**
 * 6. Bắt buộc vai trò Ứng viên
 */
if (!function_exists('requireCandidate')) {
    function requireCandidate(): void {
        requireLogin();
        if (isEmployer()) {
            header("Location: employer_dashboard.php");
            exit();
        }
    }
}