<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

$conn = $db->getConnection();

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

<div class="content-header">
    <h2>📰 Danh Sách Bài Viết</h2>
</div>

<div class="content-body">
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
                    <a href="chinhsua.php?id=<?php echo $row['article_id']; ?>" class="btn btn-success">Sửa</a>
                    <a href="?page=articles&action=delete&id=<?php echo $row['article_id']; ?>" 
                       onclick="return confirm('Bạn có chắc muốn xóa bài viết này?');"
                       class="btn btn-danger">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
