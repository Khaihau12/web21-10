# HƯỚNG DẪN TẠO TÀI KHOẢN ADMIN

## Cách 1: Dùng phpMyAdmin (ĐƠN GIẢN NHẤT)

1. Mở trình duyệt, vào: `http://localhost/phpmyadmin`
2. Chọn database `webthethao` bên trái
3. Click tab **SQL** ở trên
4. Copy toàn bộ code SQL bên dưới và paste vào:

```sql
DELETE FROM users WHERE username = 'admin' AND role = 'admin';

INSERT INTO users (username, email, password, full_name, role, created_at) 
VALUES (
    'admin',
    'admin@webthethao.com',
    '0192023a7bbd73250516f069df18b500',
    'Quản Trị Viên',
    'admin',
    NOW()
);
```

5. Click nút **Go** (hoặc **Thực hiện**)
6. Xong! 

## Thông tin đăng nhập:

- **Username**: `admin`
- **Password**: `admin123`

## Cách 2: Thêm thủ công qua giao diện

1. Vào phpMyAdmin → database `webthethao` → bảng `users`
2. Click **Insert** (Chèn)
3. Điền thông tin:
   - username: `admin`
   - email: `admin@webthethao.com`
   - password: `0192023a7bbd73250516f069df18b500`
   - full_name: `Quản Trị Viên`
   - role: `admin`
4. Click **Go**

## Lưu ý:

- Password `admin123` đã được mã hóa thành MD5: `0192023a7bbd73250516f069df18b500`
- Hệ thống đã được cập nhật để tự động mã hóa MD5 khi đăng nhập
- Đăng nhập tại: `http://localhost/web21-10/admin/login.php`
