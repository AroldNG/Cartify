// Format price in FCFA
function formatPrice(price) {
    return price.toLocaleString('fr-FR') + ' FCFA';
}

// Cart functions
function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

function addToCart(productId, quantity) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    let cart = getCart();
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            ...product,
            quantity: quantity
        });
    }

    saveCart(cart);
    alert('Added ' + quantity + ' ' + product.name + ' to cart!');
}

function updateCartCount() {
    const cart = getCart();
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.textContent = totalItems;
    }
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(rating)) {
            stars += '★';
        } else if (i - rating < 1) {
            stars += '★';
        } else {
            stars += '☆';
        }
    }
    return stars;
}

function renderProducts() {
    const productsGrid = document.getElementById('productsGrid');
    if (!productsGrid) return;

    productsGrid.innerHTML = '';

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        
        const savings = product.originalPrice 
            ? '<span class="savings">Save ' + formatPrice(product.originalPrice - product.price) + '</span>'
            : '';

        const badge = product.badge 
            ? '<span class="product-badge">' + product.badge + '</span>'
            : '';

        const originalPrice = product.originalPrice
            ? '<span class="original-price">' + formatPrice(product.originalPrice) + '</span>'
            : '';

        productCard.innerHTML = 
            '<div class="product-image-container">' +
                badge +
                '<img src="' + product.image + '" alt="' + product.name + '" class="product-image">' +
            '</div>' +
            '<h4 class="product-name">' + product.name + '</h4>' +
            '<div class="product-rating">' +
                '<span class="stars">' + generateStars(product.rating) + '</span>' +
                '<span class="reviews">(' + product.reviews + ')</span>' +
            '</div>' +
            '<div>' +
                '<span class="product-price">' + formatPrice(product.price) + '</span>' +
                originalPrice +
            '</div>' +
            savings +
            '<div class="quantity-selector">' +
                '<span>Qty:</span>' +
                '<div class="quantity-controls">' +
                    '<button class="quantity-btn" onclick="changeQuantity(' + product.id + ', -1)">-</button>' +
                    '<span class="quantity-value" id="qty-' + product.id + '">1</span>' +
                    '<button class="quantity-btn" onclick="changeQuantity(' + product.id + ', 1)">+</button>' +
                '</div>' +
            '</div>' +
            '<button class="add-to-cart-btn" onclick="addProductToCart(' + product.id + ')">' +
                'Add to Cart' +
            '</button>';

        productsGrid.appendChild(productCard);
    });
}

function changeQuantity(productId, change) {
    const qtyElement = document.getElementById('qty-' + productId);
    let currentQty = parseInt(qtyElement.textContent);
    currentQty = Math.max(1, currentQty + change);
    qtyElement.textContent = currentQty;
}

function addProductToCart(productId) {
    const qtyElement = document.getElementById('qty-' + productId);
    const quantity = parseInt(qtyElement.textContent);
    addToCart(productId, quantity);
    qtyElement.textContent = '1';
}

document.addEventListener('DOMContentLoaded', function() {
    renderProducts();
    updateCartCount();
});
