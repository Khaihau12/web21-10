<?php
/**
 * DEMO - HIỂN THỊ DANH SÁCH LOẠI TIN
 * Câu 4: Thiết kế và xây dựng chức năng hiển thị danh sách loại tin
 */

require_once 'dbadmin.php';

// Khởi tạo database
$db = new dbadmin();

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Loại Tin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .tree-item {
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
            border-left: 3px solid #4CAF50;
        }
        .tree-children {
            margin-left: 30px;
        }
        .tree-child {
            padding: 8px;
            margin: 5px 0;
            background-color: white;
            border-left: 3px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh Sách Loại Tin</h1>
        <p><i>Hiển thị danh sách các loại tin trong hệ thống</i></p>
        
        <hr>
        
        <!-- Bảng danh sách tất cả loại tin -->
        <h2>Danh Sách Tất Cả Loại Tin</h2>
        <p>Tổng số loại tin: <strong><?php echo $db->demSoLuongLoaiTin(); ?></strong></p>
        
        <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Loại Tin</th>
                            <th>Slug</th>
                            <th>Parent ID</th>
                            <th>Loại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $danhSachTatCa = $db->hienThiDanhSachLoaiTin();
                        
                        if ($danhSachTatCa && count($danhSachTatCa) > 0) {
                            foreach ($danhSachTatCa as $loaiTin) {
                                echo "<tr>";
                                echo "<td><strong>#{$loaiTin['category_id']}</strong></td>";
                                echo "<td>{$loaiTin['name']}</td>";
                                echo "<td>{$loaiTin['slug']}</td>";
                                
                                if ($loaiTin['parent_id']) {
                                    $parentInfo = $db->layThongTinLoaiTin($loaiTin['parent_id']);
                                    echo "<td>#{$loaiTin['parent_id']} - {$parentInfo['name']}</td>";
                                    echo "<td>Loại tin con</td>";
                                } else {
                                    echo "<td>-</td>";
                                    echo "<td>Loại tin gốc</td>";
                                }
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            
            <hr>
            
            <!-- Cây phân cấp loại tin -->
            <h2>Cây Phân Cấp Loại Tin</h2>
            <div class="category-tree">
                <?php
                // Lấy các loại tin gốc (không có parent)
                $danhSachGoc = $db->hienThiDanhSachLoaiTinTheoParent(null);
                
                if ($danhSachGoc && count($danhSachGoc) > 0) {
                    foreach ($danhSachGoc as $loaiTinGoc) {
                        echo "<div class='tree-item'>";
                        echo "<strong>{$loaiTinGoc['name']}</strong> ({$loaiTinGoc['slug']})";
                        
                        // Lấy các loại tin con
                        $danhSachCon = $db->hienThiDanhSachLoaiTinTheoParent($loaiTinGoc['category_id']);
                        
                        if ($danhSachCon && count($danhSachCon) > 0) {
                            echo "<div class='tree-children'>";
                            foreach ($danhSachCon as $loaiTinCon) {
                                echo "<div class='tree-child'>";
                                echo "↳ {$loaiTinCon['name']} ({$loaiTinCon['slug']})";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        
                        echo "</div>";
                    }
                } else {
                    echo "<p>Không có dữ liệu</p>";
                }
                ?>
            </div>
            
            <hr>
            <a href="index.php">← Quay lại trang chủ</a>
        </div>
    </body>
</html>
