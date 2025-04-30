<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 删除项目
if (isset($_GET['remove'])) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['remove'], $user_id]);
    header("Location: cart.php");
    exit;
}

// 更新数量
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['quantity'] as $cart_id => $qty) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$qty, $cart_id, $user_id]);
    }
    header("Location: cart.php");
    exit;
}

// 加载购物车数据
$stmt = $pdo->prepare("
    SELECT cart.id as cart_id, products.*, cart.quantity 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Your Cart</h2>
<a href="products.php">🛒 Continue Shopping</a> | <a href="index.php">🏠 Home</a>
<form method="post">
    <table border="1" cellpadding="10">
        <tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>
        <?php $grand_total = 0; ?>
        <?php foreach ($items as $item): 
            $total = $item['price'] * $item['quantity'];
            $grand_total += $total;
        ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>RM<?= $item['price'] ?></td>
            <td>
                <input type="number" name="quantity[<?= $item['cart_id'] ?>]" value="<?= $item['quantity'] ?>" min="1">
            </td>
            <td>RM<?= number_format($total, 2) ?></td>
            <td><a href="?remove=<?= $item['cart_id'] ?>" onclick="return confirm('Remove item?')">Remove</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><strong>Grand Total: RM<?= number_format($grand_total, 2) ?></strong></p>
    <button type="submit">Update Cart</button>
    <a href="checkout.php"><button type="button">Proceed to Checkout</button></a>
</form>
</body>
</html>
