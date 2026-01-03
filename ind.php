<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Raj Enterprises | Premium Mementos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Essential CSS */
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f4f4; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 5%; background: #222; color: white; }
        .nav-links { list-style: none; display: flex; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; }
        .cart-icon { cursor: pointer; position: relative; font-size: 1.2rem; }
        #cart-count { background: red; color: white; border-radius: 50%; padding: 2px 7px; font-size: 12px; position: absolute; top: -10px; right: -15px; }
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; padding: 40px 5%; }
        .product-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        
        /* Two Image Hover Effect */
        .image-container { position: relative; width: 100%; height: 250px; overflow: hidden; border-radius: 5px; }
        .image-container img { width: 100%; height: 100%; object-fit: cover; position: absolute; left: 0; transition: 0.5s; }
        .img-hover { opacity: 0; }
        .product-card:hover .img-hover { opacity: 1; }
        .product-card:hover .img-main { opacity: 0; }

        .cart-actions { display: flex; justify-content: center; gap: 10px; margin-top: 15px; }
        .qty-input { width: 50px; padding: 5px; border: 1px solid #ddd; }
        .add-to-cart { background: #28a745; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">The Raj Enterprises</div>
        <ul class="nav-links">
            <li><a href="index.php">Shop</a></li>
            <li><a href="cart.php">Cart</a></li>
        </ul>
        <div class="cart-icon" onclick="window.location.href='cart.php'">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count">0</span>
        </div>
    </nav>

    <main class="product-grid">
        <div class="product-card" data-id="101" data-name="Crystal Trophy" data-price="45.00">
            <div class="image-container">
                <img src="m1.jpg" alt="Front" class="img-main">
                <img src="m1_alt.jpg" alt="Back" class="img-hover">
            </div>
            <h3>Crystal Trophy</h3>
            <p>$45.00</p>
            <div class="cart-actions">
                <input type="number" class="qty-input" value="1" min="1">
                <button class="add-to-cart" onclick="addToCart(this)">Add to Cart</button>
            </div>
        </div>

        <div class="product-card" data-id="102" data-name="Wooden Plaque" data-price="30.00">
            <div class="image-container">
                <img src="m2.jpg" alt="Front" class="img-main">
                <img src="m2_alt.jpg" alt="Back" class="img-hover">
            </div>
            <h3>Wooden Plaque</h3>
            <p>$30.00</p>
            <div class="cart-actions">
                <input type="number" class="qty-input" value="1" min="1">
                <button class="add-to-cart" onclick="addToCart(this)">Add to Cart</button>
            </div>
        </div>
    </main>

    <script>
        function addToCart(button) {
            // Find the parent card
            const card = button.closest('.product-card');
            
            // Get data from attributes
            const id = card.getAttribute('data-id');
            const name = card.getAttribute('data-name');
            const price = parseFloat(card.getAttribute('data-price'));
            const qty = parseInt(card.querySelector('.qty-input').value);

            // Get existing cart or create new array
            let cart = JSON.parse(localStorage.getItem('rajCart')) || [];

            // Check if item already exists
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.qty += qty;
            } else {
                cart.push({ id, name, price, qty });
            }

            // Save back to localStorage
            localStorage.setItem('rajCart', JSON.stringify(cart));
            
            updateIcon();
            alert(qty + " " + name + " added to cart!");
        }

        function updateIcon() {
            let cart = JSON.parse(localStorage.getItem('rajCart')) || [];
            const total = cart.reduce((sum, item) => sum + item.qty, 0);
            document.getElementById('cart-count').innerText = total;
        }

        // Run on page load
        updateIcon();
    </script>
</body>
</html>