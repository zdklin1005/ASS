<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Graduation Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4ff;
            margin: 0;
            padding: 0;
            text-align: center;
            color: #333;
        }

        h1 {
            background-color: #4a69bd;
            color: white;
            padding: 40px 20px;
            margin: 0;
            font-size: 2.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .content {
            margin-top: 50px;
        }

        .content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        a {
            display: inline-block;
            margin: 10px 15px;
            padding: 12px 20px;
            text-decoration: none;
            background-color: #4a90e2; /* Soft blue */
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #357ab7; /* Darker blue for hover effect */
        }
    </style>
</head>

<body>
    <h1>🎓 Welcome to Gownzilla Shop 🎓</h1>

    <div class="content">
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Hello, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>!</p>
            <a href="products.php">🛒 View Products</a>
            <a href="cart.php">🧺 Cart</a>
            <a href="logout.php">🚪 Logout</a>
        <?php else: ?>
            <a href="login.php">🔐 Login</a>
            <a href="register.php">📝 Register</a>
        <?php endif; ?>
    </div>
</body>
</html>
