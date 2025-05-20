// js/login.js
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');

    loginForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('../api/login.php', { // Đường dẫn tới file PHP
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                })
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                localStorage.setItem('currentUser', JSON.stringify(result.user)); // Vẫn lưu thông tin user

                // Kiểm tra nếu người dùng là admin
                if (result.user.is_admin) {
                    window.location.href = '../admin/index.php'; // Chuyển hướng đến trang admin
                } else {
                    window.location.href = '../html/index.php'; // Chuyển hướng đến trang chính cho người dùng thường
                }
            } else {
                alert('Đăng nhập thất bại: ' + result.message);
            }
        } catch (error) {
            console.error('Lỗi khi gửi yêu cầu đăng nhập:', error);
            alert('Có lỗi xảy ra trong quá trình đăng nhập. Vui lòng thử lại.');
        }
    });
});