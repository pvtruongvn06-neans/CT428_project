<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Thông Tin Tuyển Dụng và Ứng Tuyển</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png">
    
    <!-- Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Arial, sans-serif; }
        
        /* Navbar tông màu Mẫu */
        .navbar-brand { font-weight: 800; font-size: 20px; color: #0d3b66 !important; }
        .nav-link { font-weight: 600; color: #333 !important; font-size: 14px; margin-right: 5px; }
        .nav-link:hover { color: #0284c7 !important; }
        
        /* Nút màu Cam nổi bật giống Mẫu */
        .btn-orange { background-color: #f39c12; color: #fff; font-weight: 600; }
        .btn-orange:hover { background-color: #e08e0b; color: #fff; }

        /* Banner Hero Tông màu Xanh Navy đậm giống Mẫu */
        .hero-banner { 
            background: linear-gradient(135deg, #0a2540 0%, #12518e 100%); 
            color: white; 
            padding: 45px 0 50px 0; 
        }
        .hero-title { 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            color: #41b6ff; /* Chữ màu Xanh Cyan sáng */
        }
        
        /* Thanh tìm kiếm Bo tròn Pill Search Box */
        .search-container {
            background: #ffffff;
            border-radius: 50px;
            padding: 6px 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }
        .search-input { border: none; box-shadow: none !important; font-size: 15px; }
        .search-select { border: none; border-left: 1px solid #e2e8f0; border-radius: 0; box-shadow: none !important; font-size: 14px; color: #555; }
        
        /* Tiêu đề mục */
        .section-title {
            font-weight: 800;
            text-transform: uppercase;
            color: #0d3b66;
            letter-spacing: 0.5px;
        }

        /* Thẻ Tin tuyển dụng */
        .job-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s ease-in-out;
            background: #ffffff;
        }
        .job-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-color: #0284c7;
        }
    </style>
</head>
<body>

    <!-- 1. THANH MENU (Đúng chức năng Đồ án + Tông màu Mẫu) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../assets/images/icon.png" alt="Logo" width="40" height="40" class="me-2">
                <span>CỔNG TUYỂN DỤNG</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-3">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="index.php"><i class="bi bi-house-door me-1"></i> Trang chủ</a>
                    </li>
                    <!-- Link dành cho Ứng viên -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#"><i class="bi bi-file-earmark-person me-1"></i> Hồ sơ đã ứng tuyển</a>
                    </li>
                    <!-- Link dành cho Nhà tuyển dụng -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-primary" href="#"><i class="bi bi-briefcase me-1"></i> Quản lý tin tuyển dụng</a>
                    </li>
                </ul>

                <!-- Nút Đăng nhập (Cam) & Đăng ký phân vai trò -->
                <div class="d-flex gap-2 align-items-center">
                    <a href="login.php" class="btn btn-orange btn-sm px-3 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                    </a>
                    
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-plus me-1"></i> Đăng ký
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="register.php?role=candidate"><i class="bi bi-person me-2"></i>Tài khoản Ứng viên</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="register.php?role=employer"><i class="bi bi-building me-2"></i>Tài khoản Nhà tuyển dụng</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. KHU VỰC TÌM KIẾM BANNER (Tông màu Navy & Cyan của Mẫu) -->
    <section class="hero-banner mb-4">
        <div class="container text-center">
            <h2 class="hero-title mb-2">VIỆC LÀM HÀNG ĐẦU</h2>
            <p class="text-white-50 mb-4 fs-6">Kết nối Nhà tuyển dụng với Ứng viên tiềm năng</p>
            
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <!-- Form Tìm kiếm giữ nguyên ID cho JS -->
                    <form id="search-job-form">
                        <div class="search-container d-flex align-items-center">
                            <i class="bi bi-search text-muted fs-5 ms-2 me-2"></i>
                            <input type="text" id="keyword" class="form-control search-input" placeholder="Tên công việc, kỹ năng, yêu cầu tuyển dụng...">
                            
                            <div class="d-none d-md-block flex-shrink-0 me-2" style="width: 190px;">
                                <select class="form-select search-select" id="location">
                                    <option value="">📍 Tất cả địa điểm</option>
                                    <option value="Cần Thơ">Cần Thơ</option>
                                    <option value="TP.HCM">TP. Hồ Chí Minh</option>
                                    <option value="Hà Nội">Hà Nội</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold flex-shrink-0">
                                <i class="bi bi-search me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. DANH SÁCH TIN TUYỂN DỤNG (Đổ dữ liệu từ TinTuyenDung) -->
    <div class="container my-4">
        <div class="bg-white p-4 rounded-3 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title m-0"><i class="bi bi-briefcase-fill text-primary me-2"></i>VIỆC LÀM MỚI NHẤT</h4>
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2 rounded-pill">Đồ án CT428</span>
            </div>

            <!-- Khối chứa danh sách việc làm (Giữ nguyên ID #job-list) -->
            <div id="job-list" class="row g-3">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary me-2" role="status"></div>
                    <span class="text-muted">Đang tải dữ liệu tin tuyển dụng...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. HƯỚNG DẪN QUY TRÌNH ỨNG TIỂN -->
    <div class="bg-white border-top py-4 mt-5">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <i class="bi bi-search fs-2 text-primary"></i>
                        <h6 class="fw-bold mt-2 text-dark">1. Tìm kiếm việc làm</h6>
                        <small class="text-muted">Tra cứu tin tuyển dụng theo vị trí và yêu cầu phù hợp.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <i class="bi bi-file-earmark-pdf fs-2 text-warning"></i>
                        <h6 class="fw-bold mt-2 text-dark">2. Nộp hồ sơ (CV)</h6>
                        <small class="text-muted">Ứng viên gửi đường dẫn CV trực tiếp cho nhà tuyển dụng.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                        <h6 class="fw-bold mt-2 text-dark">3. Theo dõi trạng thái</h6>
                        <small class="text-muted">Cập nhật tiến độ duyệt hồ sơ từ nhà tuyển dụng.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap & JS dự án -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/job_board.js"></script>
</body>
</html>