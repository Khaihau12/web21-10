<?php
session_start();
require_once 'dbadmin.php';

// Khởi tạo database
$db = new dbadmin();

// Đăng xuất (xóa session)
$db->logout();

// Chuyển về trang đăng nhập
header('Location: login.php');
exit();
?>
