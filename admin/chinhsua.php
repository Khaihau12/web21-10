<?php
/**
 * TRANG ADMIN - CHỈNH SỬA BÀI VIẾT
 * File: chinhsua.php
 */

require_once 'dbadmin.php';

// Khởi tạo database
$db = new dbadmin();
$conn = $db->getConnection();

// Lấy article_id từ URL
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($article_id <= 0) {
    die('ID bài báo không hợp lệ');
}

// Lấy thông tin bài báo trước
$stmt = $conn->prepare('SELECT * FROM articles WHERE article_id=?');
$stmt->bind_param('i', $article_id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
if (!$article) {
    die('Không tìm thấy bài báo');
}

// Biến lưu thông báo
$message = '';
$message_type = '';

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_url = $article['image_url']; // Giữ ảnh cũ
    
    // Xử lý upload ảnh mới (nếu có)
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
            $upload_path = dirname(__DIR__) . '/uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_url = '/web21-10/uploads/' . $filename;
                
                // Xóa ảnh cũ nếu có
                if (!empty($article['image_url']) && file_exists(dirname(__DIR__) . $article['image_url'])) {
                    @unlink(dirname(__DIR__) . $article['image_url']);
                }
            } else {
                $message = 'Lỗi khi upload ảnh!';
                $message_type = 'error';
            }
        }
    }
    
    // Nếu không có lỗi upload ảnh, tiếp tục cập nhật
    if ($message_type !== 'error') {
        $category_id = (int)($_POST['category_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        // Validate
        if ($category_id <= 0) {
            $message = 'Vui lòng chọn chuyên mục!';
            $message_type = 'error';
        } elseif ($title === '') {
            $message = 'Vui lòng nhập tiêu đề!';
            $message_type = 'error';
        } else {
            // Tạo slug nếu để trống
            if ($slug === '') {
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            }
            
            // Cập nhật bài viết
            $stmt = $conn->prepare("UPDATE articles SET category_id=?, title=?, slug=?, summary=?, content=?, image_url=?, is_featured=? WHERE article_id=?");
            $stmt->bind_param('isssssii', $category_id, $title, $slug, $summary, $content, $image_url, $is_featured, $article_id);
            
            if ($stmt->execute()) {
                $message = '✅ Cập nhật bài viết thành công!<br>Chuyển về danh sách sau 2 giây...';
                $message_type = 'success';
                header('refresh:2;url=danhsachbaidang.php');
                
                // Cập nhật lại biến $article để hiển thị data mới
                $article['category_id'] = $category_id;
                $article['title'] = $title;
                $article['slug'] = $slug;
                $article['summary'] = $summary;
                $article['content'] = $content;
                $article['image_url'] = $image_url;
                $article['is_featured'] = $is_featured;
            } else {
                $message = 'Lỗi khi cập nhật: ' . $stmt->error;
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
    <title>Chỉnh Sửa Bài Viết</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Chỉnh Sửa Bài Viết</h1>
    <p><i>Cập nhật thông tin bài viết #<?php echo $article_id; ?></i></p>
    
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
                        <?php echo ($article['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <!-- Tiêu đề -->
        <label>Tiêu đề <span style="color:red;">*</span></label>
        <input type="text" name="title" placeholder="Nhập tiêu đề bài viết..." 
               value="<?php echo htmlspecialchars($article['title']); ?>" required>
        
        <!-- Slug -->
        <label>Slug (URL thân thiện)</label>
        <input type="text" name="slug" placeholder="vi-du: bai-viet-mau-so-1"
               value="<?php echo htmlspecialchars($article['slug']); ?>">
        <p style="font-size:12px; color:#999;">Để trống để tự động tạo từ tiêu đề</p>
        
        <!-- Tóm tắt -->
        <label>Tóm tắt</label>
        <textarea name="summary" rows="3" 
                  placeholder="Nhập tóm tắt ngắn gọn về bài viết..."><?php echo htmlspecialchars($article['summary']); ?></textarea>
        
        <!-- Nội dung -->
        <label>Nội dung chi tiết</label>
        <textarea name="content" rows="8"
                  placeholder="Nhập nội dung đầy đủ của bài viết..."><?php echo htmlspecialchars($article['content']); ?></textarea>
        
        <!-- Upload ảnh -->
        <label>Ảnh đại diện</label>
        <?php if (!empty($article['image_url'])): ?>
            <div style="margin-bottom: 10px;">
                <img src="../<?php echo htmlspecialchars($article['image_url']); ?>" 
                     alt="Current image" 
                     style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                <p style="font-size:12px; color:#666;">Ảnh hiện tại</p>
            </div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/jpg">
        <p style="font-size:12px; color:#999;">Chọn ảnh mới để thay thế (JPG, PNG, GIF - tối đa 5MB)</p>
        
        <!-- Tin nổi bật -->
        <label>
            <input type="checkbox" id="is_featured" name="is_featured" value="1"
                   <?php echo ($article['is_featured']) ? 'checked' : ''; ?>>
            Đánh dấu là tin nổi bật
        </label>
        
        <!-- Nút submit -->
        <button type="submit">Cập Nhật Bài Viết</button>
        <a href="danhsachbaidang.php" style="display:inline-block; padding:10px 20px; background:#6c757d; color:#fff; text-decoration:none; border-radius:4px; margin-left:10px;">Quay lại</a>
    </form>
    
    <hr>
    <a href="danhsachbaidang.php">← Quay lại danh sách bài viết</a>
</div>
</body>
</html>
