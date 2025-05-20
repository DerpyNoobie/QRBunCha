<?php
// Cấu hình kết nối database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Mặc định của XAMPP/WAMP
define('DB_PASSWORD', '');     // Mặc định của XAMPP/WAMP
define('DB_NAME', 'buncha_db');

// Kết nối đến MySQL database
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Thiết lập chế độ báo lỗi PDO thành ngoại lệ
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Thiết lập charset
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    die("Lỗi: Không thể kết nối đến database. " . $e->getMessage());
}
?>