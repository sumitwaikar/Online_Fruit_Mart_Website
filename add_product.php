<?php
session_start();
require 'db.php'; // Ensure you have this file to connect to your database

// Redirect to login if not logged in as admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure form data is present and assign them to variables
    $product_name = $_POST['product_name'] ?? '';
    $product_price_inr = $_POST['product_price'] ?? 0;

    // Ensure the product name and price are not empty
    if (empty($product_name) || $product_price_inr <= 0) {
        echo "<div class='alert alert-danger'>Product name and price are required.</div>";
        exit;
    }

    // Handle the image upload
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/uploads/"; // Full path to uploads directory
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create uploads directory if it doesn't exist
        }

        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File is not an image.</div>";
            exit;
        }

        // Check file size (limit to 2MB)
        if ($_FILES["product_image"]["size"] > 2000000) {
            echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
            exit;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
            exit;
        }

        // Attempt to upload the image
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // Insert product into the database with INR price
            $stmt = $pdo->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
            $stmt->execute([$product_name, $product_price_inr, 'uploads/' . basename($_FILES["product_image"]["name"])]);
            
            // Set success message in session
            $_SESSION['product_added'] = true;
            header("Location: add_product.php"); // Redirect to same page to trigger pop-up
            exit();
        } else {
            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No file uploaded or an error occurred during upload.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            margin: 0;
        }
        
        .sidebar {
            min-width: 220px;
            background-color: #2a3240;
            color: #ddba6a;
            padding: 30px 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: #ddba6a;
            margin: 15px 0;
            font-size: 18px;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            text-decoration: none;
        }

        .content {
            margin-left: 240px;
            padding: 40px 30px;
            flex: 1;
            min-height: 100vh;
        }

        .content h3 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .content form {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content .form-group label {
            font-size: 16px;
            color: #333;
        }

        .content .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 16px;
        }

        .content .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        .content button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .content button:hover {
            background-color: #218838;
        }

        .alert {
            margin-top: 20px;
            padding: 10px;
        }

        /* Success pop-up styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #28a745;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            font-size: 18px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeInOut 2s forwards;
        }

        /* Fade-in and fade-out animation */
        @keyframes fadeInOut {
            0% { opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin_panel.php" style="text-decoration: none; color: #ddba6b ;"><h4>Admin Panel</h4></a>
        <a href="view_sales.php" class="nav-link">View Sales</a>
        <a href="sales_graph.php" class="nav-link">Sales Graph</a>
        <a href="add_product.php" class="nav-link">Add Product</a>
        <a href="manage_product.php" class="nav-link">Manage Products</a>
        <a href="manage_users.php" class="nav-link">Manage Users</a>
        <a href="view_feedback.php" class="nav-link">View Feedback</a>
        <a href="logout.php" class="nav-link">Logout</a> <!-- Logout Link -->
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <h3>Add New Product</h3>
        
        <!-- Pop-up message for product added -->
        <?php if (isset($_SESSION['product_added'])): ?>
            <div id="successPopup" class="popup">Product added successfully!</div>
            <?php unset($_SESSION['product_added']); ?>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="product_price">Product Price (INR):</label>
                <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required>
            </div>
            <div class="form-group">
                <label for="product_image">Product Image:</label>
                <input type="file" class="form-control" id="product_image" name="product_image" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to trigger the pop-up animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successPopup = document.getElementById('successPopup');
            if (successPopup) {
                setTimeout(() => {
                    successPopup.style.display = 'block';
                }, 500); // Delay to allow page to load

                setTimeout(() => {
                    successPopup.style.display = 'none';
                }, 2500); // Hide after 2 seconds
            }
        });
    </script>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
