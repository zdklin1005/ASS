<?php
require 'db.php';
session_start();

$registerMessage = "";
if(isset($_SESSION['register_success'])){
    $registerMessage = $_SESSION['register_success'];
    unset($_SESSION['register_success']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: index.php");
        exit;
    } else {
        $loginError = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #4a90e2;
            --accent-blue: #6ab7ff;
            --background: linear-gradient(135deg, #f8f9fa, #e0e7ff);
            --white: #ffffff;
            --text-dark: #1e1e2f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f4ff;
            color: var(--text-dark);
            text-align: center;
        }

        h2 {
            color: var(--primary-blue);
            font-size: 2.5rem;
            margin-top: 50px;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 40px 20px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .container button {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .container button:hover {
            background-color: #66ccff;
        }

        .container a {
            display: block;
            margin-top: 15px;
            font-size: 1rem;
            color: var(--primary-blue);
            text-decoration: none;
        }

        .container a:hover {
            text-decoration: underline;
        }

        .message {
            color: green;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <h2>Login</h2>

    <div class="container">
        <?php if(!empty($registerMessage)): ?>
            <p class="message"><?=htmlspecialchars($registerMessage)?></p>
        <?php endif; ?>

        <?php if (!empty($loginError)): ?>
            <p class="error"><?=htmlspecialchars($loginError)?></p>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <a href="index.php">Back to HomePage</a>
    </div>

</body>
</html>
