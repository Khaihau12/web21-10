<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['category_id'])) {
        $category_id = intval($_POST['category_id']);
    } else {
        $category_id = 0;
    }
    
    if ($category_id > 0) {
        $db = new dbadmin();
        $result = $db->delete('categories', ['category_id' => $category_id]);
        if ($result) {
            // Quay về trang danh sách chuyên mục với thông báo thành công
            header("Location: index.php?page=categories&msg=deleted");
            exit;
        } else {
            echo "Xóa loại tin thất bại!";
        }
    } else {
        echo "ID không hợp lệ!";
    }
} else {
    echo "Phương thức không hợp lệ!";
}
?>