<?php
require_once 'dbadmin.php';

$db = new dbadmin();
$db->logout();

// Chuyển về trang đăng nhập
header('Location: login.php');
exit();
?>
