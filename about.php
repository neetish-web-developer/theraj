<?php
require_once 'admin/config.php';

// Fetch about page image
$aboutImg = "uploads/neetish.jpg"; // fallback

$res = $conn->query("SELECT image_path FROM site_pages WHERE page_name='about' LIMIT 1");
if ($res && $res->num_rows === 1) {
    $row = $res->fetch_assoc();
    $aboutImg = $row['image_path'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | The Raj Enterprises</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contact_style.css"> 
    <link rel="stylesheet" href="style_about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="main_body">

<nav class="navbar">
    <div class="logo">The Raj Enterprises</div>
    <ul class="nav-links">
        <li><a href="index.php">Shop</a></li>
        <li><a href="about.php" class="active">About</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>
    <div class="cart-icon" onclick="window.location.href='cart.php'" style="cursor:pointer; position: relative;">
        <i class="fas fa-shopping-cart"></i>
        <span id="cart-count">0</span>
    </div>
</nav>

<section class="contactus">
    <div class="title">
        <h2>Our Heritage & Vision</h2>
    </div>
</section>

<div class="container">
    <section class="about">
        <div class="about-image">
            <!-- 🔥 IMAGE FROM DATABASE -->
            <img src="<?= htmlspecialchars($aboutImg) ?>" alt="Director of Raj Enterprises">
            <div class="director-name">
                <h3>Naresh Kumar Vishwakarma</h3>
                <p>Director, The Raj Enterprises</p>
            </div>
        </div>

        <div class="about-content">
            <div class="heading-style-index">
                <h1>The Legacy of Bhendra</h1>
            </div>

            <p>
                Choosing The Raj Enterprises means choosing a legacy that dates back centuries.
                Our base in <strong>Bhendra, Bokaro (828401)</strong>, is the "Blacksmith Village"
                of Jharkhand, famous worldwide for its traditional iron craftsmanship.
            </p>
            <br>
            <p>
                Historically renowned for manufacturing high-quality swords and precision tools,
                our local craftsmen possess an intuitive understanding of metal durability.
            </p>
            <br>
            <p>
                Quality is our cornerstone. Every item is inspected to meet professional standards
                expected by corporate and educational institutions.
            </p>
            <br>
            <p>
                We deliver Bhendra’s excellence to your doorstep.
                For official inquiries: <strong>shop@rajlogic.in</strong>.
            </p>
        </div>
    </section>
</div>

<div class="container2">
    <h1>Why Choose Us?</h1>
    <ul class="aim">
        <li class="about_li">Master Craftsmanship from Bhendra (828401).</li>
        <li class="about_li">Premium Quality Metal and Crystal Mementos.</li>
        <li class="about_li">Heritage-inspired designs for Modern Awards.</li>
        <li class="about_li">Direct Support to Jharkhand's Traditional Artisans.</li>
        <li class="about_li">Precision Engineering with a Personal Touch.</li>
        <li class="about_li">Timely Delivery across the Region.</li>
        <li class="about_li">Customization options for Corporate Branding.</li>
        <li class="about_li">Highly Durable and Long-lasting Products.</li>
        <li class="about_li">Ethical Sourcing of Raw Materials.</li>
        <li class="about_li">Dedicated Customer Support via shop@rajlogic.in.</li>
    </ul>
</div>

<footer>
    <p>&copy; 2018 The Raj Enterprises. All Rights Reserved. | Bhendra, Bokaro</p>
    <p>A Unit Of Bhendra Iron Cluster</p>
</footer>

<script>
function updateNavbarCart() {
    let cart = JSON.parse(localStorage.getItem('rajCart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cart-count');
    if (badge) badge.innerText = totalItems;
}
window.addEventListener('load', updateNavbarCart);
</script>

</body>
</html>
