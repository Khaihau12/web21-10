<?php
require 'dbadmin.php';

// ensure session so we can show flash messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$err = '';
$success = '';
// show and clear any flash success message
if (!empty($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
// instantiate dbadmin class
$dbAdmin = new dbadmin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $dbAdmin->login($username, $password);
    if ($user) {
        // set flash success so other pages can display it
        $_SESSION['flash_success'] = 'Đăng nhập thành công';
        header('Location: index.php');
        exit;
    } else {
        $err = 'Sai username hoặc mật khẩu';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>Đăng Nhập Hệ Thống</h1>
<?php if ($err): ?><p class="error"><?=htmlspecialchars($err)?></p><?php endif; ?>
<?php if ($success): ?><p class="success"><?=htmlspecialchars($success)?></p><?php endif; ?>
<form method="post">
    <label>Username</label>
    <input name="username" required>
    
    <label>Password</label>
    <input name="password" type="password" required>
    
    <button type="submit">Đăng Nhập</button>
</form>
</div>
</body>
</html>