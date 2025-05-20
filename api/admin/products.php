<?php
session_start();
require_once '../../config/db.php'; // Điều chỉnh đường dẫn đến db.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập trang này.']);
    http_response_code(403); // Forbidden
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            $stmt = $pdo->query("SELECT id, name, description, price, image_url FROM products ORDER BY id ASC");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($products);
            break;

        case 'POST': // Thêm sản phẩm mới
            $name = $data['name'] ?? '';
            $price = $data['price'] ?? 0;
            $description = $data['description'] ?? '';
            $image_url = $data['image_url'] ?? '';

            if (empty($name) || $price <= 0) {
                echo json_encode(['success' => false, 'message' => 'Tên và giá sản phẩm không được để trống.']);
                exit();
            }

            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url) VALUES (:name, :description, :price, :image_url)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':image_url', $image_url);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm sản phẩm.']);
            }
            break;

        case 'PUT': // Cập nhật sản phẩm
            $id = $data['id'] ?? 0;
            $name = $data['name'] ?? '';
            $price = $data['price'] ?? 0;
            $description = $data['description'] ?? '';
            $image_url = $data['image_url'] ?? '';

            if (empty($id) || empty($name) || $price <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID, tên và giá sản phẩm không được để trống.']);
                exit();
            }

            $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':image_url', $image_url);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật sản phẩm.']);
            }
            break;

        case 'DELETE': // Xóa sản phẩm
            $id = $data['id'] ?? 0;

            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'ID sản phẩm không được để trống.']);
                exit();
            }

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm.']);
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