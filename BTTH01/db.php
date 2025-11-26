<?php
/**
 *   - Kết nối tới CSDL MySQL bằng PDO
 *   - Được sử dụng chung cho tất cả bài
 */

$host     = "localhost";
$dbname   = "web2025_k65"; 
$username = "root";     
$password = "";            

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("❌ Không thể kết nối CSDL: " . $e->getMessage());
}
