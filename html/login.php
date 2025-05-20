<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập - Bún Chả Hiền Hợi</title>
    <link rel="stylesheet" href="../css/login.css" />
  </head>
  <body>
    <div class="login-container">
      <h1>ĐĂNG NHẬP</h1>
      <form id="login-form">
        <div class="form-group">
          <label for="username">Tên đăng nhập:</label>
          <input type="text" id="username" name="username" required />
        </div>
        <div class="form-group">
          <label for="password">Mật khẩu:</label>
          <input type="password" id="password" name="password" required />
        </div>
        <button type="submit" class="login-button">Đăng nhập</button>
        <p class="signup-link">
          Chưa có tài khoản? <a href="register.php">Đăng ký</a>
        </p>
      </form>
    </div>
    <script src="../js/login.js"></script>
  </body>
</html>
