<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove an item from the cart
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart'][$_GET['remove']])) {
    $removeIndex = $_GET['remove'];
    unset($_SESSION['cart'][$removeIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the cart array
    header("Location: add_to_cart.php");
    exit();
}

// Add more quantity to an existing item
if (isset($_GET['add_more']) && is_numeric($_GET['add_more']) && isset($_SESSION['cart'][$_GET['add_more']])) {
    $addIndex = $_GET['add_more'];
    $_SESSION['cart'][$addIndex]['quantity']++;
    header("Location: add_to_cart.php");
    exit();
}

// Calculate the total price and total items
$totalPrice = 0;
$totalItems = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
    $totalItems += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Mart - Your Cart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .cart-section { padding: 50px 0; }
        .cart-item { background-color: #ffffff; border-radius: 10px; padding: 20px; margin: 20px; display: flex; align-items: center; }
        .cart-item img { width: 100px; height: 100px; border-radius: 10px; object-fit: cover; margin-right: 20px; }
        .btn-remove { margin-left: auto; }
        .total-amount-section { background-color: #ffffff; padding: 20px; border-radius: 10px; margin-top: 20px; }
        .navbar { background-color: #2a3240; }
        .navbar .navbar-brand, .navbar .nav-link { color: #ddba6b; }
        .navbar img { width: 130px; height: 60px; }
        .navbar .nav-link:hover { color: #ffffff; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">
            <img src="/FRUIT_MART/images/logo.png" alt="Fruit Mart Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.php">Fruits</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart <span class="badge badge-danger"><?= $totalItems ?></span></a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Cart Section -->
    <section class="cart-section container">
        <h2 class="text-center mb-5">Your Shopping Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                    <div class="cart-item">
                        <img src="/FRUIT_MART/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div>
                            <h5><?= htmlspecialchars($item['name']) ?></h5>
                            <p>Quantity: <?= $item['quantity'] ?></p>
                            <p>Price: ₹<?= number_format($item['price'], 2) ?> each</p>
                        </div>
                        <a href="add_to_cart.php?add_more=<?= $index ?>" class="btn btn-secondary ml-3">Add More</a>
                        <a href="add_to_cart.php?remove=<?= $index ?>" class="btn btn-danger btn-remove">Remove</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="total-amount-section text-center">
                <h4>Total Amount</h4>
                <h2 class="mt-3">₹<?= number_format($totalPrice, 2) ?></h2>
                <h5>Total Items: <?= $totalItems ?></h5>
                <a href="checkout.php" class="btn btn-success mt-3">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p class="text-center">Your cart is empty. <a href="shop.php">Start shopping now!</a></p>
        <?php endif; ?>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
