<?php
// Database connection parameters
$host = 'localhost'; // Your database host
$dbname = 'fruitmart'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Initialize message variable
$message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and sanitize it
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

    // Validate the email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email already exists in the database
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM subscribers WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Email already exists
            $message = "This email address is already subscribed.";
        } else {
            // Prepare the SQL statement to insert the email
            $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (:email)");
            // Bind the email parameter
            $stmt->bindParam(':email', $email);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for subscribing!";
            } else {
                $message = "Sorry, there was an error. Please try again later.";
            }
        }
    } else {
        $message = "Invalid email address.";
    }
} else {
    $message = "Invalid request method.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2a3240; /* Dark background */
            color: #ffffff; /* White text */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ddba6b; /* Light background for the container */
            color: #2a3240; /* Dark text */
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px; /* Set a fixed width */
        }

        .container h2 {
            margin: 0 0 10px;
        }

        .container p {
            margin: 0 0 20px;
        }

        .container button {
            background-color: #2a3240; /* Dark button background */
            color: #ffffff; /* White button text */
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container button:hover {
            background-color: #1c2028; /* Darker shade on hover */
        }

        .home-button {
            margin-top: 15px;
            text-decoration: none;
            background-color: #2a3240; /* Dark background for home button */
            color: #ffffff; /* White text for contrast */
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .home-button:hover {
            background-color: #1c2028; /* Darker shade on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Subscribe to Our Newsletter</h2>
    <p><?php echo $message; ?></p>
    <a class="home-button" href="index.php">Home</a>
</div>

</body>
</html>
