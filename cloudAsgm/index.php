<?php
session_start();
require 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gownzilla Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('images/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #f0f8ff;
            text-align: center;
        }

        /* Banner Section */
        .banner {
            position: relative;
            width: 100%;
            height: 400px;
            background: rgba(0, 70, 140, 0.6); /* fallback if no overlay */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .banner::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 70, 140, 0.6);
            z-index: 0;
        }

        .banner h1 {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
            gap: 10px;
        }

        .auth-buttons {
            position: relative;
            z-index: 1;
            margin-top: 20px;
        }

        .auth-buttons a {
            display: inline-block;
            margin: 10px 15px;
            padding: 14px 25px;
            text-decoration: none;
            background-color: rgba(33, 120, 200, 0.9);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .auth-buttons a:hover {
            background-color: rgba(20, 100, 170, 0.9);
            transform: translateY(-5px);
        }

        /* Main Content */
        .content {
            margin-top: 50px;
            background-image: url('images/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 40px;
            border-radius: 8px;
            margin-bottom: 50px;
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        /* Product Section */
        h2 {
            color: #66ccff;
            margin-top: 40px;
            font-size: 2rem;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 250px;
            min-height: 450px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            color: #2471a3;
            font-size: 1.3rem;
            font-weight: bold;
        }

        .product-card p {
            flex-grow: 1;
            font-size: 0.95rem;
            color: #333;
        }

        .product-card button {
            padding: 12px 18px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .product-card button:hover {
            background-color: #357ab7;
            transform: translateY(-2px);
        }

    </style>
</head>

<body>
    <div class="banner">
        <h1>🎓 Welcome to Gownzilla Shop 🎓</h1>
        
        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="products.php">🛒 Products</a>
                <a href="cart.php">🧺 Cart</a>
                <a href="logout.php">🚪 Logout</a>
            <?php else: ?>
                <a href="login.php">🔐 Login</a>
                <a href="register.php">📝 Register</a>
            <?php endif; ?>
        </div>
    </div>

    <h2>Our Products</h2>

    <div class="products">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($product['name']) ?> - RM<?= $product['price'] ?></h3>
                <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= $product['name'] ?>">
                <p><?= htmlspecialchars($product['description']) ?></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post" action="products.php">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.php">Add to Cart</a></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
