<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đơn hàng của bạn - Bún Chả Hiền Hợi</title>
    <link rel="stylesheet" href="../css/order_summary.css" />
  </head>
  <body>
    <div class="order-summary-container">
      <h1>CẢM ƠN BẠN ĐÃ GỌI MÓN!</h1>
      <div class="order-details">
        <h2>Đơn hàng của bạn</h2>
        <ul id="order-item-list"></ul>
        <p id="order-total">Tổng tiền:</p>
        <p id="order-status">Trạng thái đơn hàng: Đang chờ xác nhận</p>
        <button id="pay-now-button">Thanh toán trước</button>
      </div>
      <button id="back-to-menu-button">Gọi thêm món</button>
      <p class="thank-you-message">
        Chúng tôi sẽ chuẩn bị món ăn của bạn ngay!
      </p>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const orderItemList = document.getElementById("order-item-list");
        const orderTotalElement = document.getElementById("order-total");
        const payNowButton = document.getElementById("pay-now-button");
        const backToMenuButton = document.getElementById("back-to-menu-button");
        const currentOrder = localStorage.getItem("currentOrder");

        if (currentOrder) {
          const orderItems = JSON.parse(currentOrder);
          let total = 0;
          orderItems.forEach((item) => {
            const listItem = document.createElement("li");
            listItem.textContent = `${item.name} (x${
              item.quantity
            }) - ${item.price.toLocaleString()}đ`;
            orderItemList.appendChild(listItem);
            total += item.price * item.quantity;
          });
          orderTotalElement.textContent = `Tổng tiền: ${total.toLocaleString()}đ`;
        } else {
          orderItemList.innerHTML = "<p>Không có đơn hàng nào.</p>";
          orderTotalElement.textContent = `Tổng tiền: 0đ`;
          payNowButton.style.display = "none";
        }

        payNowButton.addEventListener("click", function () {
          window.location.href = "payment.php";
        });

        backToMenuButton.addEventListener("click", function () {
          window.location.href = "index.php";
        });
      });
    </script>
  </body>
</html>
