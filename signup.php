<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        echo "<div class='alert alert-danger'>Passwords do not match. Please try again.</div>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists
        $checkQuery = "SELECT * FROM users WHERE username = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "<div class='alert alert-warning'>Username is already taken. Please choose another.</div>";
        } else {
            // Insert new user
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashedPassword);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Signup successful! <a href='login.php'>Click here to login.</a></div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
