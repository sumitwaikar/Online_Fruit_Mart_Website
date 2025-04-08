<?php
session_start();
$orderNumber = $_GET['order'] ?? '';

// Ensure order number is provided
if (empty($orderNumber)) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            text-align: center;
            padding: 50px;
        }
        .thank-you-message {
            margin-top: 50px;
            font-size: 24px;
            color: #28a745;
        }
        .order-number {
            font-size: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Thank You for Your Order!</h1>
    <p class="thank-you-message">Your order has been placed successfully.</p>
    <p class="order-number">Order Number: <strong><?= htmlspecialchars($orderNumber) ?></strong></p>
    <a href="index.php" class="btn btn-primary mt-3">Return to Home</a>
</body>
</html>
