// Khai báo hàm Đăng xuất ở phạm vi toàn cục (Global)
window.handleLogout = function (event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    // Tự động kiểm tra vị trí để gọi đúng API auth.php
    const apiPath = window.location.pathname.includes('/views/') 
        ? '../api/auth.php?action=logout' 
        : 'api/auth.php?action=logout';

    fetch(apiPath)
        .then(response => response.json())
        .then(data => {
            // Đăng xuất thành công -> Chuyển về index.php
            window.location.href = 'index.php';
        })
        .catch(error => {
            console.error('Lỗi đăng xuất:', error);
            window.location.href = 'index.php';
        });
};

document.addEventListener('DOMContentLoaded', function () {
    // Nơi viết code load danh sách việc làm / tìm kiếm trên Trang Chủ (nếu có)
});

// Bắt sự kiện click nút Đăng xuất trên trang chủ (index.php)
document.addEventListener('click', function (e) {
    const logoutBtn = e.target.closest('.btn-logout');

    if (logoutBtn) {
        e.preventDefault(); // Chặn hành vi mở link mặc định

        fetch('../api/auth.php?action=logout')
            .then(response => response.json())
            .then(data => {
                // Đăng xuất xong load lại trang chủ index.php
                window.location.href = 'index.php';
            })
            .catch(error => {
                console.error('Lỗi khi đăng xuất:', error);
                window.location.href = 'index.php';
            });
    }
});