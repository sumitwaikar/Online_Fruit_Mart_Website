<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.location.href='about.php';</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.location.href='about.php';</script>";
        exit();
    }

    // Database configuration
    $host = 'localhost'; 
    $db = 'fruitmart';  
    $user = 'root';      
    $pass = '';          

    // Create database connection
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert feedback
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='about.php';</script>";
    } else {
        error_log("Database error: " . $stmt->error);
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: about.php");
    exit();
}
