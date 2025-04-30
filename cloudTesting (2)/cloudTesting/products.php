<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $user_id    = $_SESSION['user_id'];

    // Check if product already in cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $item = $stmt->fetch();

    if ($item) {
        // Increase quantity
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$item['id']]);
    } else {
        // Insert new item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
    }

    header("Location: cart.php");
    exit;
}

// Load products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Products</h2>
<a href="index.php">🏠 Home</a> | <a href="cart.php">🛒 View Cart</a>
<hr>
<?php foreach ($products as $product): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
    <h3><?= htmlspecialchars($product['name']) ?> - RM<?= $product['price'] ?></h3>
    <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= $product['name'] ?>" style="max-width:150px;"><br>
    <p><?= htmlspecialchars($product['description']) ?></p>
    <form method="post">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <button type="submit">Add to Cart</button>
    </form>
</div>

<?php endforeach; ?>
</body>
</html>
