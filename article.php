<?php
session_start();
require_once 'dbuser.php';
$db = new dbuser();

// Lấy article_id từ URL
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy chi tiết bài viết
$article = $db->layChiTietBaiViet($article_id);

// Nếu không tìm thấy bài viết, chuyển về trang chủ
if (!$article) {
    header('Location: index.php');
    exit;
}

// Kiểm tra đăng nhập
$isLoggedIn = $db->kiemTraDangNhap();
$currentUser = $isLoggedIn ? $db->layUserHienTai() : null;

// Xử lý thích bài viết
if ($isLoggedIn && isset($_POST['like_toggle'])) {
    $db->toggleThichBaiViet($currentUser['user_id'], $article_id);
    header("Location: article.php?id=$article_id");
    exit;
}

// Xử lý lưu bài viết
if ($isLoggedIn && isset($_POST['save_toggle'])) {
    $db->toggleLuuBaiViet($currentUser['user_id'], $article_id);
    header("Location: article.php?id=$article_id");
    exit;
}

// Xử lý thêm bình luận
if ($isLoggedIn && isset($_POST['add_comment'])) {
    $content = trim($_POST['comment_content']);
    if (!empty($content)) {
        $db->themBinhLuan($article_id, $currentUser['user_id'], $content);
        header("Location: article.php?id=$article_id#comments");
        exit;
    }
}

// Xử lý xóa bình luận
if ($isLoggedIn && isset($_POST['delete_comment'])) {
    $comment_id = (int)$_POST['comment_id'];
    $db->xoaBinhLuan($comment_id, $currentUser['user_id']);
    header("Location: article.php?id=$article_id#comments");
    exit;
}

// Xử lý sửa bình luận
if ($isLoggedIn && isset($_POST['edit_comment'])) {
    $comment_id = (int)$_POST['comment_id'];
    $content = trim($_POST['comment_content']);
    if (!empty($content)) {
        $db->suaBinhLuan($comment_id, $currentUser['user_id'], $content);
        header("Location: article.php?id=$article_id#comments");
        exit;
    }
}

// Lấy comment_id nếu đang edit
$editingCommentId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editingComment = null;
if ($editingCommentId && $isLoggedIn) {
    $editingComment = $db->layMotBinhLuan($editingCommentId);
    // Kiểm tra có phải comment của user không
    if ($editingComment && $editingComment['user_id'] != $currentUser['user_id']) {
        $editingComment = null;
    }
}

// Đếm lượt thích và lưu từ database
$luotThich = $db->demLuotThich($article_id);
$luotLuu = $db->demLuotLuu($article_id);

// Đếm view từ session - tạm thời hiển thị 0 (có thể cập nhật sau)
// Mỗi session đọc 1 lần, lưu trong session để đếm
if (!isset($_SESSION['article_views'])) {
    $_SESSION['article_views'] = [];
}
if (!in_array($article_id, $_SESSION['article_views'])) {
    $_SESSION['article_views'][] = $article_id;
}
$luotXem = count($_SESSION['article_views']); // Tạm thời hiển thị tổng số bài đã xem trong session

// Kiểm tra user đã thích/lưu chưa
$daThich = $isLoggedIn ? $db->daThichBaiViet($currentUser['user_id'], $article_id) : false;
$daLuu = $isLoggedIn ? $db->daLuuBaiViet($currentUser['user_id'], $article_id) : false;

// Lấy bình luận
$binhLuan = $db->layBinhLuan($article_id);
$soBinhLuan = $db->demBinhLuan($article_id);

// Lấy danh mục cho menu
$danhMuc = $db->layTatCaChuyenMuc();

