<?php
require_once "dbadmin.php";

$db = new dbadmin();
$message = "";

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $parent_id = $_POST['parent_id'];
    
    // Thêm chuyên mục
    if ($db->themChuyenMuc($name, $slug, $parent_id)) {
        $message = "Thêm chuyên mục thành công!";
    } else {
        $message = "Lỗi khi thêm chuyên mục!";
    }
}

// Lấy danh sách chuyên mục để chọn parent
$categories = $db->getList("categories");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Chuyên Mục</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Thêm Chuyên Mục Mới</h1>
    
    <?php if ($message): ?>
        <p class="<?= strpos($message, 'thành công') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </p>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label>Tên chuyên mục:</label>
        <input type="text" name="name" required>
        
        <label>Slug (để trống tự động tạo):</label>
        <input type="text" name="slug">
        
        <label>Chuyên mục cha (để trống nếu là chuyên mục gốc):</label>
        <select name="parent_id">
            <option value="">-- Không có --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>"><?= $cat['name'] ?></option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Thêm chuyên mục</button>
    </form>
    
    <hr>
    <a href="category_list.php">← Quay lại danh sách</a>
</div>
</body>
</html>
