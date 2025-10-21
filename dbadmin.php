<?php
class dbadmin {
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
    
    // =======================================================================
    // TÍNH NĂNG: ĐĂNG NHẬP ADMIN
    // =======================================================================
    
    public function login($usernameOrEmail, $password) {
        // chuẩn hóa đầu vào
        $usernameOrEmail = trim($usernameOrEmail);
        if ($usernameOrEmail === '' || $password === '') {
            return false;
        }

        // Tìm user theo username hoặc email
        $sql = "SELECT user_id, username, password_hash, display_name, email, role FROM users WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('dbadmin->login prepare failed: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        if (!$user) {
            // Không tìm thấy user
            return false;
        }

        // Kiểm tra password
        if (!isset($user['password_hash'])) {
            return false;
        }


        if (password_verify($password, $user['password_hash'])) {
            // Xóa trường password_hash trước khi trả về
            unset($user['password_hash']);
            // Khởi tạo session nếu cần, rotate id và lưu user_id
            if (session_status() !== PHP_SESSION_ACTIVE) {
                @session_start();
            }
            // Rotate session id to prevent fixation
            if (function_exists('session_regenerate_id')) {
                session_regenerate_id(true);
            }
            $_SESSION['user_id'] = $user['user_id'];
            return $user;
        }

