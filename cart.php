<?php
session_start();

// Handle item removal
if (isset($_GET['remove'])) {
    $index = intval($_GET['remove']); // Get the index of the item to remove
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Remove the item from the cart
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
    }
    header("Location: cart.php"); // Redirect to reflect changes
    exit();
}

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['index'], $_POST['quantity'])) {
    $index = intval($_POST['index']);
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0 && isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['quantity'] = $quantity; // Update the quantity
    }
    header("Location: cart.php"); // Redirect to avoid form resubmission
    exit();
}

// Calculate total price and total items
$total = 0;
$totalItems = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
        $totalItems += $item['quantity'];
    }
}

// Function to set active class for navbar links
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
    <title>Fruit Mart - Your Cart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .cart-section {
            padding: 50px 0;
        }

        .cart-item {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            display: flex;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 20px;
        }

        .btn-remove {
            margin-left: auto;
        }

        .total-amount-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
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
    </style>
</head>

<body>
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

    <section class="cart-section container">
        <h2 class="text-center mb-5">Your Shopping Cart</h2>

        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <div class="cart-items">
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                            <div class="cart-item d-flex align-items-center">
                                <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>"> <!-- Displaying uploaded image -->
                                <div>
                                    <h5><?= htmlspecialchars($item['name']) ?></h5>
                                    <form action="cart.php" method="post" class="d-flex align-items-center">
                                        <input type="hidden" name="index" value="<?= $index ?>">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 60px;">
                                        <button type="submit" class="btn btn-warning ml-2">Update</button>
                                    </form>
                                    <p>Price: ₹<?= number_format($item['price'], 2) ?> each</p>
                                </div>
                                <a href="cart.php?remove=<?= $index ?>" class="btn btn-danger btn-remove">Remove</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center">Your cart is empty.</p>
                <?php endif; ?>
            </div>

            <!-- Total Amount Section -->
            <div class="col-md-4">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <div class="total-amount-section">
                        <h4>Total Amount</h4>
                        <h2 class="mt-3">₹<?= number_format($total, 2) ?></h2>
                        <h5>Total Items: <?= $totalItems ?></h5>
                        <h5>Products:</h5>
                        <ul>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <li><?= htmlspecialchars($item['name']) ?> - Quantity: <?= $item['quantity'] ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="btn btn-success" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
