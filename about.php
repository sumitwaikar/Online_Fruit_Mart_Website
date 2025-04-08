<?php
session_start(); // Start the session at the very top 

function setActive($page) {
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php"); // Redirect after logout
    exit();
}

// Initialize $totalItems
$totalItems = 0; // Default value
if (isset($_SESSION['cart'])) {
    $totalItems = count($_SESSION['cart']); // Calculate total items if cart exists
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Fruit Mart</title>
    <!-- Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            background-color: #f9f9f9;
            color: #333;
        }

        .navbar {
            background-color: #2a3240;
        }
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: #ddba6b;
        }
        .navbar img {
            width: 130px;
            height: 60px;
        }
        .navbar .nav-link:hover {
            color: #ffffff;
        }
        
        .hero-section {
            background-color: #ddba6b;
            padding: 50px 0;
            text-align: center;
            color: #fff;
        }
        
        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 2rem;
            margin: 40px 0 20px;
            text-align: center;
            position: relative;
            color: #ddba6b;
        }
        
        .section-title::after {
            content: "";
            width: 80px;
            height: 4px;
            background-color: #ddba6b;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .content-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 60px;
            flex-wrap: wrap;
        }

        .content-section img {
            max-width: 50%; /* Adjust the width to 50% */
            height: auto;
            border-radius: 10px;
            margin: 20px;
        }

        .content-text {
            max-width: 50%; /* Adjust the width to 50% */
            padding: 20px;
        }

        aside {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 60px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        aside h4 {
            color: #ddba6b;
            margin-bottom: 15px;
        }

        footer {
            background-color: #2a3240;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            .content-section {
                flex-direction: column; /* Stack elements on smaller screens */
            }

            .content-section img,
            .content-text {
                max-width: 100%; /* Full width on smaller screens */
            }
        }

        form .form-group label {
    color: #333;
    font-weight: bold;
}
form button {
    background-color: #ddba6b;
    border: none;
}
form button:hover {
    background-color: #2a3240;
    color: #fff;
}

    </style>

    
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">
            <img src="/FRUIT_MART/images/logo1.png" alt="Fruit Mart Logo" width="40" height="40" class="d-inline-block align-top">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?= setActive('index.php'); ?>">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item <?= setActive('about.php'); ?>">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>

                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item <?= setActive('shop.php'); ?>">
                        <a class="nav-link" href="shop.php">Fruits</a>
                    </li>
                    <li class="nav-item <?= setActive('cart.php'); ?>">
                        <a class="nav-link" href="cart.php">Cart 
                            <?php if ($totalItems > 0): ?>
                                <span class="badge badge-danger"><?= $totalItems; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['username'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?logout=true">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1>About Us</h1>
        <p>Your trusted source for fresh and quality fruits.</p>
    </section>

    <!-- Main Content Section -->
    <div class="container my-5">
        <section>
            <h2 class="section-title">Who We Are</h2>
            <div class="content-section">
                <img src="/FRUIT_MART/images/why_us.jpg" alt="Who We Are">
                <div class="content-text">
                    <p>At Fruit Mart, we are dedicated to providing high-quality, fresh fruits sourced from local farmers. Our mission is to bring the best nature has to offer straight to your table.</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="section-title">Our Quality</h2>
            <div class="content-section">
                <img src="/FRUIT_MART/images/fruits.jpg" alt="Our Quality">
                <div class="content-text">
                    <p>We prioritize quality and freshness. Our fruits are harvested at their peak ripeness and are guaranteed to be pesticide-free. Experience the taste of fresh, organic fruits like never before!</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="section-title">Our Services</h2>
            <div class="content-section">
                <img src="/FRUIT_MART/images/packing.webp" alt="Our Services">
                <div class="content-text">
                    <p>We offer a variety of services to enhance your shopping experience:</p>
                    <ul>
                        <li><strong>Home Delivery:</strong> Convenient delivery options to get your fresh fruits delivered to your door.</li>
                        <li><strong>Subscription Service:</strong> Regular deliveries of seasonal fruits tailored to your taste.</li>
                        <li><strong>Custom Orders:</strong> Create personalized fruit baskets for special occasions.</li>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <section>
    <h2 class="section-title">We Value Your Feedback</h2>
    <div class="content-section">
        <div class="content-text">
            <p>Your thoughts and suggestions help us improve. Please take a moment to share your feedback with us!</p>
            <form action="feedback.php" method="POST" class="mt-4">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="message">Your Feedback</label>
                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your feedback here" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
            </form>
        </div>
    </div>
</section>


    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Fruit Mart | Fresh Fruits Everyday</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.2.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
