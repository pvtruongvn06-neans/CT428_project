-- =========================================
-- 1. Tao co so du lieu Jobio
-- =========================================

CREATE DATABASE IF NOT EXISTS jobio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE jobio;

-- =========================================
-- 2. Bang nguoi dung
-- =========================================

CREATE TABLE IF NOT EXISTS NguoiDung (
    ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    Email VARCHAR(191) NOT NULL,
    MatKhau VARCHAR(255) NOT NULL,
    VaiTro ENUM('ung_vien', 'nha_tuyen_dung') NOT NULL,
    UNIQUE KEY uq_nguoidung_email (Email)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- =========================================
-- 3. Bang tin tuyen dung
-- =========================================

CREATE TABLE IF NOT EXISTS TinTuyenDung (
    ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TieuDe VARCHAR(255) NOT NULL,
    MoTa TEXT NOT NULL,
    YeuCau TEXT NOT NULL,
    DiaDiem VARCHAR(150) NOT NULL,
    HanNop DATE NOT NULL,
    ID_NhaTuyenDung INT UNSIGNED NOT NULL,
    INDEX idx_ttd_nhatuyendung (ID_NhaTuyenDung),
    INDEX idx_ttd_diadiem (DiaDiem),
    INDEX idx_ttd_handop (HanNop),
    CONSTRAINT fk_ttd_nhatuyendung FOREIGN KEY (ID_NhaTuyenDung) REFERENCES NguoiDung (ID) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- =========================================
-- 4. Bang ho so ung tuyen
-- =========================================

CREATE TABLE IF NOT EXISTS HoSoUngTuyen (
    ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ID_UngVien INT UNSIGNED NOT NULL,
    ID_TinTuyenDung INT UNSIGNED NOT NULL,
    DuongDanCV VARCHAR(500) NOT NULL,
    NgayNop DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TrangThai ENUM(
        'cho_duyet',
        'da_duyet',
        'tu_choi'
    ) NOT NULL DEFAULT 'cho_duyet',
    UNIQUE KEY uq_hsut_ungvien_tin (ID_UngVien, ID_TinTuyenDung),
    INDEX idx_hsut_tin_trangthai (ID_TinTuyenDung, TrangThai),
    INDEX idx_hsut_ungvien_ngaynop (ID_UngVien, NgayNop),
    CONSTRAINT fk_hsut_ungvien FOREIGN KEY (ID_UngVien) REFERENCES NguoiDung (ID) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_hsut_tin FOREIGN KEY (ID_TinTuyenDung) REFERENCES TinTuyenDung (ID) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;