<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Thể Thao - Trang Chủ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HEADER - Phần đầu trang -->
    <header>
        <div class="container">
            <h1>⚽ Web Thể Thao</h1>
            <p>Cập nhật tin tức thể thao mới nhất</p>
        </div>
    </header>

    <!-- NAVIGATION - Menu điều hướng -->
    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="loginuser.php">Đăng nhập</a></li>
                <li><a href="register.php">Đăng ký</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT - Nội dung chính -->
    <main>
        <div class="container">
            <!-- Bài viết nổi bật -->
            <div class="featured">
                <h2>🔥 Tin nổi bật</h2>
                <h3>Mbappe chính thức gia nhập Real Madrid</h3>
                <p>Sau nhiều năm chờ đợi, cuối cùng siêu sao người Pháp đã trở thành người của Real Madrid với hợp đồng 5 năm.</p>
                <a href="article.php">Đọc tiếp →</a>
            </div>

            <!-- Layout 2 cột: Sidebar + Danh sách bài viết -->
            <div class="two-column">
                <!-- SIDEBAR - Thanh bên trái -->
                <aside class="sidebar">
                    <h3>Chuyên mục</h3>
                    <ul>
                        <li><a href="category.php">📰 Tất cả tin tức</a></li>
                        <li><a href="category.php">⚽ Bóng đá</a></li>
                        <li><a href="category.php">🎾 Tennis</a></li>
                        <li><a href="category.php">🏀 Bóng rổ</a></li>
                        <li><a href="category.php">🏊 Bơi lội</a></li>
                        <li><a href="category.php">💰 Kinh tế</a></li>
                        <li><a href="category.php">🌍 Thế giới</a></li>
                    </ul>

                    <h3 style="margin-top: 30px;">Tin mới nhất</h3>
                    <ul>
                        <li><a href="#">Mbappe gia nhập Real Madrid</a></li>
                        <li><a href="#">Liverpool thắng Man City 3-1</a></li>
                        <li><a href="#">Federer giải nghệ ở tuổi 41</a></li>
                        <li><a href="#">Lịch thi đấu V-League 2025</a></li>
                        <li><a href="#">Bitcoin tăng mạnh</a></li>
                    </ul>
                </aside>

                <!-- CONTENT - Danh sách bài viết -->
                <div class="content">
                    <!-- Bài viết 1 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/3498db/ffffff?text=Bong+Da" alt="Bóng đá">
                        <div class="article-content">
                            <h3>Kết quả V-League: HAGL chia điểm với Hà Nội FC</h3>
                            <div class="meta">
                                <span>📅 11/10/2025</span> | 
                                <span>📁 Bóng đá</span> | 
                                <span>👁️ 1,234 lượt xem</span>
                            </div>
                            <p>Trận cầu tâm điểm vòng 15 V-League đã diễn ra vô cùng hấp dẫn với màn rượt đuổi tỷ số ngoạn mục giữa hai đội bóng hàng đầu...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>

                    <!-- Bài viết 2 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/e74c3c/ffffff?text=Tennis" alt="Tennis">
                        <div class="article-content">
                            <h3>Alcaraz vô địch Wimbledon sau trận chung kết nghẹt thở <span class="badge">HOT</span></h3>
                            <div class="meta">
                                <span>📅 11/10/2025</span> | 
                                <span>📁 Tennis</span> | 
                                <span>👁️ 856 lượt xem</span>
                            </div>
                            <p>Tay vợt trẻ người Tây Ban Nha đã xuất sắc đánh bại đối thủ kỳ cựu để lần đầu tiên lên ngôi tại Wimbledon với tỷ số 3-2...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>

                    <!-- Bài viết 3 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/2ecc71/ffffff?text=Kinh+Te" alt="Kinh tế">
                        <div class="article-content">
                            <h3>Bitcoin biến động mạnh, nhà đầu tư nên làm gì?</h3>
                            <div class="meta">
                                <span>📅 10/10/2025</span> | 
                                <span>📁 Kinh tế</span> | 
                                <span>👁️ 2,145 lượt xem</span>
                            </div>
                            <p>Thị trường tiền điện tử đang trải qua một giai đoạn đầy biến động. Các chuyên gia khuyên nhà đầu tư nên hết sức cẩn trọng...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>

                    <!-- Bài viết 4 -->
                    <div class="article-card">
                        <img src="https://via.placeholder.com/400x200/9b59b6/ffffff?text=The+Gioi" alt="Thế giới">
                        <div class="article-content">
                            <h3>NASA công bố kế hoạch đưa người trở lại Mặt Trăng</h3>
                            <div class="meta">
                                <span>📅 09/10/2025</span> | 
                                <span>📁 Thế giới</span> | 
                                <span>👁️ 1,567 lượt xem</span>
                            </div>
                            <p>Chương trình Artemis hứa hẹn sẽ mở ra một kỷ nguyên mới cho việc khám phá không gian của nhân loại vào năm 2028...</p>
                            <a href="#" class="read-more">Đọc tiếp →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER - Chân trang -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Web Thể Thao. Tất cả quyền được bảo lưu.</p>
            <p>Liên hệ: info@webthethao.com | Hotline: 1900-xxxx</p>
        </div>
    </footer>
</body>
</html>
