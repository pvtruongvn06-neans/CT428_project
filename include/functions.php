<?php
/**
 * 1. Làm sạch dữ liệu chống tấn công XSS
 */
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map('sanitizeInput', $data);
        }
        return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * 2. Định dạng hiển thị mức lương
 */
if (!function_exists('formatSalary')) {
    function formatSalary($salary): string {
        if (empty($salary) || $salary === 'Thỏa thuận') {
            return 'Thỏa thuận';
        }
        if (is_numeric($salary)) {
            return number_format((float)$salary, 0, ',', '.') . ' VNĐ';
        }
        return htmlspecialchars((string)$salary);
    }
}

/**
 * 3. Định dạng ngày tháng chuẩn Việt Nam (DD/MM/YYYY)
 */
if (!function_exists('formatDateVN')) {
    function formatDateVN($dateString): string {
        if (empty($dateString) || $dateString === '0000-00-00' || $dateString === 'N/A') {
            return 'N/A';
        }
        $timestamp = strtotime($dateString);
        return $timestamp ? date('d/m/Y', $timestamp) : htmlspecialchars((string)$dateString);
    }
}

/**
 * 4. Chuyển hướng kèm thông báo Flash
 */
if (!function_exists('redirectWithMessage')) {
    function redirectWithMessage(string $url, string $message, string $type = 'info'): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
        header("Location: " . $url);
        exit();
    }
}