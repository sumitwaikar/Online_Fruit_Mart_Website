<?php
session_start();

// Database connection
$host = 'localhost'; // your database host
$dbname = 'fruitmart'; // your database name
$username = 'root'; // your database username
$password = ''; // your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit; // Exit if the connection fails
}

// Get the filter option
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Modify the query based on the filter
switch ($filter) {
    case 'daily':
        $sql = "SELECT DATE(date_time) AS sale_date, SUM(total_amount) AS total_sales 
                FROM sales 
                WHERE DATE(date_time) = CURDATE() 
                GROUP BY sale_date 
                ORDER BY sale_date ASC";
        break;
    case 'weekly':
        $sql = "SELECT DATE(date_time) AS sale_date, SUM(total_amount) AS total_sales 
                FROM sales 
                WHERE YEARWEEK(date_time, 1) = YEARWEEK(CURDATE(), 1) 
                GROUP BY sale_date 
                ORDER BY sale_date ASC";
        break;
    case 'monthly':
        $sql = "SELECT DATE(date_time) AS sale_date, SUM(total_amount) AS total_sales 
                FROM sales 
                WHERE MONTH(date_time) = MONTH(CURDATE()) AND YEAR(date_time) = YEAR(CURDATE()) 
                GROUP BY sale_date 
                ORDER BY sale_date ASC";
        break;
    default:
        $sql = "SELECT DATE(date_time) AS sale_date, SUM(total_amount) AS total_sales 
                FROM sales 
                GROUP BY sale_date 
                ORDER BY sale_date ASC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute();
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the graph
$dates = [];
$sales = [];

foreach ($salesData as $row) {
    $dates[] = $row['sale_date'];
    $sales[] = (float)$row['total_sales'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Graph - Fruit Mart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .container { padding: 30px; }
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
            text-decoration: none;
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

    <a href="logout.php" class="nav-link">Logout</a> <!-- Logout Link -->
</div>

<!-- Main Content -->
<div class="content">
    <h2 class="text-center">Daily Sales Graph</h2>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="sales_graph.php" class="form-inline">
            <label for="filter" class="mr-2">Filter:</label>
            <select name="filter" id="filter" class="form-control mr-2">
                <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All</option>
                <option value="daily" <?= $filter == 'daily' ? 'selected' : '' ?>>Daily</option>
                <option value="weekly" <?= $filter == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                <option value="monthly" <?= $filter == 'monthly' ? 'selected' : '' ?>>Monthly</option>
            </select>
            <button type="submit" class="btn btn-secondary">Apply Filter</button>
        </form>
    </div>

    <canvas id="salesChart" width="400" height="200"></canvas>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Total Sales (₹)',
                data: <?php echo json_encode($sales); ?>,
                backgroundColor: '#ddba6a',
                borderColor: '#2a3240',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the database connection
$pdo = null;
?>
