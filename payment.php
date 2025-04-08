<?php
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Mart - Payment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .payment-section { padding: 50px 0; }
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

        /* Styling for QR code section */
        .qr-code {
            text-align: center;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .qr-code img {
            max-width: 200px; /* Adjust the size of the QR code */
            height: auto;
        }

        .payment-info {
            text-align: center;
            margin-top: 30px;
        }

        .payment-info h4 {
            font-size: 24px;
            color: #28a745;
            font-weight: bold;
        }

        .payment-info p {
            font-size: 16px;
            color: #333;
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

<!-- Payment Section -->
<section class="payment-section container">
    <h2 class="text-center mb-5">Payment Information</h2>

    <div class="qr-code">
        <h4>Scan the QR Code to Pay</h4>
        <!-- Temporary QR code image -->
        <img src="/FRUIT_MART/images/qrcode.jpeg" alt="Payment QR Code">
    </div>

    <div class="payment-info">
        <h4>Total: ₹<?= number_format($total, 2) ?></h4>
        <p>Please scan the QR code above to complete the payment. Once the payment is confirmed, you will receive a confirmation email.</p>
        <a href="order_confirmation.php" class="btn btn-primary mt-3">Proceed to Order Confirmation</a>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
