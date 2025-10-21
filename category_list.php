<?php
require_once "dbadmin.php";

$db = new dbadmin();

// Kiểm tra quyền: chỉ admin hoặc editor mới được truy cập
$user = $db->getCurrentUser();
if (!$user || !in_array($user['role'], ['admin', 'editor'])) {
    header('Location: login.php');
    exit();
}

$categories = $db->getList("categories",);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Danh Sách Chuyên Mục</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên chuyên mục</th>
                <th>Slug</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat['category_id'] ?></td>
                    <td><?= $cat['name'] ?></td>
                    <td><?= $cat['slug'] ?></td>
                    <td>
                        <form method="POST" action="delete_category.php" onsubmit="return confirm('Bạn có chắc muốn xóa loại tin này?');">
                            <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == "success"): ?>
        <p class="success">Xóa loại tin thành công!</p>
    <?php endif; ?>
    
    <hr>
    <a href="index.php">← Quay lại trang chủ</a>
</div>
</body>
</html>