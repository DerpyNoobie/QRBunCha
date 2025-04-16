document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Ngăn chặn hành động gửi form mặc định
        // Trong một ứng dụng thực tế, bạn sẽ gửi dữ liệu này đến máy chủ để xác thực.
        // Đối với phiên bản hiện tại, chúng ta sẽ giả định đăng nhập thành công và chuyển hướng đến trang index.
        window.location.href = '../html/index.html'; // Thay đổi đường dẫn thành trang index
    });
});