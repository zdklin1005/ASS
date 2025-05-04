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

    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $item = $stmt->fetch();

    if ($item) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$item['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
    }

    header("Location: cart.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #4a90e2;
            --accent-blue: #6ab7ff;
            --white: #ffffff;
            --text-dark: #1e1e2f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-dark);
        }

        h2 {
            text-align: center;
            color: var(--primary-blue);
            font-size: 2.5rem;
            margin-bottom: 5px;
        }

        .nav-links {
            text-align: center;
            margin-top: -10px;
            margin-bottom: 30px;
            font-size: 1rem;
            font-weight: 600;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--primary-blue);
            margin: 0 10px;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card h3 {
            color: var(--primary-blue);
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .product-card form {
            margin-top: auto;
        }

        .product-card button {
            padding: 10px 20px;
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .product-card button:hover {
            background-color: #66ccff;
        }
    </style>
</head>

<body>
    <h2>Products</h2>
    <br>

    <div class="nav-links">
        <a href="index.php">🏠 HomePage</a> |
        <a href="cart.php">🛒 View Cart</a>
    </div>

    <div class="products">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($product['name']) ?> - RM<?= $product['price'] ?></h3>
                <img src="https://web-aws-s3-bucket.s3.us-east-1.amazonaws.com/cloudAsgm/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= $product['name'] ?>">
                <p><?= htmlspecialchars($product['description']) ?></p>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
