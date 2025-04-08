<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Store order details in session for use on the payment page
$_SESSION['order_details'] = [
    'total' => $total,
    'items' => $_SESSION['cart']
];

// Redirect to the payment page after confirming the order
header("Location: payment.php");
exit();
?>
