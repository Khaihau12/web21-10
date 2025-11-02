<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tin tức - Web Thể Thao</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="container">
            <h1>⚽ Web Thể Thao</h1>
            <p>Cập nhật tin tức thể thao mới nhất</p>
        </div>
    </header>

    <!-- NAVIGATION -->
    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="loginuser.php">Đăng nhập</a></li>
                <li><a href="register.php">Đăng ký</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        <div class="container">
            <!-- Tiêu đề chuyên mục -->
            <div class="featured">
                <h2>📁 Danh sách tất cả tin tức</h2>
                <p>24 bài viết</p>
            </div>

            <!-- Layout 2 cột -->
            <div class="two-column">
                <!-- SIDEBAR -->
                <aside class="sidebar">
                    <h3>Chuyên mục</h3>
                    <ul>
                        <li><a href="category.php" style="color: #3498db; font-weight: bold;">📰 Tất cả tin tức</a></li>
                        <li><a href="#">⚽ Bóng đá</a></li>
                        <li><a href="#">🎾 Tennis</a></li>
                        <li><a href="#">🏀 Bóng rổ</a></li>
                        <li><a href="#">💰 Kinh tế</a></li>
                        <li><a href="#">🌍 Thế giới</a></li>
                    </ul>

                    <h3 style="margin-top: 30px;">Tin mới nhất</h3>
                    <ul>
                        <li><a href="#">Mbappe gia nhập Real Madrid</a></li>
                        <li><a href="#">Liverpool thắng Man City 3-1</a></li>
                        <li><a href="#">Federer giải nghệ ở tuổi 41</a></li>
                        <li><a href="#">Lịch thi đấu V-League 2025</a></li>
                        <li><a href="#">Bitcoin tăng mạnh</a></li>
                    </ul>
                    
                    <a href="index.php" class="back-link" style="display: inline-block; margin-top: 20px;">← Về trang chủ</a>
                </aside>

                <!-- CONTENT - CHỈ HIỂN THỊ DANH SÁCH BÀI VIẾT -->
                <div class="content">
                    <!-- Bài viết 1 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/3498db/ffffff?text=Bong+Da" alt="Bóng đá">
                        <div class="article-content">
                            <h3>Mbappe chính thức gia nhập Real Madrid <span class="badge">HOT</span></h3>
                            <div class="meta">
                                <span>📅 11/10/2025</span> | 
                                <span>📁 Bóng đá</span>
                            </div>
                            <p>Sau nhiều năm chờ đợi, cuối cùng siêu sao người Pháp đã trở thành người của Real Madrid...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>

                    <!-- Bài viết 2 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/e74c3c/ffffff?text=Tennis" alt="Tennis">
                        <div class="article-content">
                            <h3>Alcaraz vô địch Wimbledon sau trận chung kết nghẹt thở</h3>
                            <div class="meta">
                                <span>📅 11/10/2025</span> | 
                                <span>📁 Tennis</span>
                            </div>
                            <p>Tay vợt trẻ người Tây Ban Nha đã xuất sắc đánh bại đối thủ kỳ cựu...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>

                    <!-- Bài viết 3 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/2ecc71/ffffff?text=V-League" alt="V-League">
                        <div class="article-content">
                            <h3>HAGL chia điểm với Hà Nội FC</h3>
                            <div class="meta">
                                <span>📅 10/10/2025</span> | 
                                <span>📁 Bóng đá</span>
                            </div>
                            <p>Trận cầu tâm điểm vòng 15 V-League đã diễn ra vô cùng hấp dẫn...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>

                    <!-- Bài viết 4 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/9b59b6/ffffff?text=Kinh+Te" alt="Kinh tế">
                        <div class="article-content">
                            <h3>Bitcoin biến động mạnh</h3>
                            <div class="meta">
                                <span>📅 09/10/2025</span> | 
                                <span>📁 Kinh tế</span>
                            </div>
                            <p>Thị trường tiền điện tử đang trải qua giai đoạn đầy biến động...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Web Thể Thao. Tất cả quyền được bảo lưu.</p>
            <p>Liên hệ: info@webthethao.com | Hotline: 1900-xxxx</p>
        </div>
    </footer>
</body>
</html>
