<?php
session_start();
require 'db.php'; // Ensure you have this file to connect to your database

// Get the filter option
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Set the query based on the filter
switch ($filter) {
    case 'daily':
        $salesQuery = "SELECT * FROM sales WHERE DATE(date_time) = CURDATE() ORDER BY date_time DESC";
        $summaryQuery = "SELECT COUNT(*) as totalOrders, SUM(total_amount) as totalRevenue FROM sales WHERE DATE(date_time) = CURDATE()";
        break;
    case 'weekly':
        $salesQuery = "SELECT * FROM sales WHERE YEARWEEK(date_time, 1) = YEARWEEK(CURDATE(), 1) ORDER BY date_time DESC";
        $summaryQuery = "SELECT COUNT(*) as totalOrders, SUM(total_amount) as totalRevenue FROM sales WHERE YEARWEEK(date_time, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'monthly':
        $salesQuery = "SELECT * FROM sales WHERE MONTH(date_time) = MONTH(CURDATE()) AND YEAR(date_time) = YEAR(CURDATE()) ORDER BY date_time DESC";
        $summaryQuery = "SELECT COUNT(*) as totalOrders, SUM(total_amount) as totalRevenue FROM sales WHERE MONTH(date_time) = MONTH(CURDATE()) AND YEAR(date_time) = YEAR(CURDATE())";
        break;
    default:
        $salesQuery = "SELECT * FROM sales ORDER BY date_time DESC"; // Default to all sales
        $summaryQuery = "SELECT COUNT(*) as totalOrders, SUM(total_amount) as totalRevenue FROM sales";
        break;
}

// Fetch sales records
$salesResult = mysqli_query($conn, $salesQuery);
if (!$salesResult) {
    die("Database query failed: " . mysqli_error($conn));
}

// Fetch summary data
$summaryResult = mysqli_query($conn, $summaryQuery);
$summary = mysqli_fetch_assoc($summaryResult);

// Pagination logic
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Adjust the query for pagination
$pagedQuery = $salesQuery . " LIMIT $limit OFFSET $offset";
$pagedResult = mysqli_query($conn, $pagedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sales - Admin Panel</title>
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
        <a href="view_feedback.php" class="nav-link">View Feedback</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <h3>Sales Records</h3>
        <button class="btn btn-primary mb-3 print-button" onclick="printReport()">Print Sales Report</button>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="view_sales.php" class="form-inline">
                <label for="filter" class="mr-2">Filter:</label>
                <select name="filter" id="filter" class="form-control mr-2">
                    <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All</option>
                    <option value="daily" <?= $filter == 'daily' ? 'selected' : '' ?>>Daily</option>
                    <option value="weekly" <?= $filter == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                    <option value="monthly" <?= $filter == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                </select>
                <button type="submit" class="btn btn-secondary">Apply</button>
            </form>
        </div>

        <!-- Summary Section -->
        <div class="alert alert-info">
            <strong>Summary:</strong> Total Orders: <?= $summary['totalOrders'] ?> | Total Revenue: ₹<?= number_format($summary['totalRevenue'], 2) ?>
        </div>

        <!-- Sales Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Items</th>
                    <th>Total Amount (₹)</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
    <?php if (mysqli_num_rows($pagedResult) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($pagedResult)): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_number']) ?></td>
                <td>
                    <?php 
                    // Decode the JSON items field
                    $items = json_decode($row['items'], true);
                    if ($items) {
                        $itemDetails = array_map(function($item) {
                            return htmlspecialchars($item['name']) . ' (x' . htmlspecialchars($item['quantity']) . ')';
                        }, $items);
                        echo implode(', ', $itemDetails); // Combine all item details into a single string
                    } else {
                        echo 'N/A'; // Fallback if decoding fails
                    }
                    ?>
                </td>
                <td><?= number_format($row['total_amount'], 2) ?></td>
                <td><?= date('d-m-Y H:i', strtotime($row['date_time'])) ?></td>
                <td>
                    <!-- Delete Button -->
                    <a href="delete_sales.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">No sales records found for the selected filter.</td>
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
