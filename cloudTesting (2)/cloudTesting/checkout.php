<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 清空购物车
$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Checkout Successful ✅</h2>
<p>Thank you for your purchase! Your order has been processed.</p>
<a href="products.php">🛍 Continue Shopping</a> | <a href="index.php">🏠 Back to Home</a>
</body>
</html>
