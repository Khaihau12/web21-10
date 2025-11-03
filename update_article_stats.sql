-- Script cập nhật thêm các cột thống kê cho articles
USE webthethao;

-- Thêm các cột thống kê vào bảng articles (nếu chưa có)
ALTER TABLE `articles` 
ADD COLUMN `view_count` INT DEFAULT 0,
ADD COLUMN `like_count` INT DEFAULT 0,
ADD COLUMN `save_count` INT DEFAULT 0;

-- Khởi tạo giá trị 0 cho tất cả bài viết hiện có
UPDATE `articles` SET `view_count` = 0 WHERE `view_count` IS NULL;
UPDATE `articles` SET `like_count` = 0 WHERE `like_count` IS NULL;
UPDATE `articles` SET `save_count` = 0 WHERE `save_count` IS NULL;

-- Đếm lại số lượt thích từ bảng article_likes
UPDATE articles a 
SET like_count = (SELECT COUNT(*) FROM article_likes WHERE article_id = a.article_id);

-- Đếm lại số lượt lưu từ bảng article_saves
UPDATE articles a 
SET save_count = (SELECT COUNT(*) FROM article_saves WHERE article_id = a.article_id);

