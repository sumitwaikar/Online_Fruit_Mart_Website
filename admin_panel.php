<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Fruit Mart</title>
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
            margin-top:18%;
            display: flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            flex: 1;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Admin Panel</h4>
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
        <h1>Welcome to the Admin Panel</h1>
        <p>Select an option from the sidebar to manage the store.</p>
        

    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
