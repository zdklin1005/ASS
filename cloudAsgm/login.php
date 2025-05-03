<?php
require 'db.php';
session_start();

$registerMessage = "";
if (isset($_SESSION['register_success'])) {
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
    <title>Login - Gownzilla</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.7), rgba(74, 144, 226, 0.7));
            z-index: -1; /* Ensures the overlay is behind the content */
        }

        .login-box {
            background: #fff;
            padding: 60px; /* Increased padding */
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px; /* Increased max-width */
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            color: #4a90e2;
        }

        .login-box form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input[type="email"],
        input[type="password"] {
            padding: 14px 16px; /* Increased padding for inputs */
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        button {
            background-color: #4a90e2;
            color: white;
            padding: 14px; /* Increased padding for the button */
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #357ec7;
        }

        .link {
            margin-top: 20px;
            font-size: 0.95rem;
        }

        .link a {
            color: #4a90e2;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .message {
            color: green;
            font-size: 0.95rem;
        }

        .error {
            color: red;
            font-size: 0.95rem;
        }

        .icon {
            font-size: 32px;
            margin-bottom: 10px;
            color: #4a90e2;
        }

        .login-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .login-icon {
            font-size: 30px;
            color: #4a90e2;
        }

        .login-text {
            font-size: 30px;
            font-weight: 600;
            color: #4a90e2;
        }

    </style>

</head>
<body>
    <div class="overlay"></div> 

    <div class="login-box">
        <div class="login-header">
            <span class="login-icon">🔐</span>
            <span class="login-text">Login</span>
        </div>

        <?php if (!empty($registerMessage)): ?>
            <div class="message"><?= htmlspecialchars($registerMessage) ?></div>
        <?php endif; ?>

        <?php if (!empty($loginError)): ?>
            <div class="error"><?= htmlspecialchars($loginError) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="link">
            <a href="index.php">🏠 HomePage</a>
        </div>
    </div>
</body>
</html>
