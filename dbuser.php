<?php
class dbuser {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "project";
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
    
    // Lấy tất cả bài viết
    public function layTatCaBaiViet($limit = 10) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                ORDER BY articles.created_at DESC 
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
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
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    // Lấy bài viết theo category
    public function layBaiVietTheoCategory($category_slug, $limit = 10) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE categories.slug = ?
                ORDER BY articles.created_at DESC 
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $category_slug, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
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
                WHERE articles.article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
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
                WHERE (articles.title LIKE ? OR articles.content LIKE ?)
                ORDER BY articles.created_at DESC 
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $keyword, $keyword, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy bài viết theo category_id
    public function layBaiVietTheoCategoryId($category_id, $limit = 20) {
        $sql = "SELECT articles.article_id, articles.category_id, articles.title, articles.slug, 
                articles.summary, articles.content, articles.image_url, articles.author_id, 
                articles.is_featured, articles.created_at, categories.name as category_name 
                FROM articles 
                LEFT JOIN categories ON articles.category_id = categories.category_id 
                WHERE articles.category_id = ?
                ORDER BY articles.created_at DESC 
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $category_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy thông tin category theo ID
    public function layCategoryTheoId($category_id) {
        $sql = "SELECT category_id, name, slug, parent_id FROM categories WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
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
                WHERE articles.article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // =======================================================================
    // TÍNH NĂNG: ĐĂNG NHẬP USER
    // =======================================================================
    
    public function dangNhap($username, $password) {
        // Lấy tất cả tài khoản từ database
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        
        // Duyệt qua từng tài khoản
        while ($row = $result->fetch_assoc()) {
            // Nếu tìm thấy username và password khớp VÀ role phải là user (không phải admin/editor)
            if ($row['username'] == $username && $row['password'] == $password) {
                if ($row['role'] == 'user') {
                    return $row; // Đăng nhập thành công, trả về thông tin user
                }
            }
        }
        
        return false; // Không tìm thấy tài khoản khớp hoặc không phải user
    }
    
    // Đăng xuất người dùng (đơn giản)
    public function dangXuat() {
        return true;
    }

    // Kiểm tra đã đăng nhập hay chưa
    public function kiemTraDangNhap() {
        return isset($_SESSION['user_id']);
    }
    
    // Lấy thông tin user hiện tại từ session
    public function layUserHienTai() {
        if (isset($_SESSION['user_id'])) {
            // Query lại database để lấy thông tin đầy đủ
            $sql = "SELECT user_id, username, password, role, display_name, email, created_at 
                    FROM users 
                    WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            
            // Nếu không tìm thấy trong DB, trả về thông tin từ session
            return [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'] ?? '',
                'display_name' => $_SESSION['display_name'] ?? '',
                'role' => $_SESSION['role'] ?? 'user',
                'email' => '',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        return null;
    }

    // Kiểm tra user đã thích bài viết chưa
    public function daThichBaiViet($user_id, $article_id) {
        $sql = "SELECT * FROM article_likes WHERE user_id = ? AND article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Kiểm tra user đã lưu bài viết chưa
    public function daLuuBaiViet($user_id, $article_id) {
        $sql = "SELECT * FROM article_saves WHERE user_id = ? AND article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Toggle thích bài viết
    public function toggleThichBaiViet($user_id, $article_id) {
        if ($this->daThichBaiViet($user_id, $article_id)) {
            // Đã thích -> Bỏ thích
            $sql = "DELETE FROM article_likes WHERE user_id = ? AND article_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $article_id);
            $stmt->execute();
            return false;
        } else {
            // Chưa thích -> Thêm thích
            $sql = "INSERT INTO article_likes (user_id, article_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $article_id);
            $stmt->execute();
            return true;
        }
    }

    // Toggle lưu bài viết
    public function toggleLuuBaiViet($user_id, $article_id) {
        if ($this->daLuuBaiViet($user_id, $article_id)) {
            // Đã lưu -> Bỏ lưu
            $sql = "DELETE FROM article_saves WHERE user_id = ? AND article_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $article_id);
            $stmt->execute();
            return false;
        } else {
            // Chưa lưu -> Thêm lưu
            $sql = "INSERT INTO article_saves (user_id, article_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $article_id);
            $stmt->execute();
            return true;
        }
    }

    // Đếm số lượt thích của bài viết
    public function demLuotThich($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_likes WHERE article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Đếm số lượt lưu của bài viết
    public function demLuotLuu($article_id) {
        $sql = "SELECT COUNT(*) as total FROM article_saves WHERE article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // ========== BÌNH LUẬN ==========
    
    // Thêm bình luận mới
    public function themBinhLuan($article_id, $user_id, $content) {
        $sql = "INSERT INTO comments (article_id, user_id, content, created_at) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $article_id, $user_id, $content);
        return $stmt->execute();
    }

    // Lấy danh sách bình luận của bài viết
    public function layBinhLuan($article_id) {
        $sql = "SELECT c.*, u.username, u.display_name 
                FROM comments c
                JOIN users u ON c.user_id = u.user_id
                WHERE c.article_id = ?
                ORDER BY c.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Đếm số bình luận của bài viết
    public function demBinhLuan($article_id) {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Xóa bình luận (chỉ user tự xóa bình luận của mình)
    public function xoaBinhLuan($comment_id, $user_id) {
        $sql = "DELETE FROM comments WHERE comment_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $comment_id, $user_id);
        return $stmt->execute();
    }

    // Sửa bình luận (chỉ user tự sửa bình luận của mình)
    public function suaBinhLuan($comment_id, $user_id, $content) {
        $sql = "UPDATE comments SET content = ? WHERE comment_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $content, $comment_id, $user_id);
        return $stmt->execute();
    }

    // Lấy thông tin 1 bình luận
    public function layMotBinhLuan($comment_id) {
        $sql = "SELECT * FROM comments WHERE comment_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $comment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}
?>
