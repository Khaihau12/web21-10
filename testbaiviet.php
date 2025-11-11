<?php
// 1. Bắt đầu session
session_start();
require_once 'dbuser.php';
$db = new dbuser();

// 2. Lấy slug từ URL
// Ví dụ: article.php?slug=ronaldo-ghi-ban-phut-90
if (isset($_GET['slug'])) {
    $article_slug = $_GET['slug'];
} else {
    $article_slug = '';
}

// 3. Lấy chi tiết bài viết theo slug
$article = $db->layChiTietBaiVietTheoSlug($article_slug);

// 4. Kiểm tra: Nếu không tìm thấy bài viết
if (!$article) {
    header('Location: index.php'); // Về trang chủ
    exit;
}

// 5. Lấy ID bài viết để dùng cho các chức năng khác
$article_id = $article['article_id'];

// 6. Hiển thị giao diện HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $article['title']; ?></title>
</head>
<body>
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="index.php">Trang chủ</a> &raquo; 
        <a href="category.php?slug=<?php echo $article['category_slug']; ?>">
            <?php echo $article['category_name']; ?>
        </a>
    </div>
    
    <!-- Bài viết chi tiết -->
    <article>
        <!-- Tiêu đề -->
        <h1><?php echo $article['title']; ?></h1>
        
        <!-- Thông tin -->
        <div class="meta">
            Chuyên mục: 
            <a href="category.php?slug=<?php echo $article['category_slug']; ?>">
                <?php echo $article['category_name']; ?>
            </a>
            • 
            Ngày đăng: <?php echo date('d/m/Y H:i', strtotime($article['created_at'])); ?>
        </div>
        
        <!-- Tóm tắt -->
        <div class="summary">
            <strong><?php echo $article['summary']; ?></strong>
        </div>
        
        <!-- Hình ảnh -->
        <div class="article-image">
            <img src="<?php echo $article['image_url']; ?>" 
                 alt="<?php echo $article['title']; ?>">
        </div>
        
        <!-- Nội dung đầy đủ -->
        <div class="article-content">
            <?php echo $article['content']; ?>
        </div>
    </article>
</body>
</html>