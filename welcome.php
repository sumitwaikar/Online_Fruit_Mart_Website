<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Regenerate session ID for security
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container text-center mt-5">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Thank you for logging in. Enjoy shopping at Fruit Mart!</p>
    <div class="mt-4">
        <a href="index.php" class="btn btn-primary">Go to Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</body>
</html>
