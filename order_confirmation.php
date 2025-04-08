<?php
session_start();
require 'db.php'; // Ensure you have a valid database connection in this file

// Check if the order details exist in the session
if (!isset($_SESSION['order_details'])) {
    header("Location: checkout.php");
    exit();
}

// Get order details from session
$orderDetails = $_SESSION['order_details'];
$total = $orderDetails['total'];
$items = $orderDetails['items'];

// Function to set active page in navbar
function setActive($page)
{
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}

// Add order to the 'sales' table
try {
    // Generate a unique order number
    $orderNumber = uniqid("FMART_");

    // Serialize the items array for storage in the database
    $serializedItems = json_encode($items);

    // Get the current date and time
    $dateTime = date("Y-m-d H:i:s");

    // Insert data into the sales table
    $stmt = $pdo->prepare("INSERT INTO sales (order_number, items, total_amount, date_time) VALUES (?, ?, ?, ?)");
    $stmt->execute([$orderNumber, $serializedItems, $total, $dateTime]);

    // Clear order details from the session
    unset($_SESSION['order_details']);

    // Clear cart as well
    unset($_SESSION['cart']);

} catch (Exception $e) {
    die("Error recording the order: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Mart - Order Confirmation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .order-confirmation-section { padding: 50px 0; }
        .navbar {
            background-color: #2a3240;
        }
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: #ddba6b;
        }
        .navbar img {
            width: 130px;
            height: 60px;
        }
        .navbar .nav-link:hover {
            color: #ffffff;
        }
        .confirmation-message {
            text-align: center;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .confirmation-message h2 {
            font-size: 28px;
            color: #28a745;
            font-weight: bold;
        }
        .confirmation-message p {
            font-size: 18px;
            color: #333;
        }
        .order-details {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order-details h4 {
            font-size: 24px;
            color: #28a745;
            font-weight: bold;
        }
        .order-details ul {
            list-style-type: none;
            padding: 0;
        }
        .order-details li {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">
        <img src="/FRUIT_MART/images/logo1.png" alt="Fruit Mart Logo" width="40" height="40" class="d-inline-block align-top">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item <?= setActive('index.php'); ?>">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item <?= setActive('about.php'); ?>">
                <a class="nav-link" href="about.php">About Us</a>
            </li>
            <?php if (isset($_SESSION['username'])): ?>
                <li class="nav-item <?= setActive('shop.php'); ?>">
                    <a class="nav-link" href="shop.php">Fruits</a>
                </li>
                <li class="nav-item <?= setActive('cart.php'); ?>">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_SESSION['username'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="?logout=true">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Order Confirmation Section -->
<section class="order-confirmation-section container">
    <div class="confirmation-message">
        <h2>Thank You for Your Order!</h2>
        <p>Your order number is: <strong><?= htmlspecialchars($orderNumber) ?></strong></p>
        <p>Your order has been successfully placed. You will receive a confirmation email with your order details shortly.</p>
    </div>

    <div class="order-details mt-5">
        <h4>Order Summary</h4>
        <ul>
            <?php foreach ($items as $item): ?>
                <li>
                    <strong><?= htmlspecialchars($item['name']) ?></strong> (x<?= $item['quantity'] ?>) - ₹<?= number_format($item['price'] * $item['quantity'], 2) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <h5 class="mt-4">Total: ₹<?= number_format($total, 2) ?></h5>
    </div>

    <div class="text-center mt-4">
        <a href="shop.php?clear_cart=true" class="btn btn-primary">Continue Shopping</a>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
