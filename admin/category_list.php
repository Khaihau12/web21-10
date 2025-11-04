<?php
// Kiểm tra đăng nhập
require_once 'check_login.php';

$categories = $db->getList("categories",);
?>

<div class="content-header">
    <h2>📁 Danh Sách Chuyên Mục</h2>
</div>

<div class="content-body">
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
                        <a href="?page=edit-category&id=<?= $cat['category_id'] ?>" class="btn btn-success">Sửa</a>
                        <form method="POST" action="delete_category.php" onsubmit="return confirm('Bạn có chắc muốn xóa loại tin này?');" style="display:inline;">
                            <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == "success") { ?>
        <p style="color: green; margin-top: 15px;">✓ Xóa loại tin thành công!</p>
    <?php } ?>
</div>