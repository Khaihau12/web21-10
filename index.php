<?php
require_once 'dbadmin.php';

$db = new dbadmin();

// Kiểm tra quyền: chỉ admin hoặc editor mới được truy cập
$user = $db->getCurrentUser();
if (!$user || !in_array($user['role'], ['admin', 'editor'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Trang Quản Trị Web Thể Thao</h1>
        <p class="success">Xin chào, <?php echo htmlspecialchars($user['display_name'] ?? $user['username']); ?>!</p>
        
        <h2>Menu Chức Năng</h2>
    <ul>
        <li><a href="demo_danh_sach_loai_tin.php">Danh Sách Loại Tin</a></li>
        <li><a href="category_list.php">Quản Lý Category</a></li>
        <li><a href="admin_them_tin.php">Thêm Tin Mới</a></li>
        <li><a href="danhsachbaidang.php">Danh Sách Bài Đăng</a></li>
        <li><a href="logout.php">Đăng Xuất</a></li>
    </ul>
    
    <hr>
    <p><i>Hệ thống quản lý web thể thao</i></p>
    </div>
</body>
</html>
