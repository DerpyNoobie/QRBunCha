<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thực đơn Bún Chả Hiền Hợi</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
  </head>
  <body>
    <header class="header">
      <div class="index-header"></div>
      <nav class="auth-buttons">
        <?php
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            // Nếu đã đăng nhập, hiển thị tên người dùng và nút Đăng xuất
            // htmlspecialchars() giúp ngăn chặn các cuộc tấn công XSS
            echo '<span class="welcome-text">Xin chào, ' . htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) . '!</span>';
            echo '<button class="logout-button">Đăng xuất</button>';
        } else {
            // Nếu chưa đăng nhập, hiển thị nút Đăng nhập và Đăng ký
            echo '<button class="login-button">Đăng nhập</button>';
            echo '<button class="register-button">Đăng ký</button>';
        }
        ?>
      </nav>
    </header>
    <div class="main-content">
      <div class="container">
      </br>
        <h1>THỰC ĐƠN</h1>
        <div class="menu"></div>
        <div class="cart-container">
          <h2>Giỏ hàng</h2>
          <ul class="cart-items"></ul>
          <p class="cart-total">Tổng: 0đ</p>
          <button class="checkout-button">Thanh toán</button>
        </div>
      </div>
    </div>
    <div class="footer">
      <p>&copy; 2025 Bún Chả Hiền Hợi. All rights reserved.</p>
      <p>Made by Nhóm 25 - TTCN.</p>
    </div>
    <script src="../js/script.js"></script>
    <script>
      const loginButton = document.querySelector(".login-button");
      const registerButton = document.querySelector(".register-button");
      const logoutButton = document.querySelector(".logout-button"); // Nút đăng xuất (nếu có)
      if (loginButton) {
        loginButton.addEventListener("click", () => {
          window.location.href = "../html/login.php"; // Chú ý: đã đổi sang .php
        });
      }

      // Gán sự kiện cho nút Đăng ký (chỉ khi nút này tồn tại)
      if (registerButton) {
        registerButton.addEventListener("click", () => {
          window.location.href = "../html/register.php"; // Chú ý: đã đổi sang .php
        });
      }

      // Gán sự kiện cho nút Đăng xuất (chỉ khi nút này tồn tại)
      if (logoutButton) {
        logoutButton.addEventListener("click", async () => {
          // Gửi yêu cầu AJAX đến api/logout.php để hủy session trên server
          try {
            const response = await fetch('../api/logout.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              }
            });
            const result = await response.json();
            if (result.success) {
              alert(result.message);
              window.location.href = '../html/welcome.php'; // Chuyển hướng về trang chào mừng sau khi đăng xuất
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