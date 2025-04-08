<?php
session_start();
require 'db.php'; // Ensure this file creates a PDO connection in $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve admin user from the database
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    // Verify password and set session if valid
    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_panel.php');
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ddba6b, #2a3240);
            color: #333;
        }
        .login-container {
            background: #2a3240; /* Dark background for the card */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), inset 0 2px 6px rgba(255, 255, 255, 0.1);
            max-width: 400px;
            text-align: center;
            color: #ddba6b;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        h2 {
            color: #ddba6b;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="password"] {
            padding: 14px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            color: #ddba6b;
            background: #333;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            background-color: #444;
            transform: scale(1.02);
            outline: none;
            border: 1px solid #ddba6b;
        }
        button {
            padding: 14px;
            background-color: #ddba6b;
            color: #2a3240;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #c5a56a;
            transform: scale(1.05);
        }
        .error-message {
            color: #ff5a5f;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
