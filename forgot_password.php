<?php
session_start();
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        

        if ($result->num_rows > 0) {
            // Here, you would generate a password reset link and send it via email
            // For demonstration, we'll just show a success message.
            // Ideally, you would also need to implement the email sending logic
            $success_message = "A password reset link has been sent to your email address.";
        } else {
            $error_message = "No account found with that email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('https://images.unsplash.com/photo-1607083201664-03bbf4d6c8a2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDF8fGZydWl0c3xlbnwwfHx8fDE2MzEwMTIyNjU&ixlib=rb-1.2.1&q=80&w=1080') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #2a3240;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .form-group label {
            color: #2a3240;
            font-weight: 600;
        }
        .alert-danger, .alert-success {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password?</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Enter your email address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
        <p class="mt-3 text-center"><a href="login.php" class="signup-link">Back to Login</a></p>
    </div>
</body>
</html>
