<?php
/**
 * DEMO - HIỂN THỊ DANH SÁCH LOẠI TIN
 * Câu 4: Thiết kế và xây dựng chức năng hiển thị danh sách loại tin
 */

// Kiểm tra đăng nhập
require_once 'check_login.php';

require_once 'dbadmin.php';

// Khởi tạo database
$db = new dbadmin();

?>

<div class="content-header">
    <h2>📋 Demo Danh Sách Loại Tin</h2>
</div>

<div class="content-body">
    <h3>📊 Danh Sách Tất Cả Loại Tin</h3>
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
                                    echo "<td>#{$loaiTin['parent_id']}</td>";
                                    echo "<td>Loại tin con</td>";
                                } else {
                                    echo "<td>-</td>";
                                    echo "<td>Loại tin cha (gốc)</td>";
                                }
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            
            <br><br>
            
            <!-- Cây phân cấp loại tin -->
            <h3>🌳 Cây Phân Cấp Loại Tin</h3>
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
</div>
