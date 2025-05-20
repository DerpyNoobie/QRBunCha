<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đặt hàng.']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $cart_items = $data['cart_items'] ?? [];
    $total_amount = $data['total_amount'] ?? 0;

    if (empty($cart_items) || $total_amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống hoặc tổng tiền không hợp lệ.']);
        exit();
    }

    try {
        $pdo->beginTransaction(); // Bắt đầu giao dịch

        // 1. Chèn vào bảng orders
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (:user_id, :total_amount, 'pending')");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->execute();

        $order_id = $pdo->lastInsertId(); // Lấy ID của đơn hàng vừa tạo

        // 2. Chèn vào bảng order_items
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_order) VALUES (:order_id, :product_id, :quantity, :price_at_order)");
        foreach ($cart_items as $item) {
            $stmt_item->bindParam(':order_id', $order_id);
            $stmt_item->bindParam(':product_id', $item['id']);
            $stmt_item->bindParam(':quantity', $item['quantity']);
            $stmt_item->bindParam(':price_at_order', $item['price']);
            $stmt_item->execute();
        }

        $pdo->commit(); // Hoàn tất giao dịch
        echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công!', 'order_id' => $order_id]);

    } catch (PDOException $e) {
        $pdo->rollBack(); // Hoàn tác giao dịch nếu có lỗi
        echo json_encode(['success' => false, 'message' => 'Lỗi khi đặt hàng: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
?>