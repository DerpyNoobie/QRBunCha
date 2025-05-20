<?php
session_start(); // Bắt đầu phiên làm việc để có thể hủy nó

header('Content-Type: application/json'); // Trả về JSON
header('Access-Control-Allow-Origin: *'); // Cho phép CORS (trong môi trường dev)
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hủy tất cả các biến session
    $_SESSION = array();

    // Xóa cookie session (nếu có)
    if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
$params["path"], $params["domain"],
$params["secure"], $params["httponly"]
);
}

    // Cuối cùng, hủy session
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Bạn đã đăng xuất thành công.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
?>