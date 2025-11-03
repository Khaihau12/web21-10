<?php
session_start();
require_once "dbuser.php";
$db = new dbuser();

// Kiểm tra đăng nhập
if (!$db->kiemTraDangNhap()) {
    header("Location: loginuser.php");
    exit;
}

// Lấy thông tin user
$currentUser = $db->layUserHienTai();

// Lấy danh mục cho menu
$danhMuc = $db->layTatCaChuyenMuc();

// Xử lý đăng xuất
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang cá nhân - Web Thể Thao</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .user-profile {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .user-info {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
            margin-bottom: 20px;
        }
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #3498db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            margin-right: 20px;
        }
        .user-details h2 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .user-details p {
            color: #7f8c8d;
            margin: 3px 0;
        }
        .user-tabs {
            display: flex;
            gap: 10px;
            border-bottom: 2px solid #ecf0f1;
            margin-bottom: 20px;
        }
        .tab-button {
            padding: 10px 20px;
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            font-size: 16px;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        .tab-button.active {
            color: #3498db;
            border-bottom-color: #3498db;
            font-weight: bold;
        }
        .tab-button:hover {
            color: #3498db;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .change-password-form {
            max-width: 500px;
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
        .btn-submit {
            padding: 12px 30px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #2980b9;
        }
        .btn-logout {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-logout:hover {
            background-color: #c0392b;
        }
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
        .empty-message i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 15px;
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
                            <a href="indexuser.php"><i class="fa fa-user"></i> <?php echo htmlspecialchars($currentUser['display_name'] ?: $currentUser['username']); ?></a>
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
            <div class="two-column" style="margin-top: 20px;">
                <aside class="sidebar">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="index.php">🏠 Trang chủ</a></li>
                        <li><a href="indexuser.php" style="color: #3498db; font-weight: bold;">👤 Trang cá nhân</a></li>
                        <li>
                            <form method="POST" style="margin-top: 10px;">
                                <button type="submit" name="logout" class="btn-logout" style="width: 100%;" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                                    <i class="fa fa-sign-out-alt"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                    <h3 style="margin-top: 30px;">Thống kê</h3>
                    <ul>
                        <li>📖 Bài đã đọc: <strong>12</strong></li>
                        <li>❤️ Bài yêu thích: <strong>5</strong></li>
                        <li>📅 Ngày tham gia: <strong><?php echo date('d/m/Y', strtotime($currentUser['created_at'] ?? 'now')); ?></strong></li>
                    </ul>
                </aside>
                <div class="content">
                    <div class="user-profile">
                        <div class="user-info">
                            <div class="user-avatar"><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></div>
                            <div class="user-details">
                                <h2><?php echo htmlspecialchars($currentUser['display_name'] ?: $currentUser['username']); ?></h2>
                                <p>📧 Email: <?php echo htmlspecialchars($currentUser['email'] ?? 'Chưa cập nhật'); ?></p>
                                <p>👤 Tên đăng nhập: <?php echo htmlspecialchars($currentUser['username']); ?></p>
                                <p>📅 Ngày tham gia: <?php echo date('d/m/Y', strtotime($currentUser['created_at'] ?? 'now')); ?></p>
                            </div>
                        </div>
                        <div class="user-tabs">
                            <button class="tab-button active" onclick="showTab( this)">Bài đã đọc gần đây</button>
                            <button class="tab-button" onclick="showTab(this)">Bài yêu thích</button>
                            <button class="tab-button" onclick="showTab(this)">Đổi mật khẩu</button>
                        </div>
                        <div id="recent" class="tab-content active">
                            <h3 style="margin-bottom: 20px;">Bài viết đã đọc gần đây</h3>
                            <div class="empty-message">
                                <i class="fa fa-book-open"></i>
                                <p>Bạn chưa đọc bài viết nào</p>
                                <a href="index.php">Về trang chủ xem tin</a>
                            </div>
                        </div>
                        <div id="favorite" class="tab-content">
                            <h3 style="margin-bottom: 20px;">Bài viết yêu thích</h3>
                            <div class="empty-message">
                                <i class="fa fa-heart"></i>
                                <p>Bạn chưa có bài viết yêu thích nào</p>
                                <a href="index.php">Về trang chủ xem tin</a>
                            </div>
                        </div>
                        <div id="password" class="tab-content">
                            <h3 style="margin-bottom: 20px;">Đổi mật khẩu</h3>
                            <form method="POST" class="change-password-form">
                                <div class="form-group">
                                    <label>Mật khẩu hiện tại *</label>
                                    <input type="password" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label>Mật khẩu mới *</label>
                                    <input type="password" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label>Nhập lại mật khẩu mới *</label>
                                    <input type="password" name="confirm_password" required>
                                </div>
                                <button type="submit" name="change_password" class="btn-submit">Đổi mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer style="margin-top:40px;padding:20px 0;border-top:1px solid #eee;color:#666;font-size:13px">
        <div class="container" style="display:flex;justify-content:space-between;align-items:center">
            <div>© 2025 Web Thể Thao - Tất cả vì người đọc.</div>
            <div style="opacity:.6">
                <a href="admin/login.php" title="Đăng nhập quản trị" style="color:#666;text-decoration:none">Quản trị</a>
            </div>
        </div>
    </footer>
    <script>
        function showTab(btn) {
            var tabs = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabs.length; i++) tabs[i].classList.remove("active");
            var buttons = document.getElementsByClassName("tab-button");
            for (var i = 0; i < buttons.length; i++) buttons[i].classList.remove("active");
            btn.classList.add("active");
            var index = Array.from(buttons).indexOf(btn);
            tabs[index].classList.add("active");
        }
    </script>
</body>
</html>
