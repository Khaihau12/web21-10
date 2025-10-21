<?php
require_once "dbadmin.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    if ($category_id > 0) {
        $db = new dbadmin();
        $result = $db->delete('categories', ['category_id' => $category_id]);
        if ($result) {
            // Quay về trang danh sách chuyên mục
            header("Location: category_list.php?msg=success");
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