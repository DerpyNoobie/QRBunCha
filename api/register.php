<?php
require_once '../config/db.php';

header('Content-Type: application/json'); // Trả về JSON
header('Access-Control-Allow-Origin: *'); // Cho phép CORS (trong môi trường dev)
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $full_name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $username = $data['newUsername'] ?? '';
    $password = $data['newPassword'] ?? '';

    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.']);
        exit();
    }

    // Hash mật khẩu trước khi lưu vào database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Kiểm tra username hoặc email đã tồn tại chưa
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Tên đăng nhập hoặc Email đã tồn tại.']);
            exit();
        }

        // Chèn người dùng mới vào database
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, username, password) VALUES (:full_name, :email, :username, :password)");
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Đăng ký thành công! Vui lòng đăng nhập.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Đăng ký thất bại. Vui lòng thử lại.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
?>