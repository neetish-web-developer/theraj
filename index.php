<?php
// =======================
// DB CONNECTION
// =======================
$conn = new mysqli("localhost", "root", "", "theraj");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// =======================
// FETCH PRODUCTS
// =======================
$products = $conn->query("SELECT * FROM products WHERE status='active' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>The Raj Enterprises | Premium Mementos</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="contact_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
    gap: 25px;
    padding: 20px 0;
}

.product-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    text-align: center;
    transition: transform .3s ease;
}
.product-card:hover { transform: translateY(-5px); }

.image-container {
    position: relative;
    width: 100%;
    height: 250px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 15px;
    background: #fff;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    position: absolute;
    top: 0; left: 0;
    opacity: 0;
    transition: opacity .6s ease-in-out;
}

.image-container img.active {
    opacity: 1;
    z-index: 1;
}

.cart-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
}

.qty-input {
    width: 60px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
}

.add-to-cart {
    background: #28a745;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
}

#cart-count {
    background: #e74c3c;
    color: #fff;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 11px;
    position: absolute;
    top: -5px;
    right: -10px;
}
</style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
    <div class="logo">The Raj Enterprises</div>
    <ul class="nav-links">
        <li><a href="index.php" class="active">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>
    <div class="cart-icon" onclick="location.href='cart.php'" style="cursor:pointer;position:relative;">
        <i class="fas fa-shopping-cart"></i>
        <span id="cart-count">0</span>
    </div>
</nav>

<!-- ================= MAIN ================= -->
<main class="shop-container">

<div class="title" style="text-align:center;padding:40px 0;">
    <h2>Our Premium Collection</h2>
</div>

<div class="product-grid">

<?php while($p = $products->fetch_assoc()): ?>
<?php
$pid = $p['id'];
$imgs = $conn->query("SELECT * FROM product_images WHERE product_id=$pid ORDER BY sort_order");
?>

<div class="product-card"
     data-id="<?= $p['id'] ?>"
     data-name="<?= htmlspecialchars($p['name']) ?>"
     data-price="<?= $p['price'] ?>"
     onmouseenter="startSlide(this)"
     onmouseleave="stopSlide(this)">

    <div class="image-container">
        <?php
        $first = true;
        while($img = $imgs->fetch_assoc()):
        ?>
            <img src="<?= htmlspecialchars($img['image_path']) ?>"
                 class="<?= $first ? 'active' : '' ?>">
        <?php
        $first = false;
        endwhile;
        ?>
    </div>

    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p class="price">₹<?= number_format($p['price'],2) ?></p>

    <div class="cart-actions">
        <input type="number" class="qty-input" value="1" min="1">
        <button class="add-to-cart" onclick="addToCart(this)">Add to Cart</button>
    </div>
</div>

<?php endwhile; ?>

</div>
</main>

<footer>
<p>&copy; 2018 The Raj Enterprises. All Rights Reserved. | Bhendra, Bokaro</p>
<p>A Unit Of Bhendra Iron Cluster</p>
</footer>

<!-- ================= JS ================= -->
<script>
let slideIntervals = {};

function startSlide(card) {
    const images = card.querySelectorAll(".image-container img");
    if (images.length <= 1) return;

    let index = 0;
    const id = card.dataset.id;

    clearInterval(slideIntervals[id]);

    slideIntervals[id] = setInterval(() => {
        images[index].classList.remove("active");
        index = (index + 1) % images.length;
        images[index].classList.add("active");
    }, 5000); // ✅ 5 seconds (as requested)
}

function stopSlide(card) {
    const id = card.dataset.id;
    clearInterval(slideIntervals[id]);

    const images = card.querySelectorAll(".image-container img");
    images.forEach((img,i)=>{
        img.classList.toggle("active", i === 0);
    });
}

function addToCart(btn) {
    const card = btn.closest(".product-card");
    const id = card.dataset.id;
    const name = card.dataset.name;
    const price = parseFloat(card.dataset.price);
    const qty = parseInt(card.querySelector(".qty-input").value);

    let cart = JSON.parse(localStorage.getItem("rajCart")) || [];
    let item = cart.find(i => i.id === id);

    if(item) item.qty += qty;
    else cart.push({id,name,price,qty});

    localStorage.setItem("rajCart", JSON.stringify(cart));
    updateCartIcon();
    alert(name + " added to cart!");
}

function updateCartIcon() {
    let cart = JSON.parse(localStorage.getItem("rajCart")) || [];
    document.getElementById("cart-count").innerText =
        cart.reduce((s,i)=>s+i.qty,0);
}

window.onload = updateCartIcon;
</script>

</body>
</html>
