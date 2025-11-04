<?php
session_start();
require_once 'dbuser.php';
$db = new dbuser();

// Lấy dữ liệu từ database
$tinNoiBat = $db->layBaiVietNoiBat(1); // Lấy 1 bài nổi bật nhất
$tinMoiNhat = $db->layTatCaBaiViet(10); // Lấy 10 tin mới nhất
$tinTheThao = $db->layBaiVietTheoCategory('the-thao', 4); // Lấy 4 tin thể thao
$tinSidebar = $db->layTatCaBaiViet(5); // Lấy 5 tin cho sidebar
$danhMuc = $db->layTatCaChuyenMuc(); // Lấy tất cả danh mục

// Kiểm tra đăng nhập
$isLoggedIn = $db->isLoggedIn();
$currentUser = $isLoggedIn ? $db->getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức bóng đá, thể thao, giải trí | Đọc tin tức 24h mới nhất</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
                        <li><a href="index.php" class="active"><i class="fa fa-home"></i> Trang Chủ</a></li>
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
    <div class="container main-content-24h" style="padding-top: 20px;">
        <div class="row d-flex">
            <div class="col-8 main-column main-column-pad">
                
                <!-- SECTION: Tin nổi bật -->
                <section class="hightl-24h-block d-flex">
                    <!-- Bài nổi bật lớn -->
                    <?php if($tinNoiBat): ?>
                    <div class="hightl-24h-big hightl-24h-big--col">
                        <a href="article.php?id=<?php echo $tinNoiBat['article_id']; ?>">
                            <img src="<?php echo $tinNoiBat['image_url']; ?>" alt="<?php echo htmlspecialchars($tinNoiBat['title']); ?>" class="img-fluid hightl-img-big">
                        </a>
                        <h2 class="hightl-title-big">
                            <a href="article.php?id=<?php echo $tinNoiBat['article_id']; ?>" class="fw-bold color-main hover-color-24h">
                                <?php echo htmlspecialchars($tinNoiBat['title']); ?>
                            </a>
                        </h2>
                        <p class="hightl-summary"><?php echo htmlspecialchars(substr($tinNoiBat['summary'], 0, 150)); ?>...</p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Danh sách tin nổi bật nhỏ -->
                    <div class="hightl-24h-list" style="flex: 1; padding-left: 20px;">
                        <?php 
                        $tinNho = array_slice($tinMoiNhat, 1, 3); // Lấy 3 tin tiếp theo
                        foreach($tinNho as $tin): 
                        ?>
                        <article class="hightl-24h-items" style="margin-bottom: 15px;">
                            <span class="hightl-24h-items-cate d-block mar-b-5">
                                <a href="category.php?id=<?php echo $tin['category_id']; ?>" class="color-24h">
                                    <?php echo htmlspecialchars($tin['category_name']); ?>
                                </a>
                            </span>
                            <h3>
                                <a href="article.php?id=<?php echo $tin['article_id']; ?>" class="d-block fw-medium color-main hover-color-24h">
                                    <?php echo htmlspecialchars($tin['title']); ?>
                                </a>
                            </h3>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- SECTION: Category showcase - Thể thao -->
                <section class="category-showcase-block">
                    <header class="category-showcase-header">
                        <h2 class="category-title"><a href="category.php">THỂ THAO</a></h2>
                        <nav class="sub-category-nav">
                            <a href="category.php?slug=bong-da">Bóng đá</a>
                            <a href="category.php?slug=tennis">Tennis</a>
                            <a href="category.php?slug=bong-ro">Bóng rổ</a>
                        </nav>
                    </header>
                    <div class="category-showcase-content">
                        <!-- Bài chính -->
                        <?php if(isset($tinTheThao[0])): ?>
                        <article class="showcase-top-story">
                            <a href="article.php?id=<?php echo $tinTheThao[0]['article_id']; ?>" class="story-image">
                                <img src="<?php echo $tinTheThao[0]['image_url']; ?>" alt="<?php echo htmlspecialchars($tinTheThao[0]['title']); ?>">
                            </a>
                            <div class="story-content">
                                <h3><a href="article.php?id=<?php echo $tinTheThao[0]['article_id']; ?>"><?php echo htmlspecialchars($tinTheThao[0]['title']); ?></a></h3>
                                <p><?php echo htmlspecialchars(substr($tinTheThao[0]['summary'], 0, 150)); ?>...</p>
                            </div>
                        </article>
                        <?php endif; ?>
                        
                        <!-- Các bài nhỏ -->
                        <div class="showcase-bottom-stories">
                            <?php 
                            $tinNhoTheThao = array_slice($tinTheThao, 1, 3);
                            foreach($tinNhoTheThao as $tin): 
                            ?>
                            <article class="story-small">
                                <a href="article.php?id=<?php echo $tin['article_id']; ?>" class="story-image">
                                    <img src="<?php echo $tin['image_url']; ?>" alt="<?php echo htmlspecialchars($tin['title']); ?>">
                                </a>
                                <h4><a href="article.php?id=<?php echo $tin['article_id']; ?>"><?php echo htmlspecialchars($tin['title']); ?></a></h4>
                            </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <hr style="margin: 30px 0; border: 0; border-top: 5px solid #eee;">

                <!-- SECTION: Danh sách tin mới nhất -->
                <section class="cate-news-24h-r mar-t-40">
                    <div class="box-t d-flex align-items-center mar-b-15">
                        <h2 class="fw-bold text-uppercase color-green-custom">📰 TIN MỚI NHẤT</h2>
                    </div>
                    
                    <!-- Bài viết -->
                    <?php foreach($tinMoiNhat as $tin): ?>
                    <div class="article-card">
                        <img src="<?php echo $tin['image_url']; ?>" alt="<?php echo htmlspecialchars($tin['title']); ?>">
                        <div class="article-content">
                            <h3><?php echo htmlspecialchars($tin['title']); ?></h3>
                            <div class="meta">
                                <span>📅 <?php echo date('d/m/Y', strtotime($tin['created_at'])); ?></span> |
                                <span>📁 <?php echo htmlspecialchars($tin['category_name']); ?></span>
                            </div>
                            <p><?php echo htmlspecialchars(substr($tin['summary'], 0, 150)); ?>...</p>
                            <a href="article.php?id=<?php echo $tin['article_id']; ?>" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </section>

            </div>

            <!-- SIDEBAR - Cột bên phải -->
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
