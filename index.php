<?php
session_start(); // Start the session at the very top 

function setActive($page)
{
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php"); // Redirect after logout
    exit();
}



$seasonalOffer = "🍂 Enjoy 10% off on all fruits this Fall! 🍁";

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
    <title>Fruit Mart</title>
    <!-- Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Custom Inline Styles -->
    <style>
        body {
            background-color: #2a3240;
            color: #ffffff;
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
        }

        .hero-section h1 {
            font-size: 2.5rem;
            
            margin-bottom: 20px;
            color: #2a3240;
        }

        .hero-section p {
            font-size: 1.25rem;
            color: #2a3240;
        }

        .carousel-item {
            position: relative;
        }




        /* Centered Carousel Caption */
        .carousel-caption {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .carousel-caption h5 {
            font-size: 2.8rem;
            /* Decreased size for header by 5px */
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .carousel-caption p {
            font-size: 1.5rem;
            /* Increased size for subheader */
        }




        /* Circular Product Cards */
        .product-card {
            border: none;
            background-color: transparent;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            width: 200px;
            /* Fixed width for uniformity */
            margin: 10px;
            /* Margin for spacing */
        }

        .card-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            position: relative;
            transition: all 0.3s ease;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .card-image:hover img {
            transform: scale(1.1);
            /* Zoom effect on hover */
        }




        /* Buy Button */
        .btn-custom {
            position: absolute;
            bottom: 10px;
            /* Adjust position as needed */
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            /* White background */
            color: #ddba6b;
            /* Warm gold text */
            padding: 10px 20px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
            opacity: 0;
            /* Start as invisible */
            transition: opacity 0.3s ease;
            /* Smooth transition for visibility */
            border: 2px solid #ddba6b;
            /* Warm gold border */
        }

        .product-card:hover .btn-custom {
            opacity: 1;
            /* Show button on hover */
        }

        .card-body {
            padding: 0;
        }

        .card-title {
            font-weight: bold;
            color: #ddba6b;
            margin-bottom: 10px;
        }

        .card-text {
            color: #2a3240;
            margin-bottom: 20px;
        }

        footer {
            background-color: #2a3240;
            color: #ffffff;
        }



        /* Carousel image styles */
        .carousel-item img {
            height: 500px;
            /* Set a fixed height for carousel images */
            object-fit: cover;
            /* Ensure images cover the area */
        }

        .destination-section {
            padding: 50px 0;
            text-align: center;
        }

        .destination-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .destination {
            position: relative;
            margin: 10px;
            overflow: hidden;
            border-radius: 10px;
        }

        .destination img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: orange;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        .destination-label {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(255, 69, 0, 0.8);
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            border-radius: 5px;
        }


        /* Services Section Background */
        .services-section {
            background: linear-gradient(135deg, #f4d3a1 10%, #ddba6b 90%);
            padding: 70px 0;
            border-radius: 15px;
        }

        /* Section Title Styling */
        .section-title {
            color: #2a3240;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 40px;
            position: relative;
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



        /* Service Card Styling */
        .service-card {
            background-color: #2a3240;
            border-radius: 15px;
            padding: 30px;
            color: #ffffff;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }



        /* Service Icon Styling */
        .service-icon {
            background-color: #ddba6b;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }

        .service-card:hover .service-icon {
            transform: rotate(360deg);
        }

        .service-icon img {
            width: 50px;
            height: 50px;
            filter: brightness(0) invert(1);
        }

        /* Service Card Heading */
        .service-card h5 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 15px;
        }

        /* Service Card Description */
        .service-card p {
            font-size: 1rem;
            line-height: 1.6;
        }



        /* Reviews Section Styling */
        .reviews-section {
            background-color: #2a3240;
            color: #ffffff;
            padding: 70px 0;
            border-radius: 15px;
        }

        .reviews-section .section-title {
            color: #ddba6b;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
        }

        .reviews-section .section-title::after {
            content: "";
            width: 80px;
            height: 4px;
            background-color: #ddba6b;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .review-card {
            background-color: #ddba6b;
            border-radius: 10px;
            padding: 30px;
            /* Increase padding for a larger card size */
            color: #2a3240;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            /* Slightly stronger shadow */
            font-size: 1.1rem;
        }

        .review-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
        }

        .review-text {
            font-size: 1.2rem;
            /* Increase font size */
            line-height: 1.8;
            /* Adjust line height */
            margin-bottom: 20px;
        }

        .review-author {
            font-weight: bold;
            color: #2a3240;
            font-size: 1.1rem;
            /* Increase author font size */
            margin-bottom: 10px;
        }

        .review-rating {
            font-size: 1.5rem;
            /* Increase rating size */
            color: #ffa500;
        }


        .newsletter-signup {
            background-color: #f8f9fa;
            /* Light background */
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }

        .newsletter-signup h2 {
            color: #333;
            /* Darker text for header */
        }

        .newsletter-signup p {
            color: #555;
            /* Gray text for description */
        }

        .newsletter-signup input[type="email"] {
            padding: 10px;
            width: 70%;
            /* Width of the input field */
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .newsletter-signup button {
            padding: 10px 15px;
            background-color: #28a745;
            /* Green background */
            color: white;
            /* White text */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .newsletter-signup button:hover {
            background-color: #218838;
            /* Darker green on hover */
        }
    </style>




<style>
@import url('https://fonts.googleapis.com/css2?family=Lobster&display=swap');

.custom-typography {
    font-family: 'Lobster', cursive; /* Use the chosen decorative font */
    font-size: 5rem; /* Adjust size as per requirement */
    color: #fff; /* Use a vibrant pink color */
    text-shadow: 2px 2px 5px black; /* Shadow effect for depth */
    letter-spacing: 2px; /* Add spacing for emphasis */
}
</style>





</head>

<body>
    <!-- Navigation Bar -->
    <!-- Navigation Bar -->


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






    <!-- Slider Section -->
    <div id="fruitCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/FRUIT_MART/images/basket.jpg" class="d-block w-100" alt="Fresh Fruits">
                <div class="carousel-caption">
                <h1 class="custom-typography">Fresh Fruits</h1>
                <p>Experience the taste of nature's best.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1567306226416-28f0efdc88ce" class="d-block w-100" alt="Organic Fruits">
                <div class="carousel-caption">
                    <h1 class="custom-typography">Organic Fruits</h1>
                    <p>100% organic and fresh, straight from the farm.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="/FRUIT_MART/images/fruit_basket.jpg" class="d-block w-100" alt="Fruit Basket">
                <div class="carousel-caption">
                    <h1 class="custom-typography">Fruit Basket</h1>
                    <p>Variety of fruits packed just for you.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#fruitCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#fruitCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Welcome Section -->
    <section class="hero-section">
        <h1 >Welcome to Fruit Mart!</h1>
        <p>Your one-stop shop for the freshest fruits, delivered right to your door!</p>
    </section>
<br><br><br>
    <!-- Seasonal Offers -->
    <div class="alert alert-success text-center rounded shadow-sm" style="background-color: #ddba6b; color: #2a3240;">
        <strong>Seasonal Offer!</strong><br>
        <h3 class="font-weight-bold"><?= $seasonalOffer ?></h3>
        <small class="text-muted">Limited time only!</small>
    </div>


    <!-- Products Section -->
    <section id="products" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Featured Fruits</h2>
            <div class="row justify-content-center">
                <!-- Product 1 -->
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="https://images.unsplash.com/photo-1567306226416-28f0efdc88ce" class="img-fluid" alt="Apple">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Apple</h5>
                            <p class="card-text">Fresh, organic apples.</p>
                        </div>
                    </div>
                </div>
                <!-- Product 2 -->
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="/FRUIT_MART/images/bananas.jpg" class="img-fluid" alt="Banana">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Banana</h5>
                            <p class="card-text">Tasty, ripe bananas.</p>
                        </div>
                    </div>
                </div>
                <!-- Product 3 -->
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="/FRUIT_MART/images/orange1.jpg" class="img-fluid" alt="Orange">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Orange</h5>
                            <p class="card-text">Juicy, sweet oranges.</p>
                        </div>
                    </div>
                </div>
                <!-- Product 4 -->
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="/FRUIT_MART/images/strawberry.jpg" class="img-fluid" alt="Strawberry">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Strawberry</h5>
                            <p class="card-text">Fresh, organic strawberries.</p>
                        </div>
                    </div>
                </div>
                <!-- Additional Products (Hidden on Mobile) -->
                <div class="col-6 col-md-4 col-lg-3 mb-4 d-none d-md-block">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="/FRUIT_MART/images/grapes.jpg" class="img-fluid" alt="Grapes">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Grapes</h5>
                            <p class="card-text">Sweet, organic grapes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3 mb-4 d-none d-md-block">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="/FRUIT_MART/images/pineapple.jpg" class="img-fluid" alt="Pineapple">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Pineapple</h5>
                            <p class="card-text">Tropical, sweet pineapples.</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3 mb-4 d-none d-md-block">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="/FRUIT_MART/images/peach.jpg" class="img-fluid" alt="Peach">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Peach</h5>
                            <p class="card-text">Sweet, juicy peaches.</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-3 mb-4 d-none d-md-block">
                    <div class="product-card">
                        <div class="card-image">
                            <img src="/FRUIT_MART/images/kiwi1.jpg" class="img-fluid" alt="Kiwi">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Kiwi</h5>
                            <p class="card-text">Exotic, delicious kiwis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>









    <!-- Our Services Section -->
    <!-- Our Services Section -->
    <section class="services-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Our Services</h2>
            <div class="row">
                <!-- Home Delivery Card -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <img src="https://img.icons8.com/ios-filled/80/ffffff/delivery.png" alt="Home Delivery">
                        </div>
                        <h5>Home Delivery</h5>
                        <p>Get fresh fruits delivered right to your doorstep with our reliable home delivery service.</p>
                    </div>
                </div>
                <!-- Quality Assurance Card -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <img src="https://img.icons8.com/ios-filled/80/ffffff/checked-checkbox.png" alt="Quality Assurance">
                        </div>
                        <h5>Quality Assurance</h5>
                        <p>We ensure that all our fruits are fresh and of the highest quality, sourced directly from local farms.</p>
                    </div>
                </div>
                <!-- Customer Support Card -->
                <div class="col-md-4 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <img src="https://img.icons8.com/ios-filled/80/ffffff/customer-support.png" alt="Customer Support">
                        </div>
                        <h5>Customer Support</h5>
                        <p>Our dedicated customer support team is here to assist you with any inquiries and provide exceptional service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- newsletter-signup -->
    <div class="newsletter-signup">
        <h2>Subscribe to Our Newsletter</h2>
        <p>Get the latest updates on fruits, special offers, and healthy recipes!</p>
        <form action="subscribe.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>


    <!-- Reviews Section -->
    <section class="reviews-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 section-title">What Our Customer Say</h2>
            <div class="row">
                <!-- Review 1 -->
                <div class="col-md-4 mb-4">
                    <div class="review-card">
                        <p class="review-text">"Amazing quality fruits! Fresh and delicious. Will definitely order again!"</p>
                        <div class="review-author">- Pooja Naik</div>
                        <div class="review-rating">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <!-- Review 2 -->
                <div class="col-md-4 mb-4">
                    <div class="review-card">
                        <p class="review-text">"I love their home delivery service. Always on time and very professional."</p>
                        <div class="review-author">- Nisha Mahajan</div>
                        <div class="review-rating">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <!-- Review 3 -->
                <div class="col-md-4 mb-4">
                    <div class="review-card">
                        <p class="review-text">"Best place to get fresh, organic fruits. Highly recommended!"</p>
                        <div class="review-author">- Sumit Waikar </div>
                        <div class="review-rating">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- Footer -->
    <footer class="text-white text-center py-3">
        <p>&copy; 2024 Fruit Mart | Fresh Fruits Everyday</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>