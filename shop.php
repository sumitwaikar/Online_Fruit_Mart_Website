<?php
session_start();
require 'db.php';

// Fetch products from the database
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

// Initialize the cart in the session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
    $productIndex = $_POST['product_index'];
    $product = $products[$productIndex];

    // Check if the item already exists in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['name'] === $product['name']) {
            $cartItem['quantity'] += 1; // Always add 1 item
            $found = true;
            break;
        }
    }

    if (!$found) {
        $product['quantity'] = 1; // Default quantity to 1
        $_SESSION['cart'][] = $product;
    }

    // Set a success message and redirect
    $_SESSION['message'] = $found ? "Quantity updated!" : "Added to cart!";
    header("Location: shop.php");
    exit();
}

// Function to set active class for navbar links
function setActive($page)
{
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}

// Calculate total items in the cart
$totalItems = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalItems += $item['quantity'];
    }
}

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the homepage
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit Mart - Shop Now</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2a3240;
            color: #ffffff;
        }

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

        /* Badge style for cart count */
        .badge-cart {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 0.75rem;
            position: relative;
            top: -10px;
            left: -5px;
        }

        /* Product card styles */
        .product-card {
            background-color: #ddba6b;
            color: #2a3240;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .product-card img {
            width: 100%;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product-card h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .btn-add {
            background-color: #2a3240;
            color: #ffffff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            transition: 0.3s ease;
        }

        .btn-add:hover {
            background-color: #ddba6b;
            color: #2a3240;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
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
                        <a class="nav-link" href="cart.php">Cart
                            <?php if ($totalItems > 0): ?>
                                <span class="badge badge-danger"><?= $totalItems; ?></span>
                            <?php endif; ?>
                        </a>
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

    <!-- Success Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['message'] ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Shop Section -->
    <section class="container py-5">
        <h2 class="text-center mb-5">Shop Fresh Fruits</h2>
        <div class="row">
            <?php foreach ($products as $index => $product): ?>
                <div class="col-md-3">
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <h5 class="mt-3"><?= htmlspecialchars($product['name']) ?></h5>
                        <p>Price: ₹<?= number_format($product['price'], 2) ?></p> <!-- Display price directly in INR -->
                        <form method="POST">
                            <input type="hidden" name="product_index" value="<?= $index ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-add">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
