<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang cá nhân - Web Thể Thao</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS riêng cho trang user */
        .user-profile {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
            margin-bottom: 20px;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #3498db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            margin-right: 20px;
        }
        
        .user-details h2 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .user-details p {
            color: #7f8c8d;
            margin: 3px 0;
        }
        
        .user-tabs {
            display: flex;
            gap: 10px;
            border-bottom: 2px solid #ecf0f1;
            margin-bottom: 20px;
        }
        
        .tab-button {
            padding: 10px 20px;
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            font-size: 16px;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab-button.active {
            color: #3498db;
            border-bottom-color: #3498db;
            font-weight: bold;
        }
        
        .tab-button:hover {
            color: #3498db;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .article-list-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .article-list-item:hover {
            background-color: #ecf0f1;
        }
        
        .article-list-item img {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .article-list-info h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .article-list-info p {
            color: #7f8c8d;
            font-size: 13px;
            margin: 3px 0;
        }
        
        .article-list-info a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }
        
        .article-list-info a:hover {
            text-decoration: underline;
        }
        
        .change-password-form {
            max-width: 500px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn-submit {
            padding: 12px 30px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #2980b9;
        }
        
        .btn-logout {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-logout:hover {
            background-color: #c0392b;
        }
        
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
        
        .empty-message i {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 15px;
        }
    </style>
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
                <li><a href="indexuser.php">Trang cá nhân</a></li>
                <li><a href="#" class="btn-logout" style="background-color: #e74c3c; padding: 8px 15px;">Đăng xuất</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        <div class="container">
            <div class="two-column">
                <!-- SIDEBAR -->
                <aside class="sidebar">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="index.php">🏠 Trang chủ</a></li>
                        <li><a href="indexuser.php" style="color: #3498db; font-weight: bold;">👤 Trang cá nhân</a></li>
                    </ul>
                    
                    <h3 style="margin-top: 30px;">Thống kê</h3>
                    <ul>
                        <li>📖 Bài đã đọc: <strong>12</strong></li>
                        <li>❤️ Bài yêu thích: <strong>5</strong></li>
                        <li>📅 Ngày tham gia: <strong>15/09/2025</strong></li>
                    </ul>
                </aside>

                <!-- USER PROFILE CONTENT -->
                <div class="content">
                    <div class="user-profile">
                        <!-- Thông tin user -->
                        <div class="user-info">
                            <div class="user-avatar">H</div>
                            <div class="user-details">
                                <h2>Nguyễn Văn Hậu</h2>
                                <p>📧 Email: nguyenvanhau@gmail.com</p>
                                <p>👤 Tên đăng nhập: hau123</p>
                                <p>📅 Ngày tham gia: 15/09/2025</p>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="user-tabs">
                            <button class="tab-button active" onclick="showTab('recent')">📖 Bài đã đọc gần đây</button>
                            <button class="tab-button" onclick="showTab('favorite')">❤️ Bài yêu thích</button>
                            <button class="tab-button" onclick="showTab('password')">🔒 Đổi mật khẩu</button>
                        </div>

                        <!-- Tab: Bài đã đọc gần đây -->
                        <div id="recent" class="tab-content active">
                            <h3 style="margin-bottom: 20px;">Bài viết đã đọc gần đây</h3>
                            
                            <!-- Bài viết 1 -->
                            <div class="article-list-item">
                                <img src="https://via.placeholder.com/120x80/3498db/ffffff?text=Bong+Da" alt="Bài viết">
                                <div class="article-list-info">
                                    <h4>Mbappe chính thức gia nhập Real Madrid</h4>
                                    <p>📅 Đọc lúc: 01/11/2025 14:30</p>
                                    <p>📁 Chuyên mục: Bóng đá</p>
                                    <a href="article.php">Đọc lại →</a>
                                </div>
                            </div>

                            <!-- Bài viết 2 -->
                            <div class="article-list-item">
                                <img src="https://via.placeholder.com/120x80/e74c3c/ffffff?text=Tennis" alt="Bài viết">
                                <div class="article-list-info">
                                    <h4>Alcaraz vô địch Wimbledon</h4>
                                    <p>📅 Đọc lúc: 31/10/2025 10:15</p>
                                    <p>📁 Chuyên mục: Tennis</p>
                                    <a href="#">Đọc lại →</a>
                                </div>
                            </div>

                            <!-- Bài viết 3 -->
                            <div class="article-list-item">
                                <img src="https://via.placeholder.com/120x80/2ecc71/ffffff?text=V-League" alt="Bài viết">
                                <div class="article-list-info">
                                    <h4>HAGL chia điểm với Hà Nội FC</h4>
                                    <p>📅 Đọc lúc: 30/10/2025 20:45</p>
                                    <p>📁 Chuyên mục: Bóng đá</p>
                                    <a href="#">Đọc lại →</a>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Bài yêu thích -->
                        <div id="favorite" class="tab-content">
                            <h3 style="margin-bottom: 20px;">Bài viết yêu thích</h3>
                            
                            <!-- Bài viết 1 -->
                            <div class="article-list-item">
                                <img src="https://via.placeholder.com/120x80/3498db/ffffff?text=Bong+Da" alt="Bài viết">
                                <div class="article-list-info">
                                    <h4>Mbappe chính thức gia nhập Real Madrid</h4>
                                    <p>📅 Lưu lúc: 01/11/2025 14:35</p>
                                    <p>📁 Chuyên mục: Bóng đá</p>
                                    <a href="article.php">Đọc →</a> | <a href="#" style="color: #e74c3c;">Bỏ thích</a>
                                </div>
                            </div>

                            <!-- Bài viết 2 -->
                            <div class="article-list-item">
                                <img src="https://via.placeholder.com/120x80/9b59b6/ffffff?text=Kinh+Te" alt="Bài viết">
                                <div class="article-list-info">
                                    <h4>Bitcoin biến động mạnh</h4>
                                    <p>📅 Lưu lúc: 28/10/2025 09:20</p>
                                    <p>📁 Chuyên mục: Kinh tế</p>
                                    <a href="#">Đọc →</a> | <a href="#" style="color: #e74c3c;">Bỏ thích</a>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Đổi mật khẩu -->
                        <div id="password" class="tab-content">
                            <h3 style="margin-bottom: 20px;">Đổi mật khẩu</h3>
                            
                            <form method="POST" action="" class="change-password-form">
                                <div class="form-group">
                                    <label for="current_password">Mật khẩu hiện tại *</label>
                                    <input type="password" id="current_password" name="current_password" placeholder="Nhập mật khẩu hiện tại" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="new_password">Mật khẩu mới *</label>
                                    <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Nhập lại mật khẩu mới *</label>
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                                </div>
                                
                                <button type="submit" class="btn-submit">Đổi mật khẩu</button>
                            </form>
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

    <!-- JavaScript để chuyển tab -->
    <script>
        function showTab(tabName) {
            // Ẩn tất cả tab content
            var tabs = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            // Bỏ active tất cả tab button
            var buttons = document.getElementsByClassName('tab-button');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove('active');
            }
            
            // Hiển thị tab được chọn
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
