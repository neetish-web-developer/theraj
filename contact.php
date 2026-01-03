<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | The Raj Enterprises</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contact_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="main_body">

    <nav class="navbar">
        <div class="logo">The Raj Enterprises</div>
        <ul class="nav-links">
            <li><a href="index.php" >Shop</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="contact.php"class="active">Contact Us</a></li>
        </ul>
        <div class="cart-icon" onclick="window.location.href='cart.php'" style="cursor:pointer; position: relative;">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count">0</span>
        </div>
    </nav>


    <div class="contactus">
        <div class="title">
            <h2>Get in Touch</h2>
        </div>
        <div class="box">
            <div class="contact form">
                <h3>Send a Message</h3>
                <form action="#">
                    <div class="formBox">
                        <div class="row50">
                            <div class="inputBox">
                                <span>First Name*</span>
                                <input type="text" placeholder="Neetish" required>
                            </div>
                            <div class="inputBox">
                                <span>Last Name*</span>
                                <input type="text" placeholder="Raj" required>
                            </div>
                        </div>

                        <div class="row50">
                            <div class="inputBox">
                                <span>Email*</span>
                                <input type="email" placeholder="abc@gmail.com" required>
                            </div>
                            <div class="inputBox">
                                <span>Mobile*</span>
                                <input type="tel" id="phone" placeholder="1234567890" required maxlength="10">
                            </div>
                        </div>

                        <div class="row100">
                            <div class="inputBox">
                                <span>Message*</span>
                                <textarea placeholder="Write your message here about mementos or bulk orders..." required></textarea>
                            </div>
                        </div>

                        <div class="row100">
                            <div class="inputBox">
                                <input type="submit" value="Submit">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="contact info">
                <h3>Contact Info</h3>
                <div class="infoBox">
                    <div>
                        <span><i class="fas fa-map-marker-alt"></i></span>
                        <p>Bhendra, Nawadih <br>Bokaro, Jharkhand <br>PIN: 828401</p>
                    </div>
                    <div>
                        <span><i class="fas fa-envelope"></i></span>
                        <a href="mailto:shop@rajlogic.in">shop@rajlogic.in</a>
                    </div>
                    <div>
                        <span><i class="fas fa-phone-alt"></i></span>
                        <a href="tel:+919449247076">+91 7050607135</a>
                        
                    </div>
                    <div>
                        <span><i class="fas fa-phone-alt"></i></span>
                        <a href="tel:+919449247076">+91 8709389973</a>
                        
                    </div>

                    <!-- <ul class="sci">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-whatsapp"></i></a></li>
                    </ul> -->
                </div>
            </div>

            <div class="contact map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14643.144825656123!2d86.1118671!3d23.7906103!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f4233777777777%3A0x7777777777777777!2sBhendra%2C%20Jharkhand%20828401!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin" 
                style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2018 The Raj Enterprises. All Rights Reserved. | Bhendra, Bokaro</p>
        <p>A Unit Of Bhendra Iron Cluster</p>
    </footer>

    <script>
        // Restrict phone input to numbers only
        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
    <script>
    function updateNavbarCart() {
        // Get the cart from local storage
        let cart = JSON.parse(localStorage.getItem('rajCart')) || [];
        
        // Calculate total items
        const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
        
        // Find the badge and update it
        const badge = document.getElementById('cart-count');
        if (badge) {
            badge.innerText = totalItems;
        }
    }

    // Run this function as soon as the page loads
    window.addEventListener('load', updateNavbarCart);
</script>
</body>
</html>