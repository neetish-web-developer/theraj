<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart | The Raj Enterprises</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #27ae60;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
        }

        body { background-color: var(--light-bg); margin: 0; font-family: 'Segoe UI', sans-serif; }

        /* Pro Cart Layout */
        .cart-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            min-height: 60vh;
        }

        @media (max-width: 900px) {
            .cart-wrapper { grid-template-columns: 1fr; }
        }

        .cart-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; color: #7f8c8d; font-size: 0.85rem; text-transform: uppercase; padding: 10px 0; }
        td { padding: 20px 0; border-bottom: 1px solid #f1f1f1; vertical-align: middle; }

        /* Quantity Controls */
        .qty-control {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f1f1f1;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 6px;
        }
        .qty-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--primary-color);
            padding: 0 5px;
            font-weight: bold;
        }
        .qty-btn:hover { color: var(--accent-color); }
        .qty-val { font-weight: 600; min-width: 20px; text-align: center; }

        /* Summary Sidebar */
        .summary-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .total-row {
            border-top: 2px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .btn-checkout {
            width: 100%;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .btn-checkout:hover { background: #219150; transform: translateY(-2px); }

        .remove-btn {
            color: #ccc;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            transition: 0.3s;
        }
        .remove-btn:hover { color: var(--danger-color); }

        .empty-state { text-align: center; padding: 60px 0; }
        .empty-state i { font-size: 4rem; color: #ddd; margin-bottom: 20px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">The Raj Enterprises</div>
        <ul class="nav-links">
            <li><a href="index.php">Shop</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <div class="cart-icon" onclick="window.location.href='cart.php'" style="cursor:pointer; position: relative;">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count">0</span>
        </div>
    </nav>

    <div class="cart-wrapper">
        <div class="cart-box">
            <div class="cart-header">
                <h2>Your Cart</h2>
                <button onclick="clearCart()" style="background:none; border:none; color:var(--danger-color); cursor:pointer; font-weight:600;">
                    <i class="fas fa-trash-alt"></i> Empty Cart
                </button>
            </div>
            
            <div id="cart-content">
                </div>
        </div>

        <div class="summary-box">
            <h3>Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal">₹0.00</span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span style="color:var(--accent-color); font-weight:600;">FREE</span>
            </div>
            <div class="summary-row total-row">
                <span>Total</span>
                <span id="grand-total">₹0.00</span>
            </div>
            <button class="btn-checkout" onclick="proceedToPayment()">
                Proceed to Checkout <i class="fas fa-arrow-right"></i>
            </button>
            <div style="text-align:center; margin-top:15px; font-size:0.85rem; color:#888;">
                <i class="fas fa-shield-alt"></i> Secure Payment via UPI
            </div>
        </div>
    </div>

    <footer>
        <p>© 2018 The Raj Enterprises. All Rights Reserved. | Bhendra, Bokaro</p>
    </footer>

    <script>
        function loadCart() {
            const cart = JSON.parse(localStorage.getItem('rajCart')) || [];
            const content = document.getElementById('cart-content');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('grand-total');
            const navBadge = document.getElementById('cart-count');

            // Update Nav Badge
            const totalQty = cart.reduce((acc, item) => acc + item.qty, 0);
            navBadge.innerText = totalQty;

            if (cart.length === 0) {
                content.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-shopping-basket"></i>
                        <h3>Your cart is empty</h3>
                        <p>Browse our collection to add exquisite mementos.</p>
                        <a href="index.php" style="color:var(--accent-color); text-decoration:none; font-weight:bold;">Return to Shop</a>
                    </div>`;
                subtotalEl.innerText = "₹0.00";
                totalEl.innerText = "₹0.00";
                return;
            }

            let html = `<table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>`;
            
            let totalMoney = 0;

            cart.forEach((item, index) => {
                let sub = item.price * item.qty;
                totalMoney += sub;
                html += `
                    <tr>
                        <td><strong>${item.name}</strong></td>
                        <td>₹${item.price.toFixed(2)}</td>
                        <td>
                            <div class="qty-control">
                                <button class="qty-btn" onclick="changeQty(${index}, -1)">-</button>
                                <span class="qty-val">${item.qty}</span>
                                <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td>₹${sub.toFixed(2)}</td>
                        <td>
                            <button class="remove-btn" onclick="removeItem(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>`;
            });

            html += `</tbody></table>`;
            content.innerHTML = html;
            subtotalEl.innerText = "₹" + totalMoney.toFixed(2);
            totalEl.innerText = "₹" + totalMoney.toFixed(2);
        }

        function changeQty(index, delta) {
            let cart = JSON.parse(localStorage.getItem('rajCart')) || [];
            cart[index].qty += delta;
            if (cart[index].qty < 1) cart[index].qty = 1;
            localStorage.setItem('rajCart', JSON.stringify(cart));
            loadCart();
        }

        function removeItem(index) {
            let cart = JSON.parse(localStorage.getItem('rajCart')) || [];
            cart.splice(index, 1);
            localStorage.setItem('rajCart', JSON.stringify(cart));
            loadCart();
        }

        function clearCart() {
            if(confirm("Are you sure you want to clear your entire cart?")) {
                localStorage.removeItem('rajCart');
                loadCart();
            }
        }

        function proceedToPayment() {
            // Get text and remove the ₹ symbol to pass just the number to payment.php
            const total = document.getElementById('grand-total').innerText.replace('₹', '');
            if (parseFloat(total) > 0) {
                window.location.href = "payment.php?amount=" + total;
            } else {
                alert("Your cart is empty!");
            }
        }

        window.onload = loadCart;
    </script>
</body>
</html>