<?php
session_start();
require 'db.php'; // Ensure you have this file to connect to your database

// Check if an ID is passed and sanitize it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete query
    $deleteQuery = "DELETE FROM sales WHERE id = ?";

    // Prepare statement
    $stmt = mysqli_prepare($conn, $deleteQuery);

    if ($stmt) {
        // Bind the ID parameter
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // If successful, redirect to view_sales.php
            header("Location: view_sales.php");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    // If no valid ID is passed
    echo "Invalid record ID.";
}

// Close the database connection
mysqli_close($conn);
?>
