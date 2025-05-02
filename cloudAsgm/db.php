<?php
$host = 'webdb.cp2bvw7iajei.us-east-1.rds.amazonaws.com';
$db   = 'WebDB';
$user = 'kykx';
$pass = 'qwerty.12345';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("连接失败: " . $e->getMessage());
}
?>
