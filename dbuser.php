<?php
class dbuser {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "webthethao";
    protected $conn;
    
    // =======================================================================
    // CONSTRUCTOR & KẾT NỐI DATABASE
    // =======================================================================
    
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
    
    public function getConnection() {
        return $this->conn;
    }
    
    // =======================================================================
    // CHỨC NĂNG: XEM CHUYÊN MỤC (CATEGORIES)
    // Bao gồm: Xem tất cả chuyên mục, Xem chuyên mục gốc, Xem theo slug
    // Phương thức: layTatCaChuyenMuc(), layCategoryGoc(), layCategoryTheoSlug()
    // =======================================================================
    
    /**
     * Lấy tất cả chuyên mục
     * @return array - Mảng tất cả chuyên mục sắp xếp theo tên
     */
    public function layTatCaChuyenMuc() {
        $sql = "SELECT category_id, name, slug, parent_id FROM categories ORDER BY name ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Lấy danh sách category gốc (category cha - không có parent)
     * @param int $limit - Số lượng category cần lấy
     * @return array - Mảng category gốc
     */
    public function layCategoryGoc($limit = 4) {
        $sql = "SELECT category_id, name, slug, parent_id 
                FROM categories 
                WHERE parent_id IS NULL OR parent_id = 0
                ORDER BY category_id ASC
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Lấy thông tin category theo slug
     * @param string $category_slug - Slug của chuyên mục
     * @return array|null - Thông tin chuyên mục hoặc null
     */
    public function layCategoryTheoSlug($category_slug) {
        $sql = "SELECT category_id, name, slug, parent_id FROM categories WHERE slug = '$category_slug'";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    
    // =======================================================================
    // CHỨC NĂNG: XEM & ĐỌC BÀI VIẾT (ARTICLES)
    // Bao gồm: Xem bài mới nhất, nổi bật, theo chuyên mục, chi tiết bài viết
    // Phương thức: layBaiVietMoiNhat(), layBaiVietNoiBat(), 
    //              layBaiVietTheoCategory(), layChiTietBaiVietTheoSlug()
    // =======================================================================
    
    /**
     * Lấy bài viết mới nhất
     * @param int $limit - Số lượng bài viết
     * @return array - Mảng bài viết
     */
    public function layBaiVietMoiNhat($limit = 10) {
        $sql = "SELECT a.article_id, a.category_id, a.title, a.slug, 
                a.summary, a.content, a.image_url, a.author_id, 
                a.is_featured, a.created_at, 
                c.name as category_name, c.slug as category_slug 
                FROM articles AS a
                JOIN categories AS c ON a.category_id = c.category_id 
                ORDER BY a.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Lấy bài viết nổi bật
     * @param int $limit - Số lượng bài viết
     * @return array|null - Bài viết nổi bật
     */
    public function layBaiVietNoiBat($limit = 1) {
        $sql = "SELECT a.article_id, a.category_id, a.title, a.slug, 
                a.summary, a.content, a.image_url, a.author_id, 
                a.is_featured, a.created_at, 
                c.name as category_name, c.slug as category_slug 
                FROM articles AS a
                JOIN categories AS c ON a.category_id = c.category_id 
                WHERE a.is_featured = 1
                ORDER BY a.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    
    /**
     * Lấy bài viết theo category
     * @param string $category_slug - Slug chuyên mục
     * @param int $limit - Số lượng bài viết
     * @return array - Mảng bài viết
     */
    public function layBaiVietTheoCategory($category_slug, $limit = 10) {
        $sql = "SELECT a.article_id, a.category_id, a.title, a.slug, 
                a.summary, a.content, a.image_url, a.author_id, 
                a.is_featured, a.created_at, 
                c.name as category_name, c.slug as category_slug 
                FROM articles AS a
                JOIN categories AS c ON a.category_id = c.category_id 
                WHERE c.slug = '$category_slug'
                ORDER BY a.created_at DESC 
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy chi tiết bài viết theo SLUG (SEO-friendly)
     * @param string $article_slug - Slug bài viết
     * @return array|null - Thông tin bài viết
     */
    public function layChiTietBaiVietTheoSlug($article_slug) {
        $sql = "SELECT a.article_id, a.category_id, a.title, a.slug, 
                a.summary, a.content, a.image_url, a.author_id, 
                a.is_featured, a.created_at, 
                c.name as category_name, c.slug as category_slug
                FROM articles AS a
                JOIN categories AS c ON a.category_id = c.category_id 
                WHERE a.slug = '$article_slug'";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // =======================================================================
    // CHỨC NĂNG: XÁC THỰC VÀ QUẢN LÝ PHIÊN ĐĂNG NHẬP
    // Bao gồm: Đăng ký, Đăng nhập, Đăng xuất, Kiểm tra đăng nhập, Đổi mật khẩu
    // Phương thức: dangKy(), login(), logout(), isLoggedIn(), getCurrentUser(), doiMatKhau()
    // =======================================================================
    
    /**
     * Đăng ký tài khoản mới
     * @param string $username - Tên đăng nhập
     * @param string $password - Mật khẩu
     * @param string $email - Email
     * @param string $display_name - Tên hiển thị
     * @return array - Kết quả đăng ký (success, message)
     */
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
    
    /**
     * Đăng nhập user
     * @param string $username - Tên đăng nhập
     * @param string $password - Mật khẩu
     * @return bool - True nếu đăng nhập thành công
     */
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
    
    /**
     * Đăng xuất người dùng
     * @return bool - Luôn trả về true
     */
    public function logout() {
        session_destroy();
        return true;
    }

    /**
     * Kiểm tra đã đăng nhập hay chưa
     * @return bool - True nếu đã đăng nhập
     */
    public function isLoggedIn() {
        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
            return true;
        }
        return false;
    }
    
    /**
     * Lấy thông tin user hiện tại
     * @return array|null - Thông tin user hoặc null
     */
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

    /**
     * Đổi mật khẩu cho user
     * @param int $user_id - ID user
     * @param string $mat_khau_cu - Mật khẩu cũ
     * @param string $mat_khau_moi - Mật khẩu mới
     * @return array - Kết quả (success, message)
     */
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
    // CHỨC NĂNG: TƯƠNG TÁC BÀI VIẾT (Like, Save, View)
    // Bao gồm: Thích bài viết, Lưu bài viết, Lượt xem
    // Phương thức: daThichBaiViet(), daLuuBaiViet(), toggleThichBaiViet(), 
    //              toggleLuuBaiViet(), demLuotThich(), demLuotLuu(),
    //              luuLuotXem(), demLuotXemBaiViet()
    // =======================================================================

    /**
     * Kiểm tra user đã thích bài viết chưa
     * @param int $user_id - ID user
     * @param int $article_id - ID bài viết
     * @return bool - True nếu đã thích
     */
    public function daThichBaiViet($user_id, $article_id) {
        $sql = "SELECT * FROM article_likes WHERE user_id = $user_id AND article_id = $article_id";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0;
    }

    /**
     * Kiểm tra user đã lưu bài viết chưa
     * @param int $user_id - ID user
     * @param int $article_id - ID bài viết
     * @return bool - True nếu đã lưu
     */
    public function daLuuBaiViet($user_id, $article_id) {
        $sql = "SELECT * FROM article_saves WHERE user_id = $user_id AND article_id = $article_id";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0;
    }

    /**
     * Toggle thích bài viết (thích/bỏ thích)
     * @param int $user_id - ID user
     * @param int $article_id - ID bài viết
     * @return bool - True nếu vừa thích, False nếu vừa bỏ thích
     */
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

    /**
     * Toggle lưu bài viết (lưu/bỏ lưu)
     * @param int $user_id - ID user
     * @param int $article_id - ID bài viết
     * @return bool - True nếu vừa lưu, False nếu vừa bỏ lưu
     */
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

    /**
     * Đếm số lượt thích của bài viết
     * @param int $article_id - ID bài viết
     * @return int - Số lượt thích
     */
    public function demLuotThich($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_likes WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Đếm số lượt lưu của bài viết
     * @param int $article_id - ID bài viết
     * @return int - Số lượt lưu
     */
    public function demLuotLuu($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_saves WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Lưu lượt xem bài viết
     * @param int $user_id - ID user
     * @param int $article_id - ID bài viết
     * @return bool - True nếu lần đầu xem, False nếu đã xem trước đó
     */
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
    
    /**
     * Đếm tổng lượt xem của bài viết
     * @param int $article_id - ID bài viết
     * @return int - Số lượt xem
     */
    public function demLuotXemBaiViet($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_views WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // =======================================================================
    // CHỨC NĂNG: BÌNH LUẬN BÀI VIẾT (COMMENTS)
    // Bao gồm: Thêm, Sửa, Xóa bình luận của bản thân, Xem danh sách, Đếm bình luận
    // Phương thức: themBinhLuan(), layBinhLuan(), demBinhLuan(),
    //              xoaBinhLuan(), suaBinhLuan(), layMotBinhLuan()
    // =======================================================================
    
    /**
     * Thêm bình luận mới
     * @param int $article_id - ID bài viết
     * @param int $user_id - ID user
     * @param string $content - Nội dung bình luận
     * @return bool - True nếu thành công
     */
    public function themBinhLuan($article_id, $user_id, $content) {
        $sql = "INSERT INTO comments (article_id, user_id, content, created_at) 
                VALUES ($article_id, $user_id, '$content', NOW())";
        return $this->conn->query($sql);
    }

    /**
     * Lấy danh sách bình luận của bài viết
     * @param int $article_id - ID bài viết
     * @return array - Mảng bình luận
     */
    public function layBinhLuan($article_id) {
        $sql = "SELECT c.*, u.username, u.display_name 
                FROM comments AS c
                JOIN users AS u ON c.user_id = u.user_id
                WHERE c.article_id = $article_id
                ORDER BY c.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Đếm số bình luận của bài viết
     * @param int $article_id - ID bài viết
     * @return int - Số bình luận
     */
    public function demBinhLuan($article_id) {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = $article_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Xóa bình luận
     * @param int $comment_id - ID bình luận
     * @param int $user_id - ID user (chỉ xóa được bình luận của mình)
     * @return bool - True nếu xóa thành công
     */
    public function xoaBinhLuan($comment_id, $user_id) {
        $sql = "DELETE FROM comments WHERE comment_id = $comment_id AND user_id = $user_id";
        return $this->conn->query($sql);
    }

    /**
     * Sửa bình luận
     * @param int $comment_id - ID bình luận
     * @param int $user_id - ID user (chỉ sửa được bình luận của mình)
     * @param string $content - Nội dung mới
     * @return bool - True nếu sửa thành công
     */
    public function suaBinhLuan($comment_id, $user_id, $content) {
        $sql = "UPDATE comments SET content = '$content' WHERE comment_id = $comment_id AND user_id = $user_id";
        return $this->conn->query($sql);
    }

    /**
     * Lấy thông tin 1 bình luận
     * @param int $comment_id - ID bình luận
     * @return array|null - Thông tin bình luận
     */
    public function layMotBinhLuan($comment_id) {
        $sql = "SELECT * FROM comments WHERE comment_id = $comment_id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // =======================================================================
    // CHỨC NĂNG: THỐNG KÊ USER (Dashboard cá nhân)
    // Bao gồm: Thống kê bài đọc, yêu thích, lưu, danh sách bài
    // Phương thức: demBaiDaDoc(), demBaiYeuThich(), demBaiDaLuu(),
    //              layBaiDaDoc(), layBaiYeuThich()
    // =======================================================================
    
    /**
     * Đếm số bài đã đọc của user
     * @param int $user_id - ID user
     * @return int - Số bài đã đọc
     */
    public function demBaiDaDoc($user_id) {
        $sql = "SELECT COUNT(*) as total FROM article_views WHERE user_id = $user_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Đếm số bài yêu thích của user
     * @param int $user_id - ID user
     * @return int - Số bài yêu thích
     */
    public function demBaiYeuThich($user_id) {
        $sql = "SELECT COUNT(*) as total FROM article_likes WHERE user_id = $user_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Đếm số bài đã lưu của user
     * @param int $user_id - ID user
     * @return int - Số bài đã lưu
     */
    public function demBaiDaLuu($user_id) {
        $sql = "SELECT COUNT(*) as total FROM article_saves WHERE user_id = $user_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Lấy danh sách bài đã đọc gần đây
     * @param int $user_id - ID user
     * @param int $limit - Số lượng bài
     * @return array - Mảng bài viết đã đọc
     */
    public function layBaiDaDoc($user_id, $limit = 10) {
        $sql = "SELECT a.*, c.name as category_name, v.viewed_at 
                FROM article_views AS v
                JOIN articles AS a ON v.article_id = a.article_id
                LEFT JOIN categories AS c ON a.category_id = c.category_id
                WHERE v.user_id = $user_id
                ORDER BY v.viewed_at DESC
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Lấy danh sách bài yêu thích
     * @param int $user_id - ID user
     * @param int $limit - Số lượng bài
     * @return array - Mảng bài viết yêu thích
     */
    public function layBaiYeuThich($user_id, $limit = 10) {
        $sql = "SELECT a.*, c.name as category_name, l.created_at as liked_at
                FROM article_likes AS l
                JOIN articles AS a ON l.article_id = a.article_id
                LEFT JOIN categories AS c ON a.category_id = c.category_id
                WHERE l.user_id = $user_id
                ORDER BY l.created_at DESC
                LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}
?>
