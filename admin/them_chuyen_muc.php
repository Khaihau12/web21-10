<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

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

<div class="content-header">
    <h2>➕ Thêm Chuyên Mục Mới</h2>
</div>

<div class="content-body">    <?php if ($message): ?>
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
        
        <button type="submit" class="btn btn-success">✓ Thêm Chuyên Mục</button>
        <a href="?page=categories" class="btn">← Quay lại danh sách</a>
    </form>
</div>
