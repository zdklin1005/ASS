<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT products.name, products.price, cart.quantity 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

if(empty($items)){
    header("Location: cart.php");
    exit;
}

$grand_total = 0;
foreach ($items as $item) {
    $grand_total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Graduation Products Payment</title>
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
        padding: 30px;
        background-color: #f0f4ff;
        color: var(--text-dark);
    }

    h2 {
        text-align: center;
        color: var(--primary-blue);
        font-size: 2.5rem;
        margin-bottom: 30px;
    }

    form {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
        background-color: var(--white);
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: 600;
        color: var(--primary-blue);
    }

    select, input[type="text"], input[type="email"], input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
    }

    button {
        margin-top: 25px;
        width: 100%;
        padding: 12px;
        background-color: var(--primary-blue);
        color: white;
        font-size: 1.1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: 600;
    }

    button:hover {
        background-color: var(--accent-blue);
    }

    .dynamic {
        display: none;
    }
  </style>

  <script>
    function showFields(method) {
      const all = document.querySelectorAll('.dynamic');
      all.forEach(el => {
        el.style.display = 'none';
        el.querySelectorAll('input').forEach(input => input.required = false);
      });

      if (method) {
        const section = document.getElementById(method + 'Fields');
        if (section) {
          section.style.display = 'block';
          section.querySelectorAll('input').forEach(input => input.required = true);
        }
      }
    }

    window.addEventListener('DOMContentLoaded', () => {
        const expiryInput = document.querySelector('input[name="expiry"]');
        const errorSpan = document.getElementById('expiry-error');

        expiryInput.addEventListener('blur', function () {
          const value = this.value.trim();
          errorSpan.textContent = '';

          const parts = value.split('/');
          if (parts.length !== 2) {
            errorSpan.textContent = "Invalid format. Use MM/YY.";
            return;
          }

          const mm = parseInt(parts[0], 10);
          const yy = parseInt(parts[1], 10);

          if (isNaN(mm) || isNaN(yy) || mm < 1 || mm > 12) {
            errorSpan.textContent = "Invalid month. Use MM between 01 and 12.";
            return;
          }

          const inputDate = new Date(2000 + yy, mm); // first day of next month
          const today = new Date();
          const oneYearLater = new Date(today.getFullYear(), today.getMonth() + 12);

          if (inputDate <= oneYearLater) {
            errorSpan.textContent = "Expiry date must be at least 1 year in the future.";
          }
        });
      });
  </script>
</head>

<body>
  <h2>Graduation Products Payment</h2>

  <form method="POST" action="checkout.php">
      <label>Payment Method:</label>
      <select name="payment_method" onchange="showFields(this.value)" required>
          <option value="">--- Select Payment Method ---</option>
          <option value="credit">Credit/Debit Card</option>
          <option value="paypal">PayPal</option>
          <option value="tng">TNG E-wallet</option>
      </select>

      <div id="creditFields" class="dynamic">
          <label>Card Number:</label>
          <input type="text" name="card_number" pattern="\d{16}" placeholder="1111222233334444" maxlength="16">

          <label>Expiry Date (MM/YY):</label>
          <input type="text" name="expiry" pattern="(0[1-9]|1[0-2])\/\d{2}" placeholder="MM/YY">
          <span id="expiry-error" style="color: red;"></span>

          <label>CVV:</label>
          <input type="text" name="cvv" pattern="\d{3}" placeholder="123" maxlength="3">
      </div>

      <div id="paypalFields" class="dynamic">
          <label>PayPal Email:</label>
          <input type="email" name="paypal_email">
      </div>

      <div id="tngFields" class="dynamic">
          <label>TNG Phone Number:</label>
          <input type="text" name="tng_phone" pattern="^\d{3}-\d{7}$" maxlength="11" placeholder="E.g. 012-3456789">

          <label>TNG PIN:</label>
          <input type="password" name="tng_pin" pattern="\d{6}" maxlength="6">
      </div>

      <input type="hidden" name="grand_total" value="<?= number_format($grand_total, 2) ?>">

      <button type="submit">Pay RM<?= number_format($grand_total, 2) ?></button>
  </form>

</body>
</html>
