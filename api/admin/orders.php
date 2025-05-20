<?php
session_start();
require_once '../../config/db.php'; // Điều chỉnh đường dẫn đến db.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
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
            // Lấy tất cả đơn hàng cùng với thông tin người dùng
            $stmt = $pdo->query("
                SELECT
                    o.id,
                    o.total_amount,
                    o.order_date,
                    o.status,
                    u.username,
                    u.full_name
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.order_date DESC
            ");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($orders);
            break;

        case 'PUT': // Cập nhật trạng thái đơn hàng
            $order_id = $data['id'] ?? 0;
            $new_status = $data['status'] ?? '';

            // Danh sách các trạng thái hợp lệ
            $valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

            if (empty($order_id) || !in_array($new_status, $valid_statuses)) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu cập nhật không hợp lệ.']);
                exit();
            }

            $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $new_status);
            $stmt->bindParam(':id', $order_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái đơn hàng.']);
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