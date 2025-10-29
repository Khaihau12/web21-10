<?php
/**
 * TRANG ADMIN - THÊM TIN TỨC MỚI
 * File: admin_them_tin.php
 */

require_once 'dbadmin.php';

// Khởi tạo database
$db = new dbadmin();

// Biến lưu thông báo
$message = '';
$message_type = '';

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_url = '';
    
    // Xử lý upload ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $message = 'Chỉ chấp nhận file ảnh JPG, PNG, GIF!';
            $message_type = 'error';
        } elseif ($_FILES['image']['size'] > $max_size) {
            $message = 'File ảnh quá lớn! Tối đa 5MB.';
            $message_type = 'error';
        } else {
            // Tạo tên file unique
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $upload_path = __DIR__ . '/uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_url = '/web21-10/uploads/' . $filename;
            } else {
                $message = 'Lỗi khi upload ảnh!';
                $message_type = 'error';
            }
        }
    }
    
    // Nếu không có lỗi upload ảnh, tiếp tục thêm bài viết
    if ($message_type !== 'error') {
        // Lấy dữ liệu từ form
        $data = [
            'category_id' => $_POST['category_id'] ?? 0,
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'summary' => trim($_POST['summary'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'image_url' => $image_url,
            'author_id' => !empty($_POST['author_id']) ? (int)$_POST['author_id'] : null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0
        ];
        
        // Kiểm tra dữ liệu
        if (empty($data['category_id'])) {
            $message = 'Vui lòng chọn chuyên mục!';
            $message_type = 'error';
        } elseif (empty($data['title'])) {
            $message = 'Vui lòng nhập tiêu đề!';
            $message_type = 'error';
        } else {
            // Thêm bài viết
            $result = $db->themBaiViet($data);
            
            if ($result) {
                $message = '✅ Thêm bài viết thành công!<br>ID bài viết: <strong>' . $result . '</strong><br>Có thể tiếp tục thêm bài viết mới bên dưới.';
                $message_type = 'success';
                
                // Reset form để thêm bài mới
                $_POST = [];
            } else {
                $message = 'Lỗi khi thêm bài viết! Có thể slug đã tồn tại.';
                $message_type = 'error';
            }
        }
    }
}

// Lấy danh sách chuyên mục
$categories = $db->layDanhSachChuyenMuc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Tin Tức Mới</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Thêm Tin Tức Mới</h1>
    <p><i>Thêm bài viết mới vào hệ thống</i></p>
    
    <?php if ($message): ?>
        <div class="<?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        
        <!-- Chuyên mục -->
        <label>Chuyên mục <span style="color:red;">*</span></label>
        <select name="category_id" required>
            <option value="">-- Chọn chuyên mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['category_id']; ?>" 
                        <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <!-- Tiêu đề -->
        <label>Tiêu đề <span style="color:red;">*</span></label>
        <input type="text" name="title" placeholder="Nhập tiêu đề bài viết..." 
               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
        
        <!-- Slug -->
        <label>Slug (URL thân thiện)</label>
        <input type="text" name="slug" placeholder="vi-du: bai-viet-mau-so-1"
               value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>">
        <p style="font-size:12px; color:#999;">Để trống để tự động tạo từ tiêu đề</p>
        
        <!-- Tóm tắt -->
        <label>Tóm tắt</label>
        <textarea name="summary" rows="3" 
                  placeholder="Nhập tóm tắt ngắn gọn về bài viết..."><?php echo htmlspecialchars($_POST['summary'] ?? ''); ?></textarea>
        
        <!-- Nội dung -->
        <label>Nội dung chi tiết</label>
        <textarea name="content" rows="8"
                  placeholder="Nhập nội dung đầy đủ của bài viết..."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
        
        <!-- Upload ảnh -->
        <label>Ảnh đại diện</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/jpg">
        <p style="font-size:12px; color:#999;">Chọn ảnh JPG, PNG, GIF (tối đa 5MB)</p>
        
        <!-- Tin nổi bật -->
        <label>
            <input type="checkbox" id="is_featured" name="is_featured" value="1"
                   <?php echo (isset($_POST['is_featured'])) ? 'checked' : ''; ?>>
            Đánh dấu là tin nổi bật
        </label>
        
        <!-- Nút submit -->
        <button type="submit">Thêm Bài Viết</button>
        <button type="reset">Làm mới</button>
    </form>
    
    <hr>
    <a href="index.php">← Quay lại trang chủ</a>
</div>
</body>
</html>
