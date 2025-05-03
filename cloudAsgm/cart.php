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
            --success-green: #5cb85c;
            --success-dark: #45a049;
            --danger-red: #e74c3c;
            --white: #ffffff;
            --gray-bg: #f7f9fc;
            --text-dark: #1e1e2f;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
            color: var(--text-dark);
        }

        h2 {
            text-align: center;
            color: var(--primary-blue);
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .nav-links {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1rem;
            font-weight: 600;
        }

        .nav-links a {
            margin: 0 10px;
            text-decoration: none;
            color: var(--primary-blue);
            padding: 6px 10px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        th, td {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid #e1e1e1;
        }

        th {
            background-color: var(--primary-blue);
            color: white;
            font-size: 1.1rem;
        }

        td {
            font-size: 0.95rem;
        }

        input[type="number"] {
            width: 70px;
            padding: 6px 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 1rem;
        }

        .grand-total {
            margin-top: 20px;
            font-size: 1.2rem;
            text-align: right;
            font-weight: 600;
            color: var(--text-dark);
        }

        .actions {
            text-align: center;
            margin-top: 25px;
        }

        .actions button {
            padding: 12px 24px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0 10px;
        }

        .actions button[type="submit"] {
            background-color: var(--primary-blue);
            color: white;
        }

        .actions a button {
            background-color: var(--success-green);
            color: white;
        }

        .actions button:hover {
            background-color: var(--accent-blue);
        }

        .actions a button:hover {
            background-color: var(--success-dark);
        }

        a.remove-link {
            display: inline-block;
            padding: 6px 12px;
            color: white;
            background-color: var(--danger-red);
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        a.remove-link:hover {
            background-color: #c0392b;
        }

        @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr {
            margin-bottom: 20px;
        }

        td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        td::before {
            position: absolute;
            left: 10px;
            width: 45%;
            white-space: nowrap;
            font-weight: 600;
            text-align: left;
        }

        td:nth-of-type(1)::before { content: "Product"; }
        td:nth-of-type(2)::before { content: "Price"; }
        td:nth-of-type(3)::before { content: "Qty"; }
        td:nth-of-type(4)::before { content: "Total"; }
        td:nth-of-type(5)::before { content: "Action"; }

        .grand-total, .actions {
            text-align: center;
        }
    }
</style>

</head>
<body>

    <h2>Your Cart</h2><br>

    <div class="nav-links">
        <a href="products.php">🛍 Continue Shopping</a> |
        <a href="index.php">🏠 HomePage</a>
    </div>
    <br>

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
