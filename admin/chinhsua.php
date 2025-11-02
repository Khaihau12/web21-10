<?php
require_once 'dbadmin.php';
$db = new dbadmin();
$conn = $db->getConnection();

// Lấy article_id từ URL
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($article_id <= 0) {
    die('ID bài báo không hợp lệ');
}

// Thông báo
$message = '';

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Validate
    if ($category_id <= 0 || $title === '' || $content === '') {
        $message = 'Vui lòng nhập đầy đủ thông tin bắt buộc.';
    } else {
        // Tạo slug nếu để trống
        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
        }
        // Chuẩn bị truy vấn
        $stmt = $conn->prepare("UPDATE articles SET category_id=?, title=?, slug=?, summary=?, content=?, image_url=?, is_featured=? WHERE article_id=?");
        $stmt->bind_param('issssssi', $category_id, $title, $slug, $summary, $content, $image_url, $is_featured, $article_id);
        if ($stmt->execute()) {
            $message = 'Cập nhật bài báo thành công!';
            header('refresh:2;url=danhsachbaidang.php');
        } else {
            $message = 'Lỗi khi cập nhật: ' . $stmt->error;
        }
    }
}

// Lấy thông tin bài báo
$stmt = $conn->prepare('SELECT * FROM articles WHERE article_id=?');
$stmt->bind_param('i', $article_id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
if (!$article) {
    die('Không tìm thấy bài báo');
}

// Lấy danh sách chuyên mục
$categories = $conn->query('SELECT category_id, name FROM categories ORDER BY name ASC')->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa bài báo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Sửa bài báo</h1>
    <?php if ($message): ?>
        <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-danger'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="category_id" class="form-label">Chuyên mục</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">-- Chọn chuyên mục --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['category_id']; ?>" <?php echo ($article['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($article['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug (URL)</label>
            <input type="text" name="slug" id="slug" class="form-control" value="<?php echo htmlspecialchars($article['slug']); ?>">
        </div>
        <div class="mb-3">
            <label for="summary" class="form-label">Tóm tắt</label>
            <textarea name="summary" id="summary" class="form-control" rows="2"><?php echo htmlspecialchars($article['summary']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea name="content" id="content" class="form-control" rows="6" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image_url" class="form-label">Ảnh minh họa (URL)</label>
            <input type="text" name="image_url" id="image_url" class="form-control" value="<?php echo htmlspecialchars($article['image_url']); ?>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" <?php echo ($article['is_featured'] ? 'checked' : ''); ?>>
            <label for="is_featured" class="form-check-label">Bài nổi bật</label>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="danhsachbaidang.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
