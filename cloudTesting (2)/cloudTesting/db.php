<?php
$host = 'localhost';
$db   = 'graduation_store';
$user = 'root';
$pass = ''; // XAMPP 默认密码为空

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("连接失败: " . $e->getMessage());
}
?>
