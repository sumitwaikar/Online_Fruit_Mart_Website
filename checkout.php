<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Calculate total price and item details
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Store order details in session for later use
$_SESSION['order_details'] = [
    'total' => $total,
    'items' => $_SESSION['cart']
];

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
    <title>Fruit Mart - Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .checkout-section { padding: 50px 0; }
        .order-summary, .checkout-form { background-color: #ffffff; padding: 20px; border-radius: 10px; }
        
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

        /* Styling for order summary */
        .order-summary img {
            width: 100px; /* Increased size for better visibility */
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        
        .order-summary li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 16px;
        }

        /* Styling for total price */
        .order-summary h5 {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }

        /* Styling for checkout form */
        .checkout-form h4 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
<img src="/FRUIT_MART/images/logo1.png" alt="Fruit Mart Logo" width="40" height="40" class="d-inline-block align-top">
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

<!-- Checkout Section -->
<section class="checkout-section container">
    <h2 class="text-center mb-5">Proceed to Checkout</h2>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-6 order-summary">
            <h4>Order Summary</h4>
            <ul class="list-unstyled mt-3">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li>
                        <div class="d-flex align-items-center">
                            <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <span><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>) - ₹<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <h5 class="mt-4">Total: ₹<?= number_format($total, 2) ?></h5>
        </div>

        <!-- Checkout Form -->
        <div class="col-md-6 checkout-form">
            <h4>Billing Details</h4>
            <form action="confirm_order.php" method="post">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" required placeholder="Enter 10-digit phone number" maxlength="10" pattern="^\d{10}$" title="Phone number must be exactly 10 digits long">
                </div>
                <button type="submit" class="btn btn-success mt-3">Confirm Order</button>
            </form>
        </div>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
