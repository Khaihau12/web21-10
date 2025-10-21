<?php
require_once 'dbadmin.php';
$db = new dbadmin();
$conn = $db->getConnection();

// Kiểm tra quyền: chỉ admin hoặc editor mới được truy cập
$user = $db->getCurrentUser();
if (!$user || !in_array($user['role'], ['admin', 'editor'])) {
    header('Location: login.php');
    exit();
}

// Xử lý xóa bài viết
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $article_id = (int)$_GET['id'];
    if ($db->xoaBaiViet($article_id)) {
        $message = "Xóa bài viết thành công!";
        $message_type = "success";
    } else {
        $message = "Lỗi khi xóa bài viết!";
        $message_type = "error";
    }
}

// Lấy danh sách bài báo
$result = $conn->query('SELECT a.article_id, a.title, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.category_id ORDER BY a.article_id DESC');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Bài Đăng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Danh Sách Bài Đăng</h1>
    
    <?php if (isset($message)): ?>
        <div class="<?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Chuyên mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['article_id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                <td>
                    <a href="chinhsua.php?id=<?php echo $row['article_id']; ?>">Sửa</a>
                    |
                    <a href="?action=delete&id=<?php echo $row['article_id']; ?>" 
                       onclick="return confirm('Bạn có chắc muốn xóa bài viết này?');"
                       style="color: red;">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    
    <hr>
    <a href="index.php">← Quay lại trang chủ</a>
</div>
</body>
</html>
