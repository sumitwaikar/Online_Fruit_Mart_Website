<?php
session_start();
require 'db.php'; // Ensure you have this file to connect to your database

// Redirect to login if not logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch all products from the database
$productQuery = "SELECT * FROM products ORDER BY id DESC"; // Adjust according to your database schema
$productResult = mysqli_query($conn, $productQuery);

if (!$productResult) {
    die("Database query failed: " . mysqli_error($conn));
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $deleteQuery = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        header("Location: manage_product.php"); // Redirect to manage products after deletion
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting product: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Panel</title>
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
        <a href="view_feedback.php" class="nav-link">View Feedback</a>

        <a href="logout.php" class="nav-link">Logout</a>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <h3>Manage Products</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price (₹)</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($productResult)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><img src="/FRUIT_MART/images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="width: 50px; height: auto;"></td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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
