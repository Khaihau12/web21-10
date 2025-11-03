<?php
session_start();
require_once 'dbuser.php';
$db = new dbuser();

// Biến thông báo
$message = '';
$message_type = '';

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $message = 'Vui lòng nhập đầy đủ thông tin!';
        $message_type = 'error';
    } else {
        // Kiểm tra đăng nhập
        $userData = $db->dangNhap($username, $password);
        
        if ($userData) {
            // Đăng nhập thành công - Lưu thông tin vào session
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['display_name'] = $userData['display_name'];
            $_SESSION['role'] = $userData['role'];
            
            // Chuyển về trang chủ
            header('Location: index.php');
            exit;
        } else {
            $message = 'Tên đăng nhập hoặc mật khẩu không đúng!';
            $message_type = 'error';
        }
    }
}

// Lấy danh mục cho menu
$danhMuc = $db->layTatCaChuyenMuc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Tin Tức 24H</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* CSS riêng cho form đăng nhập */
        .login-container {
            max-width: 450px;
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
            font-size: 28px;
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
            padding: 12px;
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
    <!-- HEADER - Thanh header chính -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <!-- Top Row: Logo + Search + User -->
                <div class="header-top">
                    <!-- Logo -->
                    <div class="site-logo">
                        <a href="index.php">
                            <h1>📰 24H</h1>
                            <span>Tin Tức Thể Thao</span>
                        </a>
                    </div>
                    
                    <!-- Search & User -->
                    <div class="header-actions">
                        <form action="index.php" method="get" class="search-form">
                            <input type="text" name="q" placeholder="Tìm kiếm...">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                        <div class="user-links">
                            <a href="loginuser.php"><i class="fa fa-user"></i> Đăng nhập</a>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Menu - Dòng dưới -->
                <nav class="main-navigation">
                    <ul>
                        <li><a href="index.php"><i class="fa fa-home"></i> Trang Chủ</a></li>
                        <?php foreach($danhMuc as $dm): ?>
                        <li><a href="category.php?id=<?php echo $dm['category_id']; ?>"><?php echo htmlspecialchars($dm['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main>
        <div class="container">
            <div class="login-container">
                <h2>🔐 Đăng nhập</h2>
                
                <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
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
                
                <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
                

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer style="margin-top:40px;padding:20px 0;border-top:1px solid #eee;color:#666;font-size:13px">
        <div class="container" style="display:flex;justify-content:space-between;align-items:center">
            <div>© 2025 Web Thể Thao - Tất cả vì người đọc.</div>
            <div style="opacity:.6">
                <a href="admin/login.php" title="Đăng nhập quản trị" style="color:#666;text-decoration:none">Quản trị</a>
            </div>
        </div>
    </footer>
</body>
</html>
