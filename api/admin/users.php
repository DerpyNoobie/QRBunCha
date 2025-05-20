<?php
session_start();
require_once '../../config/db.php'; // Điều chỉnh đường dẫn đến db.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Hàm kiểm tra quyền admin
function isAdmin() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Xử lý yêu cầu OPTIONS (preflight request cho CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

// Bảo vệ tất cả các hành động trong file này
if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập.']);
    http_response_code(403); // Forbidden
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            // Lấy tất cả người dùng (không bao gồm mật khẩu)
            $stmt = $pdo->query("SELECT id, username, full_name, email, phone, address, is_admin FROM users ORDER BY id ASC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
            break;

        case 'DELETE': // Xóa người dùng
            $user_id_to_delete = $data['id'] ?? 0;
            $current_admin_id = $_SESSION['user_id'] ?? 0;

            if (empty($user_id_to_delete)) {
                echo json_encode(['success' => false, 'message' => 'ID người dùng không được để trống.']);
                exit();
            }

            // Ngăn chặn admin tự xóa chính mình
            if ($user_id_to_delete == $current_admin_id) {
                echo json_encode(['success' => false, 'message' => 'Bạn không thể tự xóa tài khoản của mình.']);
                exit();
            }

            // Ngăn chặn admin xóa người dùng khác nếu người đó cũng là admin
            $stmt_check_admin = $pdo->prepare("SELECT is_admin FROM users WHERE id = :id");
            $stmt_check_admin->bindParam(':id', $user_id_to_delete);
            $stmt_check_admin->execute();
            $target_user = $stmt_check_admin->fetch(PDO::FETCH_ASSOC);

            if ($target_user && $target_user['is_admin'] == 1) {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa một tài khoản quản trị viên khác.']);
                exit();
            }


            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $user_id_to_delete);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Xóa người dùng thành công.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa người dùng.']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không được hỗ trợ.']);
            http_response_code(405); // Method Not Allowed
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
    http_response_code(500); // Internal Server Error
}
?>