// Lấy tin sidebar
$tinSidebar = $db->layTatCaBaiViet(5);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Tin Tức 24H</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* CSS riêng cho trang chi tiết bài viết */
        .article-detail {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .article-detail h1 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        
        .article-detail .meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .article-detail .featured-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .article-detail .summary {
            font-size: 18px;
            font-weight: bold;
            color: #555;
            line-height: 1.6;
            margin: 20px 0;
            padding: 20px;
            background-color: #ecf0f1;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        
        .article-detail .content {
            font-size: 16px;
            line-height: 1.8;
            color: #333;
        }
        
        .article-detail .content p {
            margin-bottom: 15px;
        }
        
        .article-detail .content h3 {
            color: #2c3e50;
            font-size: 22px;
            margin: 25px 0 15px 0;
        }
        
        .article-detail .content img {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .breadcrumb {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .breadcrumb a {
            color: #3498db;
        }
        
        .breadcrumb a:hover {
            color: #d90000;
        }
    </style>
</head>
<body>
    <!-- HEADER - Thanh header chính -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <!-- Top Row: Logo + Search + User -->
                <div class="header-top">
                    <!-- Logo -->
                    <div class="site-logo">
                        <a href="index.php">
                            <h1>📰 24H</h1>
                            <span>Tin Tức Thể Thao</span>
                        </a>
                    </div>
                    
                    <!-- Search & User -->
                    <div class="header-actions">
                        <form action="index.php" method="get" class="search-form">
                            <input type="text" name="q" placeholder="Tìm kiếm...">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                        <div class="user-links">
                            <?php if($isLoggedIn): ?>
                                <a href="indexuser.php"><i class="fa fa-user"></i> <?php echo htmlspecialchars($currentUser['display_name'] ?: $currentUser['username']); ?></a>
                            <?php else: ?>
                                <a href="loginuser.php"><i class="fa fa-user"></i> Đăng nhập</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Menu - Dòng dưới -->
                <nav class="main-navigation">
                    <ul>
                        <li><a href="index.php"><i class="fa fa-home"></i> Trang Chủ</a></li>
                        <?php foreach($danhMuc as $dm): ?>
                        <li><a href="category.php?id=<?php echo $dm['category_id']; ?>"><?php echo htmlspecialchars($dm['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main>
    <div class="container content-area d-flex" style="padding-top: 20px;">
        <div class="main-column col-8 main-column-pad">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a> &raquo; 
                <a href="category.php?id=<?php echo $article['category_id']; ?>"><?php echo htmlspecialchars($article['category_name']); ?></a>
            </div>
            
            <!-- Bài viết chi tiết -->
            <article class="full-article">
                <div class="article-header">
                    <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                    <div class="article-meta-info d-flex justify-content-between align-items-center">
                        <span class="meta-left">
                            Chuyên mục: <a href="category.php?id=<?php echo $article['category_id']; ?>" style="color:#3498db;"><?php echo htmlspecialchars($article['category_name']); ?></a>
                            &nbsp;•&nbsp;
                            <span class="date-time"><?php echo date('d/m/Y H:i', strtotime($article['created_at'])); ?></span>
                            &nbsp;•&nbsp;
                            <span class="view-count">👁️ <?php echo number_format($luotXem); ?> lượt xem</span>
                        </span>
                    </div>
                </div>
            
                <div class="article-content">
                    <?php if(!empty($article['summary'])): ?>
                    <p style="font-weight: bold; color: #000; font-size: 19px; line-height: 1.5; margin-bottom: 20px;">
                        <?php echo htmlspecialchars($article['summary']); ?>
                    </p>
                    <?php endif; ?>
                    
                    <div class="article-body">
                        <?php echo $article['content']; ?>
                    </div>
                </div>
            </article>
            
            <!-- Actions -->
            <section class="article-actions" style="margin-top:16px;border-top:1px solid #eee;padding-top:12px;">
                <?php if($isLoggedIn): ?>
                    <form method="post" style="display:inline">
                        <button type="submit" name="like_toggle" value="1" style="padding:6px 10px;border-radius:6px;border:1px solid <?php echo $daThich ? '#e74c3c' : '#ddd'; ?>;background:<?php echo $daThich ? '#ffe6e6' : '#fff'; ?>;cursor:pointer;color:<?php echo $daThich ? '#e74c3c' : '#333'; ?>">
                            <?php echo $daThich ? '❤️' : '🤍'; ?> Thích (<?php echo number_format($luotThich); ?>)
                        </button>
                    </form>
                    <form method="post" style="display:inline;margin-left:8px;">
                        <button type="submit" name="save_toggle" value="1" style="padding:6px 10px;border-radius:6px;border:1px solid <?php echo $daLuu ? '#3498db' : '#ddd'; ?>;background:<?php echo $daLuu ? '#e6f2ff' : '#fff'; ?>;cursor:pointer;color:<?php echo $daLuu ? '#3498db' : '#333'; ?>">
                            <?php echo $daLuu ? '📌' : '🔖'; ?> <?php echo $daLuu ? 'Đã lưu' : 'Lưu đọc sau'; ?> (<?php echo number_format($luotLuu); ?>)
                        </button>
                    </form>
                <?php else: ?>
                    <a href="loginuser.php" style="padding:6px 10px;border-radius:6px;border:1px solid #ddd;background:#fff;text-decoration:none;color:#333;display:inline-block">
                        🤍 Thích (<?php echo number_format($luotThich); ?>)
                    </a>
                    <a href="loginuser.php" style="padding:6px 10px;border-radius:6px;border:1px solid #ddd;background:#fff;text-decoration:none;color:#333;display:inline-block;margin-left:8px;">
                        🔖 Lưu đọc sau (<?php echo number_format($luotLuu); ?>)
                    </a>
                    <span style="color:#999;font-size:12px;margin-left:8px;">• <a href="loginuser.php">Đăng nhập</a> để thích và lưu bài viết</span>
                <?php endif; ?>
            </section>

            <!-- BÌNH LUẬN -->
            <section id="comments" class="comments-section" style="margin-top:30px;padding-top:20px;border-top:2px solid #eee;">
                <h3 style="font-size:20px;font-weight:bold;margin-bottom:15px;">
                    💬 Bình luận (<?php echo number_format($soBinhLuan); ?>)
                </h3>

                <!-- Form thêm/sửa bình luận -->
                <?php if($isLoggedIn): ?>
                <div class="comment-form" style="margin-bottom:25px;padding:15px;background:#f8f9fa;border-radius:8px;">
                    <?php if($editingComment): ?>
                    <!-- Form sửa bình luận -->
                    <form method="post" action="">
                        <div style="margin-bottom:10px;">
                            <strong><?php echo htmlspecialchars($currentUser['display_name'] ?: $currentUser['username']); ?></strong>
                            <span style="color:#f39c12;margin-left:10px;">✏️ Đang chỉnh sửa bình luận</span>
                        </div>
                        <input type="hidden" name="comment_id" value="<?php echo $editingComment['comment_id']; ?>">
                        <textarea name="comment_content" rows="3" placeholder="Viết bình luận..." 
                            style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;resize:vertical;" required><?php echo htmlspecialchars($editingComment['content']); ?></textarea>
                        <div style="margin-top:10px;">
                            <button type="submit" name="edit_comment" value="1" 
                                style="padding:8px 20px;background:#f39c12;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:500;">
                                💾 Lưu thay đổi
                            </button>
                            <a href="article.php?id=<?php echo $article_id; ?>#comments" 
                                style="margin-left:10px;padding:8px 20px;background:#95a5a6;color:#fff;border:none;border-radius:6px;text-decoration:none;display:inline-block;">
                                ❌ Hủy
                            </a>
                        </div>
                    </form>
                    <?php else: ?>
                    <!-- Form thêm bình luận mới -->
                    <form method="post" action="">
                        <div style="margin-bottom:10px;">
                            <strong><?php echo htmlspecialchars($currentUser['display_name'] ?: $currentUser['username']); ?></strong>
                        </div>
                        <textarea name="comment_content" rows="3" placeholder="Viết bình luận..." 
                            style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;resize:vertical;" required></textarea>
                        <button type="submit" name="add_comment" value="1" 
                            style="margin-top:10px;padding:8px 20px;background:#3498db;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:500;">
                            Gửi bình luận
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div style="margin-bottom:20px;padding:15px;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;">
                    <a href="loginuser.php" style="color:#856404;font-weight:500;">👤 Đăng nhập</a> để tham gia bình luận
                </div>
                <?php endif; ?>

                <!-- Danh sách bình luận -->
                <div class="comment-list">
                    <?php if(count($binhLuan) > 0): ?>
                        <?php foreach($binhLuan as $comment): ?>
                        <div class="comment-item" style="padding:15px;margin-bottom:15px;background:#fff;border:1px solid #e3e3e3;border-radius:8px;">
                            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
                                <div>
                                    <strong style="color:#2c3e50;font-size:14px;">
                                        <?php echo htmlspecialchars($comment['display_name'] ?: $comment['username']); ?>
                                    </strong>
                                    <span style="color:#999;font-size:12px;margin-left:8px;">
                                        <?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?>
                                    </span>
                                </div>
                                <?php if($isLoggedIn && $comment['user_id'] == $currentUser['user_id']): ?>
                                <div>
                                    <a href="article.php?id=<?php echo $article_id; ?>&edit=<?php echo $comment['comment_id']; ?>#comments" 
                                        style="padding:4px 8px;background:#f39c12;color:#fff;border:none;border-radius:4px;text-decoration:none;font-size:12px;margin-right:5px;">
                                        ✏️ Sửa
                                    </a>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa bình luận này?')">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                        <button type="submit" name="delete_comment" value="1" 
                                            style="padding:4px 8px;background:#e74c3c;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:12px;">
                                            🗑️ Xóa
                                        </button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div style="color:#333;font-size:14px;line-height:1.6;">
                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <p style="color:#999;text-align:center;padding:20px;">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- SIDEBAR -->
        <aside class="sidebar-column col-4">
            <div class="latest-news-block">
                <header class="latest-news-tit">
                    <h2 class="fw-bold text-uppercase color-green-custom">📌 TIN MỚI NHẤT</h2>
                </header>
                <div class="latest-news-list">
                    <?php foreach($tinSidebar as $tin): ?>
                    <div class="sidebar-article">
                        <h4 style="font-size: 11px; color: #888; text-transform: uppercase;">
                            <?php echo htmlspecialchars($tin['category_name']); ?>
                        </h4>
                        <p>
                            <a href="article.php?id=<?php echo $tin['article_id']; ?>" class="color-main hover-color-24h">
                                <?php echo htmlspecialchars($tin['title']); ?>
                            </a>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
    </div>
    </main>

    <!-- FOOTER -->
    <footer style="margin-top:40px;padding:20px 0;border-top:1px solid #eee;color:#666;font-size:13px">
        <div class="container" style="display:flex;justify-content:space-between;align-items:center">
            <div>© 2025 Web Thể Thao - Tất cả vì người đọc.</div>
            <div style="opacity:.6">
                <a href="admin/login.php" title="Đăng nhập quản trị" style="color:#666;text-decoration:none">Quản trị</a>
            </div>
        </div>
    </footer>
</body>
</html>
