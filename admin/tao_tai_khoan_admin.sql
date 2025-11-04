-- =====================================================
-- TẠO TÀI KHOẢN ADMIN VỚI PASSWORD MÃ HÓA MD5
-- =====================================================
-- 
-- Thông tin đăng nhập:
-- Username: admin
-- Password: admin123
-- Password đã mã hóa MD5: 0192023a7bbd73250516f069df18b500
--
-- =====================================================

-- Xóa tài khoản admin cũ nếu tồn tại
DELETE FROM users WHERE username = 'admin' AND role = 'admin';

-- Thêm tài khoản admin mới
INSERT INTO users (username, email, password, role, created_at) 
VALUES ('admin', 'admin@webthethao.com', '0192023a7bbd73250516f069df18b500', 'admin', NOW());


