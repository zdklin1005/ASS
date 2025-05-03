<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$payment_method = $_POST['payment_method'] ?? '';
$grand_total = $_POST['grand_total'] ?? 0;

if (!$payment_method || $grand_total <= 0) {
    die("Invalid payment details.");
}

$grand_total = floatval(str_replace(',', '', $grand_total));

// Load cart items with product ID
$stmt = $pdo->prepare("
    SELECT products.id AS product_id, products.name, products.price, cart.quantity 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

if (empty($items)) {
    die("Your cart is empty.");
}

// 1. Insert into orders table
$order_stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$order_stmt->execute([$user_id, $grand_total]);
$order_id = $pdo->lastInsertId();

// 2. Insert into order_items table using product_id
$order_item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($items as $item) {
    $order_item_stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
}

// 3. Insert into payment table
$payment_stmt = $pdo->prepare("INSERT INTO payment (order_id, payment_method, amount) VALUES (?, ?, ?)");
$payment_stmt->execute([$order_id, $payment_method, $grand_total]);

// 4. Clear cart
$clear_stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$clear_stmt->execute([$user_id]);

// 5. Fetch order items again for display (join with products)
$display_stmt = $pdo->prepare("
    SELECT p.name, oi.quantity, oi.price 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?");
$display_stmt->execute([$order_id]);
$ordered_items = $display_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Successful</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #4a90e2;
            --accent-blue: #6ab7ff;
            --white: #ffffff;
            --text-dark: #1e1e2f;
            --success-green: #5cb85c;
            --danger-red: #e74c3c;
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
            margin-bottom: 30px;
        }

        .nav-links {
            text-align: center;
            margin-bottom: 20px;
        }

        .nav-links a {
            margin: 0 10px;
            text-decoration: none;
            color: var(--primary-blue);
            font-weight: 600;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: var(--white);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: var(--primary-blue);
            color: white;
        }

        .actions {
            margin-top: 20px;
            text-align: center;
        }

        .actions button {
            padding: 12px 24px;
            margin: 5px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .actions button:hover {
            background-color: var(--accent-blue);
        }

        .actions a button {
            background-color: #5cb85c;
        }

        .actions a button:hover {
            background-color: #45a049;
        }

        .grand-total {
            text-align: right;
            font-size: 1.2rem;
            margin-top: 15px;
            color: var(--text-dark);
        }

        a.remove-link {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }

        a.remove-link:hover {
            text-decoration: underline;
        }

        img {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            display: block;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <h2>Payment Successful!</h2>
    <p><strong>Order ID: </strong> <?= $order_id ?></p>
    <p><strong>Payment Method: </strong> <?= htmlspecialchars(ucfirst($payment_method)) ?></p>

    <h3>🛒 Items Purchased</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price (RM)</th>
            <th>Subtotal (RM)</th>
        </tr>
        
        <?php foreach ($ordered_items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>RM <?= number_format($item['price'], 2) ?></td>
            <td>RM <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Total Paid (RM):</strong></td>
            <td><strong>RM <?= number_format($grand_total, 2) ?></strong></td>
        </tr>
    </table>

    <br>
    <div class="nav-links">
        <a href="products.php">🛍️️ Continue Shopping</a> |
        <a href="index.php">🏠 HomePage</a>
    </div>
</body>
</html>
