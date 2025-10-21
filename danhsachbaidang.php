<?php
require_once 'dbadmin.php';
$db = new dbadmin();
$conn = $db->getConnection();

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