        return false;
    }
    
    // Đăng xuất người dùng
    public function logout($destroy = true) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        // remove user id
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
        if ($destroy) {
            // Clear session data and destroy session cookie
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'], $params['secure'], $params['httponly']
                );
            }
            @session_destroy();
        }
        return true;
    }

    // Kiểm tra đã đăng nhập hay chưa
    public function isLoggedIn() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        return !empty($_SESSION['user_id']);
    }
    
    // Lấy thông tin user hiện tại (nếu cần)
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        $uid = $_SESSION['user_id'];
        $sql = "SELECT user_id, username, display_name, email, role FROM users WHERE user_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }
    
    // =======================================================================
    // TÍNH NĂNG: HIỂN THỊ DANH SÁCH LOẠI TIN
    // =======================================================================
    
    /**
     * Hiển thị danh sách tất cả loại tin
     * @return array|bool - Mảng danh sách loại tin hoặc false nếu lỗi
     */
    public function hienThiDanhSachLoaiTin() {
        $sql = "SELECT category_id, name, slug, parent_id FROM categories ORDER BY category_id ASC";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            return false;
        }
        
        $danhSach = array();
        
        while ($row = $result->fetch_assoc()) {
            $danhSach[] = $row;
        }
        
        $result->free();
        
        return $danhSach;
    }
    
    /**
     * Hiển thị danh sách loại tin theo parent (phân cấp)
     * @param int|null $parent_id - ID của loại tin cha, null để lấy loại tin gốc
     * @return array|bool - Mảng danh sách loại tin hoặc false nếu lỗi
     */
    public function hienThiDanhSachLoaiTinTheoParent($parent_id = null) {
        if ($parent_id === null) {
            // Lấy các loại tin gốc (không có parent)
            $sql = "SELECT category_id, name, slug, parent_id FROM categories WHERE parent_id IS NULL ORDER BY category_id ASC";
            $stmt = $this->conn->prepare($sql);
        } else {
            // Lấy các loại tin con theo parent_id
            $sql = "SELECT category_id, name, slug, parent_id FROM categories WHERE parent_id = ? ORDER BY category_id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $parent_id);
        }
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $danhSach = array();
        
        while ($row = $result->fetch_assoc()) {
            $danhSach[] = $row;
        }
        
        $stmt->close();
        
        return $danhSach;
    }
    
    /**
     * Lấy thông tin chi tiết một loại tin theo ID
     * @param int $category_id - ID của loại tin
     * @return array|bool - Mảng thông tin loại tin hoặc false nếu không tìm thấy
     */
    public function layThongTinLoaiTin($category_id) {
        $sql = "SELECT category_id, name, slug, parent_id FROM categories WHERE category_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data;
        }
        
        $stmt->close();
        return false;
    }
    
    /**
     * Đếm tổng số lượng loại tin
     * @return int - Số lượng loại tin
     */
    public function demSoLuongLoaiTin() {
        $sql = "SELECT COUNT(*) as total FROM categories";
        
        $result = $this->conn->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        
        return 0;
    }
    
    // =======================================================================
    // TÍNH NĂNG: XÓA CATEGORY
    // =======================================================================
    
    public function getList($table) {
        $result = $this->conn->query("SELECT * FROM `$table`");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    public function delete($table, $where) {
        $where_clause = [];
        $vals = [];
        foreach ($where as $key => $val) {
            $where_clause[] = "`$key` = ?";
            $vals[] = $val;
        }
        $sql = "DELETE FROM `$table` WHERE " . implode(" AND ", $where_clause);
        $stmt = $this->conn->prepare($sql);
        $types = str_repeat("s", count($vals));
        $stmt->bind_param($types, ...$vals);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    // =======================================================================
    // TÍNH NĂNG: SỬA TIN (CẬP NHẬT CHUYÊN MỤC)
    // =======================================================================
    
    // Cập nhật thông tin chuyên mục
    public function update($category_id, $data) {
        // Validate input
        if (empty($data['name'])) {
            return ['success' => false, 'message' => 'Tên chuyên mục không được trống'];
        }
        
        // Generate slug if not provided
        $slug = isset($data['slug']) && !empty($data['slug']) 
            ? $this->createSlug($data['slug'])
            : $this->createSlug($data['name']);
            
        // Check if new parent would create a cycle
        if (!empty($data['parent_id']) && !$this->isValidParent($category_id, $data['parent_id'])) {
            return ['success' => false, 'message' => 'Không thể chọn chuyên mục con làm chuyên mục cha'];
        }
        
        // Update category
        $stmt = $this->conn->prepare("
            UPDATE categories 
            SET name = ?, slug = ?, parent_id = ? 
            WHERE category_id = ?
        ");
        
        $parent_id = empty($data['parent_id']) ? null : $data['parent_id'];
        
        $stmt->bind_param("ssii", 
            $data['name'],
            $slug,
            $parent_id,
            $category_id
        );
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cập nhật chuyên mục thành công'];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi cập nhật: ' . $stmt->error];
        }
    }

    // Helper method to create slug
    private function createSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    // Helper method to check valid parent
    private function isValidParent($category_id, $parent_id) {
        // Cannot set itself as parent
        if ($category_id == $parent_id) {
            return false;
        }
        
        // Get all children of the category
        $stmt = $this->conn->prepare("
            WITH RECURSIVE category_tree AS (
                SELECT category_id, parent_id
                FROM categories
                WHERE category_id = ?
                UNION ALL
                SELECT c.category_id, c.parent_id
                FROM categories c
                INNER JOIN category_tree ct ON c.parent_id = ct.category_id
            )
            SELECT category_id FROM category_tree
        ");
        
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if proposed parent is not among children
        while ($row = $result->fetch_assoc()) {
            if ($row['category_id'] == $parent_id) {
                return false;
            }
        }
        
        return true;
    }
    
    // =======================================================================
    // TÍNH NĂNG: THÊM TIN
    // =======================================================================
    
    /**
     * Thêm bài viết mới vào database
     * @param array $data - Mảng chứa thông tin bài viết
     * @return bool|int - Trả về ID bài viết mới nếu thành công, false nếu thất bại
     */
    public function themBaiViet($data) {
        // Validate dữ liệu đầu vào
        if (empty($data['category_id']) || empty($data['title'])) {
            return false;
        }
        
        // Chuẩn bị dữ liệu
        $category_id = (int)$data['category_id'];
        $title = $data['title'];
        $slug = !empty($data['slug']) ? $data['slug'] : $this->taoSlug($title);
        $summary = $data['summary'] ?? '';
        $content = $data['content'] ?? '';
        $image_url = $data['image_url'] ?? null;
        $author_id = isset($data['author_id']) ? (int)$data['author_id'] : null;
        $is_featured = isset($data['is_featured']) ? (int)$data['is_featured'] : 0;
        
        // Chuẩn bị câu lệnh SQL
        $sql = "INSERT INTO articles (category_id, title, slug, summary, content, image_url, author_id, is_featured, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        // Bind parameters
        $stmt->bind_param("isssssii", $category_id, $title, $slug, $summary, $content, $image_url, $author_id, $is_featured);
        
        // Thực thi
        if ($stmt->execute()) {
            $insert_id = $stmt->insert_id;
            $stmt->close();
            return $insert_id;
        } else {
            $stmt->close();
            return false;
        }
    }
    

    /**
     * Lấy danh sách chuyên mục
     * @return array - Mảng các chuyên mục
     */
    public function layDanhSachChuyenMuc() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $result = $this->conn->query($sql);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    //Loại bỏ ký tự có dấu tiếng Việt
    private function stripUnicode($str) {
        if (!$str) return false;
        
        $unicode = array (
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ', 
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ', 
            'd'=>'đ',
            'D'=>'Đ', 
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ', 
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ', 
            'i'=>'í|ì|ỉ|ĩ|ị', 
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị', 
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ', 
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự', 
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự', 
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ', 
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
        );
        
        foreach ($unicode as $khongdau => $codau) {
            $arr = explode("|", $codau);
            $str = str_replace($arr, $khongdau, $str);
        }
        
        return $str;
    }
    
    private function stripSpecial($str) {
        $arr = array(",", "$", "!", "?", "&", "'", '"', "+"); 
        $str = str_replace($arr, "", $str);   
        $str = trim($str); 
        
        // Loại bỏ khoảng trắng thừa
        while (strpos($str, "  ") > 0) {
            $str = str_replace("  ", " ", $str);
        }
        
        // Chuyển khoảng trắng thành dấu gạch ngang
        $str = str_replace(" ", "-", $str);  
        
        return $str;
    }
    
    //Tạo slug từ tiêu đề
    private function taoSlug($str) {
        // Loại bỏ ký tự có dấu
        $str = $this->stripUnicode($str);
        
        // Chuyển về chữ thường
        $str = mb_strtolower($str, 'UTF-8');
        
        // Loại bỏ ký tự đặc biệt và format slug
        $str = $this->stripSpecial($str);
        
        return $str;
    }
    
    // =======================================================================
    // ĐÓNG KẾT NỐI
    // =======================================================================
    
    /**
     * Đóng kết nối database
     */
    public function dongKetNoi() {
        if ($this->conn && !$this->conn->connect_errno) {
            $this->conn->close();
            $this->conn = null; // Đặt về null sau khi đóng để tránh đóng lại
        }
    }
    
    /**
     * Destructor - Tự động đóng kết nối khi object bị hủy
     */
    public function __destruct() {
        $this->dongKetNoi();
    }
}
?>
