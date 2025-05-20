<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh toán - Bún Chả Hiền Hợi</title>
    <link rel="stylesheet" href="../css/payment.css" />
  </head>
  <body>
    <div class="payment-container">
      <h1>Thanh toán chuyển khoản</h1>
      <div class="qr-code-container">
        <img
          src="../images/QR.jpg"
          alt="Mã QR chuyển khoản"
          class="qr-code-image"
        />
        <p class="instruction">Quét mã QR này để chuyển khoản thanh toán.</p>
      </div>
      <button id="back-button">Quay lại đơn hàng</button>
    </div>

    <script>
      document
        .getElementById("back-button")
        .addEventListener("click", function () {
          window.location.href = "order_summary.php";
        });
    </script>
  </body>
</html>
