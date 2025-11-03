# 📋 HƯỚNG DẪN BỐ CỤC MỚI ĐÃ CẬP NHẬT

## ✅ ĐÃ HOÀN THÀNH

Đã cập nhật bố cục giao diện cho 3 file chính dựa trên thư mục `fix index category article`:

### 1. **index.php** ✅
**Thay đổi:**
- ✅ Thêm **top-bar** với logo "24H 📰" và form tìm kiếm
- ✅ **Main navigation** ngang với icon Font Awesome
- ✅ Layout **hightl-24h-block** (1 bài nổi bật lớn + danh sách bài nhỏ bên phải)
- ✅ **Category showcase** với header + sub-navigation
- ✅ **Sidebar bên phải** với danh sách tin mới nhất
- ✅ Footer đơn giản với link Quản trị

**Class CSS sử dụng:**
- `.top-bar`, `.logo`, `.top-menu`
- `.main-nav`
- `.hightl-24h-block`, `.hightl-24h-big`, `.hightl-24h-list`
- `.category-showcase-block`
- `.col-8`, `.col-4` (grid 2 cột)
- `.sidebar-column`, `.latest-news-block`

---

### 2. **category.php** ✅
**Thay đổi:**
- ✅ Giống header/nav như index.php
- ✅ **Page title** với border-bottom màu xanh (#78B43D)
- ✅ **List view** với ảnh bên trái (220px) + info bên phải
- ✅ Class `.list-news-item` hiển thị ngang
- ✅ Không có sidebar (full width)
- ✅ Footer giống index.php

**Class CSS sử dụng:**
- `.page-title` (border-bottom #78B43D)
- `.category-article-list`
- `.list-news-item` (d-flex)
- `.list-news-img` (flex: 0 0 220px)
- `.list-news-info`

---

### 3. **article.php** ✅
**Thay đổi:**
- ✅ Giống header/nav như index.php
- ✅ **Breadcrumb** navigation
- ✅ Layout 2 cột: `.col-8` (main) + `.col-4` (sidebar)
- ✅ **Article header** với tiêu đề + meta info
- ✅ **Article content** với summary bold, nội dung chi tiết
- ✅ **Action buttons**: Thích, Lưu đọc sau
- ✅ **Comments section**
- ✅ Sidebar tin mới nhất

**Class CSS sử dụng:**
- `.breadcrumb`
- `.full-article`, `.article-header`, `.article-content`
- `.article-actions`
- `.comments`
- `.sidebar-column`, `.latest-news-block`

---

## 🎨 CÁC CLASS CSS QUAN TRỌNG CẦN CÓ TRONG `style.css`

Bạn cần thêm các class này vào file `style.css` hiện tại (copy từ `fix index category article/css/style.css`):

###  **Utility Classes**
```css
.d-flex { display: flex; }
.justify-content-between { justify-content: space-between; }
.align-items-center { align-items: center; }
.fw-bold { font-weight: bold; }
.fw-medium { font-weight: 500; }
.text-uppercase { text-transform: uppercase; }
.color-main { color: #333; }
.color-24h { color: #d90000; }
.color-green-custom { color: #78B43D; }
.hover-color-24h:hover { color: #d90000; }
.img-fluid { max-width: 100%; height: auto; }
```

### 2. **Grid System**
```css
.row {
    display: flex;
    flex-wrap: wrap;
    gap: 2%;
}
.col-8 { flex: 0 0 66.6667%; max-width: 66.6667%; }
.col-4 { flex: 0 0 32%; max-width: 32%; }
```

### 3. **Top Bar & Navigation**
```css
.top-bar {
    background-color: #fff;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}
.logo a {
    color: #007bff;
    font-size: 18px;
    font-weight: bold;
}
.main-nav {
    background-color: #333;
    color: #fff;
    border-top: 3px solid #d90000;
}
```

### 4. **Featured Block (Tin nổi bật)**
```css
.hightl-24h-block {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}
.hightl-24h-big {
    flex: 0 0 60%;
}
.hightl-24h-list {
    flex: 1;
}
```

### 5. **Category Showcase**
```css
.category-showcase-block {
    margin: 30px 0;
}
.category-showcase-header {
    border-bottom: 3px solid #78B43D;
    margin-bottom: 15px;
}
```

### 6. **Article List (Category page)**
```css
.list-news-item {
    display: flex;
    margin-bottom: 20px;
    border-bottom: 1px dotted #ccc;
    padding-bottom: 15px;
}
.list-news-img {
    flex: 0 0 220px;
    margin-right: 20px;
}
```

### 7. **Sidebar**
```css
.sidebar-column {
    padding-left: 20px;
}
.sidebar-article {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px dotted #eee;
}
```

---

## 🔧 NHỮNG VIỆC CẦN LÀM TIẾP

1. **Copy toàn bộ CSS** từ `fix index category article/css/style.css` sang `style.css` của bạn
2. **Xóa code bị dư** trong `article.php` (dòng 245 trở đi)
3. **Test** các trang trên trình duyệt
4. **Tích hợp PHP** để lấy dữ liệu từ database thay cho dữ liệu mẫu

---

## 📊 SO SÁNH BỐ CỤC CŨ VÀ MỚI

| Tính năng | Bố cục cũ | Bố cục mới |
|-----------|-----------|------------|
| **Header** | 1 cấp đơn giản | 2 cấp (top-bar + main-nav) |
| **Logo** | Emoji đơn giản | "24H 📰" + subtext |
| **Search** | Không có | Form tìm kiếm trong top-bar |
| **Trang chủ** | Sidebar trái + content | Content 66% + sidebar phải 33% |
| **Tin nổi bật** | 1 box đơn giản | Layout ngang (ảnh lớn + danh sách) |
| **Category page** | Giống trang chủ | List view (ảnh trái + text phải) |
| **Article page** | Full width | 2 cột (content + sidebar) |
| **Icons** | Emoji | Font Awesome |
| **Colors** | #3498db (blue) | #d90000 (red) + #78B43D (green) |

---

## 💡 LƯU Ý QUAN TRỌNG

1. **Font Awesome**: Cần có CDN link trong `<head>`:
   ```html
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
   ```

2. **Responsive**: Bố cục mới hỗ trợ responsive tốt hơn với breakpoint @media

3. **Màu sắc chủ đạo**:
   - Đỏ #d90000: Màu nhấn, hover, border
   - Xanh #78B43D: Tiêu đề chuyên mục
   - Đen #333: Text chính
   - Xám #888: Text phụ

4. **Giữ nguyên** file `style.css` cũ, chỉ cần **thêm vào** các class mới

---

## 🎯 KẾT LUẬN

Bạn đã học được cách tổ chức bố cục trang tin tức chuyên nghiệp:
- ✅ Header 2 cấp với tìm kiếm
- ✅ Layout linh hoạt với grid system
- ✅ Tin nổi bật ngang
- ✅ List view cho trang category
- ✅ 2 cột cho trang chi tiết
- ✅ Sidebar tin mới nhất

**Next step**: Tích hợp PHP/database để lấy dữ liệu thực!
