// js/register.js
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');

    registerForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const newUsername = document.getElementById('new-username').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (newPassword !== confirmPassword) {
            alert('Mật khẩu và xác nhận mật khẩu không khớp!');
            return;
        }

        try {
            const response = await fetch('../api/register.php', { // Đường dẫn tới file PHP
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    newUsername: newUsername,
                    newPassword: newPassword
                })
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = '../html/login.php'; // Chuyển hướng về trang đăng nhập
            } else {
                alert('Đăng ký thất bại: ' + result.message);
            }
        } catch (error) {
            console.error('Lỗi khi gửi yêu cầu đăng ký:', error);
            alert('Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.');
        }
    });
});