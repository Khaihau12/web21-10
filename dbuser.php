<?php
class dbuser {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "webthethao";
    protected $conn;
    
    // Constructor - Tự động kết nối
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8mb4");
    }
    
    // Getter for database connection
    public function getConnection() {
        return $this->conn;
    }
    
    // Lấy bài viết mới nhất
    public function layBaiVietMoiNhat($limit = 10) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                ORDER BY articles.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy bài viết nổi bật
    public function layBaiVietNoiBat($limit = 1) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE articles.is_featured = 1
                ORDER BY articles.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    
    // Lấy bài viết theo category
    public function layBaiVietTheoCategory($category_slug, $limit = 10) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE categories.slug = '$category_slug'
                ORDER BY articles.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy bài viết theo ID
    public function layBaiVietTheoId($id) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, 
                categories.name as category_name, categories.slug as category_slug 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE articles.article_id = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    
    // Lấy tất cả chuyên mục
    public function layTatCaChuyenMuc() {
        $sql = "SELECT category_id, name, slug, parent_id FROM categories ORDER BY name ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Tìm kiếm bài viết
    public function timKiemBaiViet($keyword, $limit = 20) {
        $keyword = '%' . $keyword . '%';
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE (articles.title LIKE '$keyword' OR articles.content LIKE '$keyword')
                ORDER BY articles.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy bài viết theo category_id
    public function layBaiVietTheoCategoryId($category_id, $limit = 20) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE articles.category_id = $category_id
                ORDER BY articles.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy thông tin category theo ID
    public function layCategoryTheoId($category_id) {
        $sql = "SELECT category_id, name, slug, parent_id FROM categories WHERE category_id = $category_id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // Lấy chi tiết bài viết theo ID
    public function layChiTietBaiViet($article_id) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, 
                categories.name as category_name, categories.slug as category_slug
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE articles.article_id = $article_id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // =======================================================================
    // TÍNH NĂNG: ĐĂNG KÝ USER
    // =======================================================================
    
    // Đăng ký tài khoản mới
    public function dangKy($username, $password, $email, $display_name = '') {
        // 1. Kiểm tra các trường bắt buộc không được để trống
        if (empty($username) || empty($password) || empty($email)) {
            return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!'];
        }
        
        // 2. Kiểm tra username đã tồn tại chưa
        $sql = "SELECT username FROM users WHERE username = '$username'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Tên đăng nhập đã tồn tại!'];
        }
        
        // 3. Kiểm tra email đã tồn tại chưa
        $sql = "SELECT email FROM users WHERE email = '$email'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Email đã được sử dụng!'];
        }
        
        // 4. Nếu không có display_name thì dùng username
        if (empty($display_name)) {
            $display_name = $username;
        }
        
        // 5. Mã hóa password bằng MD5 và lưu vào database
        $password_md5 = md5($password);
        
        $sql = "INSERT INTO users (username, password, email, display_name, role, created_at) 
                VALUES ('$username', '$password_md5', '$email', '$display_name', 'user', NOW())";
        
        if ($this->conn->query($sql)) {
            return ['success' => true, 'message' => 'Đăng ký thành công!'];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi tạo tài khoản!'];
        }
    }
    
    // =======================================================================
    // TÍNH NĂNG: ĐỔI MẬT KHẨU
    // =======================================================================
    
    // Đổi mật khẩu cho user
    public function doiMatKhau($user_id, $mat_khau_cu, $mat_khau_moi) {
        // 1. Lấy password hiện tại
        $sql = "SELECT password FROM users WHERE user_id = $user_id";
        $result = $this->conn->query($sql);
        $user = $result->fetch_assoc();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Không tìm thấy người dùng!'];
        }
        
        // 2. So sánh mật khẩu cũ (mã hóa MD5)
        $mat_khau_cu_md5 = md5($mat_khau_cu);
        if ($user['password'] !== $mat_khau_cu_md5) {
            return ['success' => false, 'message' => 'Mật khẩu hiện tại không đúng!'];
        }
        
        // 3. Cập nhật mật khẩu mới (mã hóa MD5)
        $mat_khau_moi_md5 = md5($mat_khau_moi);
        $sql = "UPDATE users SET password = '$mat_khau_moi_md5' WHERE user_id = $user_id";
        
        if ($this->conn->query($sql)) {
            return ['success' => true, 'message' => 'Đổi mật khẩu thành công!'];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi đổi mật khẩu!'];
        }
    }
    
    // =======================================================================
    // TÍNH NĂNG: ĐĂNG NHẬP USER
    // =======================================================================
    
    public function login($username, $password) {
        // Mã hóa password bằng MD5
        $password_md5 = md5($password);
        
        // Lấy tất cả tài khoản từ database
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        
        // Duyệt qua từng tài khoản
        while ($row = $result->fetch_assoc()) {
            // Nếu tìm thấy username và password khớp
            if ($row['username'] == $username && $row['password'] == $password_md5) {
                // Lưu thông tin vào session
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['display_name'] = $row['display_name'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['email'] = $row['email'];
                
                return true;
            }
        }
        
        return false;
    }
    
    // Đăng xuất người dùng
    public function logout() {
        unset($_SESSION['user_logged_in']);
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['display_name']);
        unset($_SESSION['role']);
        unset($_SESSION['email']);
        return true;
    }

    // Kiểm tra đã đăng nhập hay chưa
    public function isLoggedIn() {
        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
            return true;
        }
        return false;
    }
    
    // Lấy thông tin user hiện tại
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'display_name' => $_SESSION['display_name'],
                'role' => $_SESSION['role'],
                'email' => $_SESSION['email']
            ];
        }
        return null;
    }

    // Kiểm tra user đã thích bài viết chưa
    public function daThichBaiViet($user_id, $article_id) {
        $sql = "SELECT * FROM article_likes WHERE user_id = $user_id AND article_id = $article_id";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0;
    }

    // Kiểm tra user đã lưu bài viết chưa
    public function daLuuBaiViet($user_id, $article_id) {
        $sql = "SELECT * FROM article_saves WHERE user_id = $user_id AND article_id = $article_id";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0;
    }

    // Toggle thích bài viết
    public function toggleThichBaiViet($user_id, $article_id) {
        if ($this->daThichBaiViet($user_id, $article_id)) {
            // Đã thích -> Bỏ thích
            $sql = "DELETE FROM article_likes WHERE user_id = $user_id AND article_id = $article_id";
            $this->conn->query($sql);
            return false;
        } else {
            // Chưa thích -> Thêm thích
            $sql = "INSERT INTO article_likes (user_id, article_id) VALUES ($user_id, $article_id)";
            $this->conn->query($sql);
            return true;
        }
    }

    // Toggle lưu bài viết
    public function toggleLuuBaiViet($user_id, $article_id) {
        if ($this->daLuuBaiViet($user_id, $article_id)) {
            // Đã lưu -> Bỏ lưu
            $sql = "DELETE FROM article_saves WHERE user_id = $user_id AND article_id = $article_id";
            $this->conn->query($sql);
            return false;
        } else {
            // Chưa lưu -> Thêm lưu
            $sql = "INSERT INTO article_saves (user_id, article_id) VALUES ($user_id, $article_id)";
            $this->conn->query($sql);
            return true;
        }
    }

    // Đếm số lượt thích của bài viết
    public function demLuotThich($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_likes WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Đếm số lượt lưu của bài viết
    public function demLuotLuu($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_saves WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // ========== BÌNH LUẬN ==========
    
    // Thêm bình luận mới
    public function themBinhLuan($article_id, $user_id, $content) {
        $sql = "INSERT INTO comments (article_id, user_id, content, created_at) 
                VALUES ($article_id, $user_id, '$content', NOW())";
        return $this->conn->query($sql);
    }

    // Lấy danh sách bình luận của bài viết
    public function layBinhLuan($article_id) {
        $sql = "SELECT c.*, u.username, u.display_name 
                FROM comments c
                JOIN users u ON c.user_id = u.user_id
                WHERE c.article_id = $article_id
                ORDER BY c.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Đếm số bình luận của bài viết
    public function demBinhLuan($article_id) {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Xóa bình luận
    public function xoaBinhLuan($comment_id, $user_id) {
        $sql = "DELETE FROM comments WHERE comment_id = $comment_id AND user_id = $user_id";
        return $this->conn->query($sql);
    }

    // Sửa bình luận
    public function suaBinhLuan($comment_id, $user_id, $content) {
        $sql = "UPDATE comments SET content = '$content' WHERE comment_id = $comment_id AND user_id = $user_id";
        return $this->conn->query($sql);
    }

    // Lấy thông tin 1 bình luận
    public function layMotBinhLuan($comment_id) {
        $sql = "SELECT * FROM comments WHERE comment_id = $comment_id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // ========== LƯỢT XEM BÀI VIẾT ==========
    
    // Lưu lượt xem bài viết
    public function luuLuotXem($user_id, $article_id) {
        // Kiểm tra đã xem chưa
        $sql = "SELECT * FROM article_views WHERE user_id = $user_id AND article_id = $article_id";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Chưa xem -> Thêm mới
            $sql = "INSERT INTO article_views (user_id, article_id, viewed_at) VALUES ($user_id, $article_id, NOW())";
            $this->conn->query($sql);
            return true;
        } else {
            // Đã xem -> Cập nhật thời gian
            $sql = "UPDATE article_views SET viewed_at = NOW() WHERE user_id = $user_id AND article_id = $article_id";
            $this->conn->query($sql);
            return false;
        }
    }
    
    // Đếm tổng lượt xem của bài viết
    public function demLuotXemBaiViet($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_views WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // ========== THỐNG KÊ USER ==========
    
    // Đếm số bài đã đọc của user
    public function demBaiDaDoc($user_id) {
        $sql = "SELECT COUNT(*) as total FROM article_views WHERE user_id = $user_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Đếm số bài yêu thích của user
    public function demBaiYeuThich($user_id) {
        $sql = "SELECT COUNT(*) as total FROM article_likes WHERE user_id = $user_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Đếm số bài đã lưu của user
    public function demBaiDaLuu($user_id) {
        $sql = "SELECT COUNT(*) as total FROM article_saves WHERE user_id = $user_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Lấy danh sách bài đã đọc gần đây
    public function layBaiDaDoc($user_id, $limit = 10) {
        $sql = "SELECT a.*, c.name as category_name, v.viewed_at 
                FROM article_views v
                JOIN articles a ON v.article_id = a.article_id
                LEFT JOIN categories c ON a.category_id = c.category_id
                WHERE v.user_id = $user_id
                ORDER BY v.viewed_at DESC
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy danh sách bài yêu thích
    public function layBaiYeuThich($user_id, $limit = 10) {
        $sql = "SELECT a.*, c.name as category_name, l.created_at as liked_at
                FROM article_likes l
                JOIN articles a ON l.article_id = a.article_id
                LEFT JOIN categories c ON a.category_id = c.category_id
                WHERE l.user_id = $user_id
                ORDER BY l.created_at DESC
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}
?>
