<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyên mục: Bóng đá</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <!-- TOP BAR -->
    <header class="top-bar">
        <div class="container">
            <div class="logo">
                <a href="index.php" aria-label="Trang chủ">
                    24H 📰 <span class="logo-subtext">THỂ THAO - BÓNG ĐÁ</span>
                </a>
            </div>
            <nav class="top-menu">
                <ul>
                    <li>
                        <form action="index.php" method="get">
                            <input type="text" name="q" placeholder="Nhập tin cần tìm">
                            <button type="submit" style="border:none; background:transparent; padding:0; margin-left:6px;">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </li>
                    <li><a href="loginuser.php">Đăng nhập</a></li>
                    <li><a href="register.php">Đăng ký</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- MAIN NAVIGATION -->
    <nav class="main-nav">
        <div class="container">
            <ul>
                <li><a href="index.php"><i class="fa fa-home"></i> TRANG CHỦ</a></li>
                <li><a href="category.php?slug=bong-da" class="active">BÓNG ĐÁ</a></li>
                <li><a href="category.php?slug=tennis">TENNIS</a></li>
                <li><a href="category.php?slug=bong-ro">BÓNG RỔ</a></li>
                <li><a href="category.php?slug=kinh-te">KINH TẾ</a></li>
                <li><a href="category.php?slug=the-gioi">THẾ GIỚI</a></li>
            </ul>
        </div>
    </nav>


    <!-- MAIN CONTENT -->
    <div class="container" style="padding: 20px 0;">
        <h1 class="page-title" style="font-size: 24px; border-bottom: 3px solid #78B43D; padding-bottom: 10px; margin-bottom: 25px; text-transform: uppercase;">
            📁 BÓNG ĐÁ
        </h1>

        <div class="category-article-list">
            <!-- Bài viết 1 -->
            <article class="list-news-item d-flex" style="margin-bottom: 20px; border-bottom: 1px dotted #ccc; padding-bottom: 15px;">
                <a href="article.php" class="list-news-img" style="flex: 0 0 220px; margin-right: 20px;">
                    <img src="https://via.placeholder.com/220x140/3498db/ffffff?text=Bong+Da" alt="Bài viết" class="img-fluid" style="aspect-ratio: 16/9; object-fit: cover; border-radius: 4px;">
                </a>
                <div class="list-news-info">
                    <h3 class="list-news-title">
                        <a href="article.php" class="fw-bold color-main hover-color-24h" style="font-size: 20px; line-height: 1.3;">
                            Mbappe chính thức gia nhập Real Madrid với hợp đồng 5 năm
                        </a>
                    </h3>
                    <p style="font-size: 15px; color: #555; margin-top: 8px;">
                        Sau nhiều năm chờ đợi, cuối cùng siêu sao người Pháp đã trở thành người của Real Madrid...
                    </p>
                </div>
            </article>

            <!-- Bài viết 2 -->
            <article class="list-news-item d-flex" style="margin-bottom: 20px; border-bottom: 1px dotted #ccc; padding-bottom: 15px;">
                <a href="#" class="list-news-img" style="flex: 0 0 220px; margin-right: 20px;">
                    <img src="https://via.placeholder.com/220x140/e74c3c/ffffff?text=V-League" alt="Bài viết" class="img-fluid" style="aspect-ratio: 16/9; object-fit: cover; border-radius: 4px;">
                </a>
                <div class="list-news-info">
                    <h3 class="list-news-title">
                        <a href="#" class="fw-bold color-main hover-color-24h" style="font-size: 20px; line-height: 1.3;">
                            Kết quả V-League: HAGL chia điểm kịch tính với Hà Nội FC
                        </a>
                    </h3>
                    <p style="font-size: 15px; color: #555; margin-top: 8px;">
                        Trận cầu tâm điểm vòng 15 V-League đã diễn ra vô cùng hấp dẫn với màn rượt đuổi tỷ số ngoạn mục...
                    </p>
                </div>
            </article>

            <!-- Bài viết 3 -->
            <article class="list-news-item d-flex" style="margin-bottom: 20px; border-bottom: 1px dotted #ccc; padding-bottom: 15px;">
                <a href="#" class="list-news-img" style="flex: 0 0 220px; margin-right: 20px;">
                    <img src="https://via.placeholder.com/220x140/2ecc71/ffffff?text=Tactics" alt="Bài viết" class="img-fluid" style="aspect-ratio: 16/9; object-fit: cover; border-radius: 4px;">
                </a>
                <div class="list-news-info">
                    <h3 class="list-news-title">
                        <a href="#" class="fw-bold color-main hover-color-24h" style="font-size: 20px; line-height: 1.3;">
                            Phân tích chiến thuật: Liverpool đã vô hiệu hóa Man City như thế nào?
                        </a>
                    </h3>
                    <p style="font-size: 15px; color: #555; margin-top: 8px;">
                        HLV Jurgen Klopp đã một lần nữa cho thấy tài năng của mình với một thế trận phòng ngự phản công bậc thầy...
                    </p>
                </div>
            </article>

            <!-- Bài viết 4 -->
            <article class="list-news-item d-flex" style="margin-bottom: 20px; border-bottom: 1px dotted #ccc; padding-bottom: 15px;">
                <a href="#" class="list-news-img" style="flex: 0 0 220px; margin-right: 20px;">
                    <img src="https://via.placeholder.com/220x140/f39c12/ffffff?text=News" alt="Bài viết" class="img-fluid" style="aspect-ratio: 16/9; object-fit: cover; border-radius: 4px;">
                </a>
                <div class="list-news-info">
                    <h3 class="list-news-title">
                        <a href="#" class="fw-bold color-main hover-color-24h" style="font-size: 20px; line-height: 1.3;">
                            Ronaldo lập hat-trick ở tuổi 39
                        </a>
                    </h3>
                    <p style="font-size: 15px; color: #555; margin-top: 8px;">
                        Siêu sao người Bồ Đào Nha tiếp tục chứng minh rằng tuổi tác chỉ là con số...
                    </p>
                </div>
            </article>
        </div>
    </div>

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
