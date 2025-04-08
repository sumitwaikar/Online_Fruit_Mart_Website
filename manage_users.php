<?php
session_start();
require 'db.php'; // Ensure you have this file to connect to your database

// Fetch users from the database
$userQuery = "SELECT * FROM users ORDER BY id ASC"; // Adjust according to your database schema
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die("Database query failed: " . mysqli_error($conn));
}

// Add User Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $insertQuery)) {
        header("Location: manage_users.php"); // Redirect to avoid form resubmission
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete User Logic
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $deleteQuery = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $deleteQuery);
    header("Location: manage_users.php"); // Redirect after deletion
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
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
        <h3>Manage Users</h3>
        
        <!-- Add User Form -->
        <form action="" method="post" class="mb-4">
            <h5>Add User</h5>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
        </form>

        <!-- Users Table -->
        <h5>Existing Users</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <a href="manage_users.php?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
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
