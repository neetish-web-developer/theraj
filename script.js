function addToCart(button) {
    const card = button.closest('.product-card');
    const id = card.getAttribute('data-id');
    const name = card.getAttribute('data-name');
    const price = parseFloat(card.getAttribute('data-price'));
    const qty = parseInt(card.querySelector('.qty-input').value);

    let cart = JSON.parse(localStorage.getItem('myCart')) || [];

    // Check if item already in cart
    const index = cart.findIndex(item => item.id === id);
    if (index > -1) {
        cart[index].qty += qty;
    } else {
        cart.push({ id, name, price, qty });
    }

    localStorage.setItem('myCart', JSON.stringify(cart));
    updateCartIcon();
    alert("Added " + qty + " item(s) to cart!");
}

function updateCartIcon() {
    const cart = JSON.parse(localStorage.getItem('myCart')) || [];
    const totalItems = cart.reduce((acc, item) => acc + item.qty, 0);
    const badge = document.getElementById('cart-count');
    if(badge) badge.innerText = totalItems;
}

// Run on load
updateCartIcon();