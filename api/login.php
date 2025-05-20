<?php
session_start(); // Bắt đầu phiên làm việc
require_once '../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên đăng nhập và mật khẩu.']);
        exit();
    }

    try {
        // Tìm người dùng theo tên đăng nhập
        $stmt = $pdo->prepare("SELECT id, full_name, username, password, is_admin FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Xác minh mật khẩu
            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công, lưu thông tin vào session
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['is_admin'] = (bool)$user['is_admin']; // Lưu trạng thái admin

                echo json_encode(['success' => true, 'message' => 'Đăng nhập thành công!', 'user' => [
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'is_admin' => (bool)$user['is_admin'] // Trả về cả trạng thái admin
                ]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Tên đăng nhập không tồn tại.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
?>