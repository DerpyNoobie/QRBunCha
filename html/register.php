<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký - Bún Chả Hiền Hợi</title>
    <link rel="stylesheet" href="../css/register.css" />
  </head>
  <body>
    <div class="register-container">
      <h1>ĐĂNG KÝ</h1>
      <form id="register-form">
        <div class="form-group">
          <label for="name">Họ và tên:</label>
          <input type="text" id="name" name="name" required />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="new-username">Tên đăng nhập:</label>
          <input type="text" id="new-username" name="new-username" required />
        </div>
        <div class="form-group">
          <label for="new-password">Mật khẩu:</label>
          <input
            type="password"
            id="new-password"
            name="new-password"
            required
          />
        </div>
        <div class="form-group">
          <label for="confirm-password">Xác nhận mật khẩu:</label>
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            required
          />
        </div>
        <button type="submit" class="register-button">Đăng ký</button>
        <p class="login-link">
          Đã có tài khoản? <a href="login.php">Đăng nhập</a>
        </p>
      </form>
    </div>
    <script src="../js/register.js"></script>
  </body>
</html>
