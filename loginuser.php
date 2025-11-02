<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Web Thể Thao</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS riêng cho form đăng nhập */
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .login-container h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-login:hover {
            background-color: #2980b9;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
        }
        
        .form-footer a {
            color: #3498db;
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        
        .message.error {
            background-color: #ffe6e6;
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }
        
        .message.success {
            background-color: #e6ffe6;
            color: #27ae60;
            border: 1px solid #27ae60;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="container">
            <h1>⚽ Web Thể Thao</h1>
            <p>Cập nhật tin tức thể thao mới nhất</p>
        </div>
    </header>

    <!-- NAVIGATION -->
    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="loginuser.php">Đăng nhập</a></li>
                <li><a href="register.php">Đăng ký</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        <div class="container">
            <div class="login-container">
                <h2>Đăng nhập</h2>
                
                <!-- Thông báo (ẩn, dùng khi có PHP) -->
                <!-- <div class="message error">Sai tên đăng nhập hoặc mật khẩu!</div> -->
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>
                    
                    <button type="submit" class="btn-login">Đăng nhập</button>
                </form>
                
                <div class="form-footer">
                    <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
                    <p><a href="index.php">← Quay lại trang chủ</a></p>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Web Thể Thao. Tất cả quyền được bảo lưu.</p>
            <p>Liên hệ: info@webthethao.com | Hotline: 1900-xxxx</p>
        </div>
    </footer>
</body>
</html>
