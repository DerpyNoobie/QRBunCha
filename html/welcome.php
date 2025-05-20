<?php
session_start(); // Bắt đầu phiên làm việc để truy cập các biến session
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chào mừng đến với Bún Chả Hiền Hợi</title>
    <link rel="stylesheet" href="../css/welcome.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/footer.css" />
  </head>
  <body>
    <div class="welcome-container">
      <div class="header">
        <div class="logo">
          <img
            src="..//images/logo2.png"
            alt="Logo Hiền Hợi"
          />
        </div>
        <nav class="auth-buttons">
          <?php
          // Kiểm tra xem người dùng đã đăng nhập chưa
          if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
              // Nếu đã đăng nhập, hiển thị tên người dùng và nút Đăng xuất
              echo '<span class="welcome-text">Xin chào, ' . htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) . '!</span>';
              echo '<button class="logout-button">Đăng xuất</button>';
          } else {
              // Nếu chưa đăng nhập, hiển thị nút Đăng nhập và Đăng ký
              echo '<button class="login-button">Đăng nhập</button>';
              echo '<button class="register-button">Đăng ký</button>';
          }
          ?>
        </nav>
      </div>
      <h1>CHÀO MỪNG ĐẾN VỚI BÚN CHẢ HIỀN HỢI! </br> </br> </h1>
      <button class="order-button">Gọi món ngay</button>
    </div>
    <script>
      // Sự kiện cho nút "Gọi món ngay"
      document.querySelector(".order-button").addEventListener("click", () => {
        // Nếu đã đăng nhập, chuyển hướng đến index.php (thực đơn)
        // Nếu chưa đăng nhập, chuyển hướng đến login.php
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            window.location.href = "index.php";
        <?php else: ?>
            window.location.href = "login.php";
        <?php endif; ?>
      });

      // Lấy các nút theo class để gán sự kiện
      const loginButton = document.querySelector(".login-button");
      const registerButton = document.querySelector(".register-button");
      const logoutButton = document.querySelector(".logout-button"); // Nút đăng xuất (nếu có)

      // Gán sự kiện cho nút Đăng nhập (chỉ khi nút này tồn tại)
      if (loginButton) {
        loginButton.addEventListener("click", () => {
          window.location.href = "login.php"; // Chú ý: đã đổi sang .php
        });
      }

      // Gán sự kiện cho nút Đăng ký (chỉ khi nút này tồn tại)
      if (registerButton) {
        registerButton.addEventListener("click", () => {
          window.location.href = "register.php"; // Chú ý: đã đổi sang .php
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
              window.location.href = 'welcome.php'; // Chuyển hướng về trang chào mừng sau khi đăng xuất
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