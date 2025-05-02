<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['remove'])) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['remove'], $user_id]);
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['quantity'] as $cart_id => $qty) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$qty, $cart_id, $user_id]);
    }
    header("Location: cart.php");
    exit;
}

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
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
            background: #f0f4ff;
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

        input[type="number"] {
            width: 60px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            text-align: center;
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
    </style>
</head>
<body>

    <h2>Your Cart</h2>

    <div class="nav-links">
        <a href="products.php">🛍 Continue Shopping</a> |
        <a href="index.php">🏠 HomePage</a>
    </div>

    <form method="post">
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
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
                <td>
                    <a href="?remove=<?= $item['cart_id'] ?>" class="remove-link" onclick="return confirm('Remove item?')">Remove</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="grand-total">
            <strong>Grand Total: RM<?= number_format($grand_total, 2) ?></strong>
        </div>

        <div class="actions">
            <?php if(!empty($item)): ?>
                <button type="submit">Update Cart</button>
                <a href="payment.php"><button type="button">Proceed to Payment</button></a>
            <?php else: ?>
                <p style="color:red; font-weight: bold;">Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </form>

</body>
</html>
