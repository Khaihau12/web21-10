# HƯỚNG DẪN SỬ DỤNG WEB USER

## CẤU TRÚC FILE ĐÃ TẠO:

### 1. FILE TĨNH (Xem trước giao diện)
- `index.php` - Trang chủ tĩnh (dữ liệu mẫu cứng)

### 2. FILE ĐỘNG (Kết nối Database)
- `index_dynamic.php` - Trang chủ động (dữ liệu từ DB)
- `category.php` - Xem bài viết theo chuyên mục
- `article.php` - Xem chi tiết bài viết
- `dbuser.php` - Class database cho user
- `user_style.css` - File CSS chung

## CÁCH SỬ DỤNG:

### BƯỚC 1: Xem giao diện tĩnh
```
http://localhost/web21-10/index.php
```
→ Xem trước giao diện với dữ liệu mẫu

### BƯỚC 2: Test với dữ liệu thật từ Database
```
http://localhost/web21-10/index_dynamic.php
```
→ Xem dữ liệu thật từ database

### BƯỚC 3: Khi đã OK, đổi tên file
```
- Đổi index.php → index_static.php (lưu làm mẫu)
- Đổi index_dynamic.php → index.php (dùng chính thức)
```

## CHỨC NĂNG:

### Trang chủ (index.php / index_dynamic.php)
- Hiển thị tin nổi bật
- Hiển thị 6 bài viết mới nhất
- Sidebar: Danh sách chuyên mục + Tin mới nhất
- Click chuyên mục → Xem bài viết theo chuyên mục

### Trang chuyên mục (category.php)
- URL: category.php?id=5 (xem chuyên mục ID 5)
- URL: category.php (xem tất cả)
- Hiển thị tất cả bài viết của chuyên mục đó
- Sidebar highlight chuyên mục đang xem

### Trang chi tiết bài viết (article.php)
- URL: article.php?id=1 (xem bài viết ID 1)
- Hiển thị đầy đủ nội dung bài viết
- Hiển thị ảnh, tiêu đề, tóm tắt, nội dung
- Link quay lại chuyên mục và trang chủ

## CODE ĐƠN GIẢN, DỄ HIỂU:

### Ví dụ lấy dữ liệu:
```php
<?php
require_once 'dbuser.php';
$db = new dbuser();

// Lấy tất cả bài viết
$articles = $db->layTatCaBaiViet();

// Lấy bài viết theo chuyên mục
$articles = $db->layBaiVietTheoCategory(5);

// Lấy 1 bài viết
$article = $db->layBaiVietTheoId(1);
?>
```

### Ví dụ hiển thị:
```php
<?php foreach ($articles as $article): ?>
<div class="article-card">
    <h3><?php echo $article['title']; ?></h3>
    <p><?php echo $article['summary']; ?></p>
    <a href="article.php?id=<?php echo $article['article_id']; ?>">Đọc tiếp</a>
</div>
<?php endforeach; ?>
```

## LƯU Ý:
- File tĩnh để học và xem trước giao diện
- File động để chạy thật với database
- Code đơn giản, dễ hiểu cho người mới học
- Không dùng framework phức tạp
