<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Graduation Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🎓 Welcome to TARUMT Graduation Shop</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Hello, <?= $_SESSION['user_name'] ?>!</p>
        <a href="products.php">🛍 View Products</a> |
        <a href="cart.php">🛒 Cart</a> |
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a> |
        <a href="register.php">Register</a>
    <?php endif; ?>
</body>
</html>
