<?php
session_start();
require 'db.php'; // Ensure you have this file to connect to your database

// Get the filter option
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Set the query based on the filter
switch ($filter) {
    case 'daily':
        $feedbackQuery = "SELECT * FROM feedback WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC";
        break;
    case 'weekly':
        $feedbackQuery = "SELECT * FROM feedback WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) ORDER BY created_at DESC";
        break;
    case 'monthly':
        $feedbackQuery = "SELECT * FROM feedback WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) ORDER BY created_at DESC";
        break;
    default:
        $feedbackQuery = "SELECT * FROM feedback ORDER BY created_at DESC"; // Default to all feedback
        break;
}

$feedbackResult = mysqli_query($conn, $feedbackQuery);

if (!$feedbackResult) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback - Admin Panel</title>
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
        .filter-section {
            margin-bottom: 20px;
        }
        
        /* Hide sidebar and print button during print */
        @media print {
            .sidebar {
                display: none;
            }
            .content {
                margin-left: 0; /* Adjust content margin when sidebar is hidden */
            }
            .print-button {
                display: none; /* Hide the print button */
            }
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
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
        <a href="view_feedback.php" class="nav-link">View Feedback</a> <!-- New Link -->
        <a href="logout.php" class="nav-link">Logout</a> <!-- Logout Link -->
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <h3>Feedback Records</h3>
        <button class="btn btn-primary mb-3 print-button" onclick="printReport()">Print Feedback Report</button>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="view_feedback.php" class="form-inline">
                <label for="filter" class="mr-2">Filter:</label>
                <select name="filter" id="filter" class="form-control mr-2">
                    <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All</option>
                    <option value="daily" <?= $filter == 'daily' ? 'selected' : '' ?>>Daily</option>
                    <option value="weekly" <?= $filter == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                    <option value="monthly" <?= $filter == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                </select>
                <button type="submit" class="btn btn-secondary">Apply </button>
            </form>
        </div>

        <!-- Feedback Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($feedbackResult) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($feedbackResult)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['message']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No feedback records found for the selected filter.</td>
                    </tr>
                <?php endif; ?>
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
