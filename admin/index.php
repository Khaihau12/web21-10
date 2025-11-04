<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

// Khởi tạo database
$db = new dbadmin();

// Lấy thông tin user hiện tại
$currentUser = $db->getCurrentUser();

// Lấy trang hiện tại từ URL (mặc định là dashboard)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Lấy thống kê tổng quan
$conn = $db->getConnection();
$tongBaiViet = $conn->query("SELECT COUNT(*) as total FROM articles")->fetch_assoc()['total'];
$tongChuyenMuc = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];
$tongUser = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user'")->fetch_assoc()['total'];
$baiVietMoiNhat = $conn->query("SELECT COUNT(*) as total FROM articles WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Web Thể Thao</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { 
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* HEADER - ĐƠN GIẢN */
        .admin-header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            font-size: 22px;
            margin: 0;
        }
        .admin-header .user-info {
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 5px;
        }
        .admin-header .user-info strong {
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* LAYOUT */
        .admin-layout {
            display: flex;
            flex: 1;
        }
        
        /* LEFT SIDEBAR - ĐƠN GIẢN */
        .admin-sidebar {
            width: 220px;
            background-color: #34495e;
            color: white;
            padding: 10px 0;
            min-height: 100%;
        }
        .admin-sidebar h3 {
            padding: 10px 15px;
            font-size: 12px;
            color: #ffc107;
            background-color: #2c3e50;
            margin-bottom: 5px;
        }
        .admin-menu {
            list-style: none;
        }
        .admin-menu li a {
            display: block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            background-color: transparent;
        }
        .admin-menu li a:hover {
            background-color: #2c3e50;
        }
        .admin-menu li a.active {
            background-color: #4CAF50;
            color: white;
        }
        .admin-menu li a i {
            margin-right: 8px;
            width: 18px;
            color: white;
        }
        
        /* MAIN CONTENT - ĐƠN GIẢN */
        .admin-main {
            flex: 1;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .content-header {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #4CAF50;
            margin-bottom: 20px;
        }
        .content-header h2 {
            color: #333;
            font-size: 24px;
        }
        .content-body {
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
        }
        
        /* STATS CARDS - ĐƠN GIẢN */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: #2196F3;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .stat-card.green { background-color: #4CAF50; }
        .stat-card.orange { background-color: #FF9800; }
        .stat-card.blue { background-color: #2196F3; }
        .stat-card h3 {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
        }
        
        /* FOOTER - ĐƠN GIẢN */
        .admin-footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 13px;
        }
        
        /* TABLE - ĐƠN GIẢN */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        
        /* BUTTON - ĐƠN GIẢN */
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            margin: 3px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { background-color: #1976D2; }
        .btn-danger { background-color: #f44336; }
        .btn-danger:hover { background-color: #d32f2f; }
        .btn-success { background-color: #4CAF50; }
        .btn-success:hover { background-color: #388E3C; }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="admin-header">
        <h1>🏆 Quản Trị Web Thể Thao</h1>
        <?php if ($currentUser): ?>
            <div class="user-info">
                Chào <strong><?php echo $currentUser['role'] == 'admin' ? 'Admin' : 'Editor'; ?></strong> 
                <?php echo htmlspecialchars($currentUser['display_name']); ?>
            </div>
        <?php endif; ?>
    </header>

    <!-- LAYOUT: SIDEBAR + MAIN -->
    <div class="admin-layout">
        <!-- LEFT SIDEBAR -->
        <aside class="admin-sidebar">
            <h3>� MENU CHÍNH</h3>
            <ul class="admin-menu">
                <li>
                    <a href="?page=dashboard" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Trang Chủ
                    </a>
                </li>
                <li>
                    <a href="?page=articles" class="<?php echo ($page == 'articles') ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper"></i> Danh Sách Bài Viết
                    </a>
                </li>
                <li>
                    <a href="?page=add-article" class="<?php echo ($page == 'add-article') ? 'active' : ''; ?>">
                        <i class="fas fa-plus"></i> Thêm Bài Viết
                    </a>
                </li>
                <li>
                    <a href="?page=categories" class="<?php echo ($page == 'categories') ? 'active' : ''; ?>">
                        <i class="fas fa-folder"></i> Danh Sách Chuyên Mục
                    </a>
                </li>
                <li>
                    <a href="?page=add-category" class="<?php echo ($page == 'add-category') ? 'active' : ''; ?>">
                        <i class="fas fa-plus"></i> Thêm Chuyên Mục
                    </a>
                </li>
                <li>
                    <a href="?page=demo-loai-tin" class="<?php echo ($page == 'demo-loai-tin') ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> Demo Danh Sách Loại Tin
                    </a>
                </li>
            </ul>
            
            <h3>⚙️ HỆ THỐNG</h3>
            <ul class="admin-menu">
                <li>
                    <a href="../index.php" target="_blank">
                        <i class="fas fa-globe"></i> Xem Website
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                    </a>
                </li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-main">
            <?php
            // SWITCH CASE - Hiển thị nội dung theo page
            switch ($page) {
                case 'dashboard':
                    // TRANG CHỦ - DASHBOARD
                    ?>
                    <div class="content-header">
                        <h2>📊 Trang Chủ Quản Trị</h2>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card blue">
                            <h3>Tổng Bài Viết</h3>
                            <div class="number"><?php echo $tongBaiViet; ?></div>
                        </div>
                        <div class="stat-card green">
                            <h3>Tổng Chuyên Mục</h3>
                            <div class="number"><?php echo $tongChuyenMuc; ?></div>
                        </div>
                        <div class="stat-card orange">
                            <h3>Tổng User</h3>
                            <div class="number"><?php echo $tongUser; ?></div>
                        </div>
                        <div class="stat-card">
                            <h3>Bài Mới Hôm Nay</h3>
                            <div class="number"><?php echo $baiVietMoiNhat; ?></div>
                        </div>
                    </div>
                    
                    <div class="content-body">
                        <h3>� Bài Viết Mới Nhất</h3>
                        <?php
                        $recent = $conn->query("SELECT a.title, a.created_at, c.name as category_name 
                                               FROM articles a 
                                               LEFT JOIN categories c ON a.category_id = c.category_id 
                                               ORDER BY a.created_at DESC LIMIT 5");
                        ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Tiêu Đề</th>
                                    <th>Chuyên Mục</th>
                                    <th>Ngày Đăng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $recent->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    break;

                case 'articles':
                    // DANH SÁCH BÀI VIẾT
                    include 'danhsachbaidang.php';
                    break;

                case 'add-article':
                    // THÊM BÀI VIẾT
                    include 'admin_them_tin.php';
                    break;

                case 'categories':
                    // DANH SÁCH CHUYÊN MỤC
                    include 'category_list.php';
                    break;

                case 'add-category':
                    // THÊM CHUYÊN MỤC
                    include 'them_chuyen_muc.php';
                    break;

                case 'edit-category':
                    // SỬA CHUYÊN MỤC
                    include 'chinhsua_chuyen_muc.php';
                    break;

                case 'demo-loai-tin':
                    // DEMO DANH SÁCH LOẠI TIN
                    include 'demo_danh_sach_loai_tin.php';
                    break;
            }
            ?>
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="admin-footer">
        &copy; 2025 Web Thể Thao - Quản Trị Hệ Thống
    </footer>
</body>
</html>
