<?php
session_start();
// Kiểm tra xem người dùng đã đăng nhập và có phải là admin không
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Nếu không phải admin, chuyển hướng về trang chào mừng hoặc trang đăng nhập
    header('Location: ../welcome.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Bún Chả Hiền Hợi</title>
    <link rel="stylesheet" href="../css/style.css" /> <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/admin.css" /> </head>
<body>
    <header class="header">
        <div class="index-header"></div>
        <nav class="auth-buttons">
            <span class="welcome-text">Xin chào Admin, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?>!</span>
            <button class="logout-button">Đăng xuất</button>
        </nav>
    </header>

    <div class="admin-main-content">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <div class="admin-sections">
                <button class="admin-nav-button" data-section="products">Quản lý Sản phẩm</button>
                <button class="admin-nav-button" data-section="orders">Quản lý Đơn hàng</button>
                </div>

            <div id="admin-products-section" class="admin-section active">
                <h2>Quản lý Sản phẩm</h2>
                <div class="product-list">
                    <h3>Danh sách Sản phẩm</h3>
                    <table id="products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên món</th>
                                <th>Giá</th>
                                <th>Mô tả</th>
                                <th>Ảnh</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>

                <div class="product-form">
                    <h3>Thêm/Sửa Sản phẩm</h3>
                    <form id="product-form">
                        <input type="hidden" id="product-id" name="id">
                        <div class="form-group">
                            <label for="product-name">Tên món:</label>
                            <input type="text" id="product-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="product-price">Giá:</label>
                            <input type="number" id="product-price" name="price" required min="0">
                        </div>
                        <div class="form-group">
                            <label for="product-description">Mô tả:</label>
                            <textarea id="product-description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product-image-url">URL Ảnh:</label>
                            <input type="url" id="product-image-url" name="image_url">
                        </div>
                        <button type="submit" class="submit-button">Thêm/Cập nhật Sản phẩm</button>
                        <button type="button" class="cancel-button" id="cancel-edit-button" style="display:none;">Hủy</button>
                    </form>
                </div>
            </div>

            <div id="admin-orders-section" class="admin-section">
                <h2>Quản lý Đơn hàng</h2>
                <p>Nội dung quản lý đơn hàng sẽ được thêm vào đây.</p>
                </div>

            </div>
    </div>

    <div class="footer">
      <p>&copy; 2025 Bún Chả Hiền Hợi. All rights reserved.</p>
      <p>Made by Nhóm 25 - TTCN.</p>
    </div>

    <script src="../js/admin.js"></script>
    <script>
        // JavaScript cho nút Đăng xuất trong trang Admin
        const logoutButton = document.querySelector(".logout-button");
        if (logoutButton) {
            logoutButton.addEventListener("click", async () => {
                try {
                    const response = await fetch('../api/logout.php', { method: 'POST' });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message);
                        window.location.href = '../welcome.php'; // Chuyển về trang chào mừng sau khi đăng xuất
                    } else {
                        alert('Lỗi khi đăng xuất: ' + result.message);
                    }
                } catch (error) {
                    console.error('Lỗi network hoặc server khi đăng xuất:', error);
                    alert('Không thể kết nối đến máy chủ để đăng xuất.');
                }
            });
        }
    </script>
</body>
</html>