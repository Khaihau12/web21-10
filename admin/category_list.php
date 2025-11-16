<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

$categories = $db->layDanhSachChuyenMuc();
?>

<div class="content-header">
    <h2>📁 Danh Sách Chuyên Mục</h2>
</div>

<div class="content-body">
    <?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted") { ?>
        <p class="success">✅ Xóa chuyên mục thành công!</p>
    <?php } ?>
    
    <a href="?page=add-category" class="btn btn-success">+ Thêm Chuyên Mục Mới</a>
    <br><br>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên chuyên mục</th>
                <th>Slug</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat) { ?>
                <tr>
                    <td><?= $cat['category_id'] ?></td>
                    <td><?= $cat['name'] ?></td>
                    <td><?= $cat['slug'] ?></td>
                    <td>
                        <a href="?page=edit-category&slug=<?= $cat['slug'] ?>" class="btn btn-success">Sửa</a>
                        <a href="?page=delete-category&slug=<?= $cat['slug'] ?>" 
                           onclick="return confirm('Bạn có chắc muốn xóa loại tin này?');"
                           class="btn btn-danger">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>