<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

$message = "";
$message_type = "";

// Lấy ID từ URL
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra ID hợp lệ
if ($category_id <= 0) {
    $message = "ID chuyên mục không hợp lệ!";
    $message_type = "error";
} else {
    // Lấy thông tin chuyên mục hiện tại
    $category = $db->layChuyenMucTheoId($category_id);
    
    if (!$category) {
        $message = "Không tìm thấy chuyên mục!";
        $message_type = "error";
    }
}

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($category)) {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $parent_id = $_POST['parent_id'];
    
    // Sửa chuyên mục
    if ($db->suaChuyenMuc($category_id, $name, $slug, $parent_id)) {
        $message = "✓ Sửa chuyên mục thành công!";
        $message_type = "success";
        
        // Lấy lại thông tin sau khi sửa
        $category = $db->layChuyenMucTheoId($category_id);
    } else {
        $message = "✗ Lỗi khi sửa chuyên mục!";
        $message_type = "error";
    }
}

// Lấy danh sách chuyên mục để chọn parent (trừ chính nó)
$categories = $db->getList("categories");
?>

<div class="content-header">
    <h2>✏️ Chỉnh Sửa Chuyên Mục</h2>
</div>

<div class="content-body">
    <?php if ($message): ?>
        <p class="<?= $message_type == 'success' ? 'success' : 'error' ?>">
            <?= $message ?>
        </p>
    <?php endif; ?>
    
    <?php if (isset($category)): ?>
    <form method="POST" action="">
        <label>Tên chuyên mục:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        
        <label>Slug (để trống tự động tạo):</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($category['slug']) ?>">
        
        <label>Chuyên mục cha (để trống nếu là chuyên mục gốc):</label>
        <select name="parent_id">
            <option value="">-- Không có --</option>
            <?php foreach ($categories as $cat): ?>
                <?php if ($cat['category_id'] != $category_id): // Không cho chọn chính nó ?>
                    <option value="<?= $cat['category_id'] ?>" 
                            <?= ($category['parent_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        
        <button type="submit" class="btn btn-success">✓ Lưu Thay Đổi</button>
        <a href="?page=categories" class="btn">← Quay lại danh sách</a>
    </form>
    <?php else: ?>
        <p>Không thể chỉnh sửa chuyên mục này.</p>
        <a href="?page=categories" class="btn">← Quay lại danh sách</a>
    <?php endif; ?>
</div>
