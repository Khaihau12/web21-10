<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

$conn = $db->getConnection();

// Xử lý xóa
if (isset($_GET['page']) && $_GET['page'] == 'delete-article' && isset($_GET['slug'])) {
    $db->xoaBaiVietTheoSlug($_GET['slug']);
    ?>
    <script>
    window.location.href = 'index.php?page=articles&msg=deleted';
    </script>
    <?php
    exit;
}

// Lấy danh sách bài báo với slug
$result = $conn->query('SELECT a.article_id, a.title, a.slug, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.category_id ORDER BY a.article_id DESC');
?>

<div class="content-header">
    <h2>📰 Danh Sách Bài Viết</h2>
</div>

<div class="content-body">
    <h1>Danh Sách Bài Đăng</h1>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted") { ?>
        <p class="success">✅ Xóa bài viết thành công!</p>
    <?php } ?>
    
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
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['article_id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td>
                    <a href="?page=edit-article&slug=<?php echo $row['slug']; ?>" class="btn btn-success">Sửa</a>
                    <a href="?page=delete-article&slug=<?php echo $row['slug']; ?>" 
                       onclick="return confirm('Bạn có chắc muốn xóa bài viết này?');"
                       class="btn btn-danger">Xóa</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
