<?php
require_once '../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT id, name, description, price, image_url FROM products ORDER BY id ASC");
        $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($menu_items);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi lấy dữ liệu thực đơn: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
?>