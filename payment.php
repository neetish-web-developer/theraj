<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment | The Raj Enterprises</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #27ae60;
        }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; }
        .payment-container {
            max-width: 450px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
        }
        .amount-tag {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent-color);
            margin: 15px 0;
        }
        .qr-card {
            background: #fff;
            padding: 15px;
            border: 2px dashed #ddd;
            border-radius: 15px;
            display: inline-block;
            margin: 20px 0;
        }
        .qr-card img {
            display: block;
            width: 220px;
            height: 220px;
        }
        .upi-badge {
            background: #f1f1f1;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: #555;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn-success {
            display: block;
            width: 100%;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 10px;
            font-weight: 600;
            margin-top: 20px;
            transition: 0.3s;
        }
        .btn-success:hover { background: #1a252f; }
        .footer-note { font-size: 0.8rem; color: #999; margin-top: 20px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">The Raj Enterprises</div>
    </nav>

    <?php
        // 1. Get and clean the amount
        $raw_amount = isset($_GET['amount']) ? $_GET['amount'] : '0.00';
        $amount = preg_replace('/[^0-9.]/', '', $raw_amount); // Ensure only numbers
        
        // 2. Your Specific Payment Details
        $upi_id = "neetish61raj-2@okicici"; 
        $payee_name = "The Raj Enterprises";
        
        // 3. Construct UPI Deep Link
        $upi_url = "upi://pay?pa=" . $upi_id . "&pn=" . rawurlencode($payee_name) . "&am=" . $amount . "&cu=INR";
        
        // 4. Generate QR via QuickChart (Reliable & High Quality)
        $qr_image_url = "https://quickchart.io/qr?text=" . urlencode($upi_url) . "&size=250&margin=1";
    ?>

    <div class="payment-container">
        <div style="color: var(--accent-color); font-size: 3rem; margin-bottom: 10px;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 style="margin:0;">Secure Payment</h2>
        <p style="color:#666; margin-top:5px;">Scan to pay via any UPI App</p>

        <div class="amount-tag">₹<?php echo number_format((float)$amount, 2); ?></div>

        <div class="upi-badge">
            <i class="fas fa-at"></i> <?php echo $upi_id; ?>
        </div>

        <div class="qr-card">
            <img src="<?php echo $qr_image_url; ?>" alt="Scan to Pay">
        </div>

        <p style="font-size: 0.85rem; color: #777;">
            <i class="fas fa-info-circle"></i> Once payment is complete, click the button below to finish your order.
        </p>

        <a href="index.php" class="btn-success" onclick="clearCart()">
            I have made the payment
        </a>

        <div class="footer-note">
            <i class="fas fa-lock"></i> SSL Encrypted & Secure Payment
        </div>
    </div>

    <script>
        function clearCart() {
            localStorage.removeItem('rajCart');
            alert("Thank you! Order Status: Payment Under Verification.");
        }
    </script>
</body>
</html>