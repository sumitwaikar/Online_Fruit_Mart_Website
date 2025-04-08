<?php
session_start();
require 'db.php'; // Ensure this file connects to your database

// Check if the product ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_product.php'); // Redirect if no ID is provided
    exit;
}

$productId = $_GET['id'];

// Fetch the product details from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: manage_product.php'); // Redirect if the product doesn't exist
    exit;
}

// Handle form submission for editing the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image']; // Assuming image URL or path is handled

    // Update the product in the database
    $updateStmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    $updateStmt->execute([$name, $price, $image, $productId]);

    header('Location: manage_product.php'); // Redirect after successful update
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Panel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; }
        .sidebar {
            min-width: 200px;
            background-color: #2a3240;
            color: #ddba6a;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }
        .sidebar .nav-link {
            color: #ddba6a;
            margin: 10px 0;
        }
        .sidebar .nav-link:hover {
            color: #ffffff;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <a href="admin_panel.php" style="text-decoration: none; color: #ddba6b"><h4>Admin Panel</h4></a>
        <a href="view_sales.php" class="nav-link">View Sales</a>
        <a href="sales_graph.php" class="nav-link">Sales Graph</a>
        <a href="add_product.php" class="nav-link">Add Product</a>
        <a href="manage_product.php" class="nav-link">Manage Products</a>
        <a href="manage_users.php" class="nav-link">Manage Users</a>
        <a href="manage_feedback.php" class="nav-link">Feedback</a>
        <a href="view_feedback.php" class="nav-link">View Feedback</a>

        <a href="logout.php" class="nav-link">Logout</a>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <h3>Edit Product</h3>
        <form method="POST">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" name="price" id="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="text" name="image" id="image" class="form-control" value="<?= htmlspecialchars($product['image']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
