<?php
require_once 'admin/config.php';

// Fetch gallery items
$gallery = $conn->query(
    "SELECT * FROM gallery_items 
     WHERE status='active' 
     ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Gallery | The Raj Enterprises</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- EXISTING CSS (DO NOT CHANGE ORDER) -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="gallery_cards.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">The Raj Enterprises</div>
    <ul class="nav-links">
        <li><a href="index.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="gallery.php" class="active">Gallery</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>
    <div class="cart-icon" onclick="window.location.href='cart.php'" style="cursor:pointer; position: relative;">
        <i class="fas fa-shopping-cart"></i>
        <span id="cart-count">0</span>
    </div>
</nav>

<section class="contactus">

    <div class="page-heading">
        <h2>Photo Gallery</h2>
    </div>

    <div class="gallery_main_container">
        <div class="event_body">

            <?php if ($gallery && $gallery->num_rows > 0): ?>
                <?php while ($g = $gallery->fetch_assoc()): ?>
                <div class="event_card">
                    <img src="<?= htmlspecialchars($g['image_path']) ?>"
                         class="event_card-img"
                         alt="<?= htmlspecialchars($g['title']) ?>">

                    <div class="event_card_body">
                        <h3 class="event_card_title">
                            <?= htmlspecialchars($g['title']) ?>
                        </h3>

                        <?php if (!empty($g['subtitle'])): ?>
                            <p class="event_card_info">
                                <?= htmlspecialchars($g['subtitle']) ?>
                            </p>
                        <?php endif; ?>

                        <button class="event_card_btn">View</button>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="padding:20px;">No gallery images available.</p>
            <?php endif; ?>

        </div>
    </div>

</section>

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
