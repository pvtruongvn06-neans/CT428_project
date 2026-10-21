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
    const tableBody = document.getElementById('applications-list');

    // Tải danh sách ứng tuyển nếu đang ở trang Lịch sử
    if (tableBody) {
        fetchMyApplications();
    }

    function fetchMyApplications() {
        fetch('../api/my_applications.php')
            .then(response => {
                if (!response.ok) throw new Error('Mã lỗi: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' && Array.isArray(data.data) && data.data.length > 0) {
                    let html = '';
                    data.data.forEach((item, index) => {
                        const jobTitle = item.TieuDeTin || 'Chưa cập nhật';
                        const company  = item.TenCongTy || item.TenNhaTuyenDung || 'N/A';
                        const date     = item.NgayNop || '';
                        const status   = item.TrangThai || 'Chờ duyệt';
                        const cvPath   = item.DuongDanCV || '#';

                        let badgeClass = 'bg-warning text-dark';
                        if (status === 'Phù hợp' || status === 'Đã duyệt') badgeClass = 'bg-success';
                        if (status === 'Từ chối') badgeClass = 'bg-danger';

                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td class="fw-bold">${jobTitle}</td>
                                <td>${company}</td>
                                <td class="small text-muted">${date}</td>
                                <td><span class="badge ${badgeClass}">${status}</span></td>
                                <td>
                                    ${cvPath !== '#' 
                                        ? `<a href="${cvPath}" target="_blank" class="btn btn-sm btn-outline-info">
                                             <i class="bi bi-file-earmark-pdf me-1"></i>Xem CV
                                           </a>`
                                        : `<span class="text-muted small">Không có file</span>`
                                    }
                                </td>
                            </tr>
                        `;
                    });
                    tableBody.innerHTML = html;
                } else {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Bạn chưa nộp hồ sơ ứng tuyển nào.
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Lỗi khi tải lịch sử:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-danger">
                            Không thể tải danh sách ứng tuyển. Vui lòng thử lại sau!
                        </td>
                    </tr>
                `;
            });
    }
});

// Bắt sự kiện Đăng xuất trên trang Lịch sử ứng tuyển
document.addEventListener('click', function (e) {
    const logoutBtn = e.target.closest('.btn-logout');

    if (logoutBtn) {
        e.preventDefault();

        fetch('../api/auth.php?action=logout')
            .then(response => response.json())
            .then(data => {
                window.location.href = 'index.php';
            })
            .catch(error => {
                console.error('Lỗi đăng xuất:', error);
                window.location.href = 'index.php';
            });
    }
});