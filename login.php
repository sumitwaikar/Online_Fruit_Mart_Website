<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .form-control {
            height: 50px;
            font-size: 16px;
            color: #2a3240;
            border: 1px solid #ddd;
            transition: box-shadow 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 8px rgba(221, 186, 107, 0.6);
            border-color: #ddba6b;
            outline: none;
        }
        .btn-primary {
            background-color: #ddba6b;
            border: none;
            font-size: 18px;
            font-weight: bold;
            padding: 12px 20px;
            margin-top: 10px;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #c5a56a;
            transform: scale(1.02);
        }
        .alert-danger {
            text-align: center;
            font-weight: bold;
        }
        p {
            text-align: center;
            margin-top: 15px;
        }
        .signup-link {
            color: #ddba6b;
            font-weight: bold;
            text-decoration: none;
        }
        .signup-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    
<div class="container">
    <h2>Welcome Back to Daily Fruit!</h2>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
            <label class="form-check-label" for="rememberMe">Remember Me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p><a href="forgot_password.php" class="signup-link">Forgot Password?</a></p>
    <p>Don't have an account? <a href="signup.php" class="signup-link">Sign up here</a></p>
    
    <!-- Back Button -->
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-secondary">Go to Home</a>
    </div>
</div>


</body>
</html>
