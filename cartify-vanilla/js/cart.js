// Format price in FCFA
function formatPrice(price) {
    return price.toLocaleString('fr-FR') + ' FCFA';
}

// Get cart from localStorage
function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
}

// Save cart to localStorage
function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Calculate cart totals (NO TAX)
function calculateCartTotals() {
    const cart = getCart();
    
    const subtotal = cart.reduce((sum, item) => {
        return sum + (item.price * item.quantity);
    }, 0);
    
    const total = subtotal;
    
    return {
        subtotal: subtotal,
        total: total
    };
}

// Update cart quantity
function updateCartQuantity(productId, change) {
    let cart = getCart();
    const item = cart.find(item => item.id === productId);
    
    if (item) {
        item.quantity += change;
        
        if (item.quantity < 1) {
            removeFromCart(productId);
            return;
        }
        
        saveCart(cart);
        renderCart();
    }
}

// Remove item from cart
function removeFromCart(productId) {
    let cart = getCart();
    cart = cart.filter(item => item.id !== productId);
    saveCart(cart);
    renderCart();
}

// Render cart items
function renderCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cart = getCart();
    
    // Update cart count in header
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartItemCountElement = document.getElementById('cartItemCount');
    if (cartItemCountElement) {
        cartItemCountElement.textContent = totalItems;
    }
    
    // If cart is empty
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = 
            '<div class="empty-cart">' +
                '<div class="empty-cart-icon">🛒</div>' +
                '<h3>Your cart is empty</h3>' +
                '<p>Add some products to get started!</p>' +
                '<a href="index.html" class="start-shopping-btn">Start Shopping</a>' +
            '</div>';
        updateCartSummary();
        return;
    }
    
    // Render cart items
    cartItemsContainer.innerHTML = '';
    
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        
        const itemTotal = item.price * item.quantity;
        
        cartItem.innerHTML = 
            '<img src="' + item.image + '" alt="' + item.name + '" class="cart-item-image">' +
            '<div class="cart-item-details">' +
                '<h3 class="cart-item-name">' + item.name + '</h3>' +
                '<p class="cart-item-price">' + formatPrice(item.price) + '</p>' +
                '<div class="cart-item-actions">' +
                    '<div class="quantity-controls">' +
                        '<button class="quantity-btn" onclick="updateCartQuantity(' + item.id + ', -1)">-</button>' +
                        '<span class="quantity-value">' + item.quantity + '</span>' +
                        '<button class="quantity-btn" onclick="updateCartQuantity(' + item.id + ', 1)">+</button>' +
                    '</div>' +
                    '<button class="remove-btn" onclick="removeFromCart(' + item.id + ')">' +
                        '🗑️ Remove' +
                    '</button>' +
                '</div>' +
            '</div>' +
            '<div class="cart-item-total">' +
                formatPrice(itemTotal) +
            '</div>';
        
        cartItemsContainer.appendChild(cartItem);
    });
    
    updateCartSummary();
}

// Update cart summary
function updateCartSummary() {
    const totals = calculateCartTotals();
    
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    
    if (subtotalElement) subtotalElement.textContent = formatPrice(totals.subtotal);
    if (totalElement) totalElement.textContent = formatPrice(totals.total);
}

// Initialize cart page
document.addEventListener('DOMContentLoaded', function() {
    renderCart();
});
