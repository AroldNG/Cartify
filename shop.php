<?php
require_once 'db-connect.php';
$conn = getConnection();

// Get all active products
$sql = "SELECT * FROM products WHERE stock_quantity > 0 ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartify</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="header-left">
                <span>📍 Deliver to Douala, Cameroon</span>
            </div>
            <div class="header-right">
                <a href="#">Customer Service</a>
                <a href="#">Track Order</a>
                <a href="index.php">Admin Panel</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="container">
            <div class="nav-content">
                <div class="nav-left">
                    <h1 class="logo">Cartify</h1>
                    <button class="menu-btn">☰ All</button>
                </div>

                <div class="search-bar">
                    <select class="search-category">
                        <option>All</option>
                        <option>Electronics</option>
                        <option>Fashion</option>
                        <option>Home</option>
                    </select>
                    <input type="text" placeholder="Search Cartify" class="search-input">
                    <button class="search-btn">🔍</button>
                </div>

                <div class="nav-right">
                    <a href="#" class="nav-link">
                        <span class="small">Hello, Guest</span>
                        <span class="bold">Account</span>
                    </a>
                    <a href="#" class="nav-link">
                        <span class="small">Returns</span>
                        <span class="bold">& Orders</span>
                    </a>
                    <a href="cart.php" class="cart-link">
                        <span class="cart-icon">🛒</span>
                        <span class="cart-count" id="cartCount">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Categories Bar -->
    <div class="categories-bar">
        <div class="container">
            <a href="?category=all">All Products</a>
            <a href="?category=Electronics">Electronics</a>
            <a href="?category=Fashion">Fashion</a>
            <a href="?category=Home & Kitchen">Home & Kitchen</a>
            <a href="?category=Sports">Sports</a>
            <a href="?category=Books">Books</a>
            <a href="?category=Beauty">Beauty</a>
        </div>
    </div>

    <!-- Banner -->
    <div class="banner">
        <div class="container">
            <h2>Today's Deals</h2>
            <p>Save up to 50% on selected items</p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container">
        <div class="products-section">
            <h3>Recommended for You (<?php echo $result->num_rows; ?> Products)</h3>
            <div class="products-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($product = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <div class="product-image-container">
                                <?php if($product['badge']): ?>
                                    <span class="product-badge"><?php echo htmlspecialchars($product['badge']); ?></span>
                                <?php endif; ?>
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image">
                            </div>
                            
                            <h4 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h4>
                            
                            <div class="product-rating">
                                <span class="stars">
                                    <?php 
                                    $rating = $product['rating'];
                                    for($i = 1; $i <= 5; $i++) {
                                        if($i <= floor($rating)) {
                                            echo '★';
                                        } elseif($i - $rating < 1) {
                                            echo '★';
                                        } else {
                                            echo '☆';
                                        }
                                    }
                                    ?>
                                </span>
                                <span class="reviews">(<?php echo number_format($product['reviews_count']); ?>)</span>
                            </div>
                            
                            <div>
                                <span class="product-price">
                                    <?php echo number_format($product['price'], 0, ',', ' '); ?> FCFA
                                </span>
                                <?php if($product['original_price']): ?>
                                    <span class="original-price">
                                        <?php echo number_format($product['original_price'], 0, ',', ' '); ?> FCFA
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($product['original_price']): ?>
                                <span class="savings">
                                    Save <?php echo number_format($product['original_price'] - $product['price'], 0, ',', ' '); ?> FCFA
                                </span>
                            <?php endif; ?>
                            
                            <div class="quantity-selector">
                                <span>Qty:</span>
                                <div class="quantity-controls">
                                    <button class="quantity-btn" onclick="changeQuantity(<?php echo $product['product_id']; ?>, -1)">-</button>
                                    <span class="quantity-value" id="qty-<?php echo $product['product_id']; ?>">1</span>
                                    <button class="quantity-btn" onclick="changeQuantity(<?php echo $product['product_id']; ?>, 1)">+</button>
                                </div>
                            </div>
                            
                            <button class="add-to-cart-btn" 
                                    onclick="addToCart(<?php echo $product['product_id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>')">
                                Add to Cart
                            </button>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-products">No products available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
       

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-column">
                    <h4>Get to Know Us</h4>
                    <a href="#">About Us</a>
                    <a href="#">Careers</a>
                    <a href="#">Press</a>
                </div>
                <div class="footer-column">
                    <h4>Make Money</h4>
                    <a href="#">Sell on Cartify</a>
                    <a href="#">Affiliate Program</a>
                    <a href="#">Advertise</a>
                </div>
                <div class="footer-column">
                    <h4>Payment</h4>
                    <a href="#">Your Account</a>
                    <a href="#">Returns</a>
                    <a href="#">Shipping</a>
                </div>
                <div class="footer-column">
                    <h4>Help</h4>
                    <a href="#">Customer Service</a>
                    <a href="#">Contact Us</a>
                    <a href="#">FAQ</a>
                </div>
            </div>
            <div class="footer-bottom">
                <h2 class="logo">Cartify</h2>
                <p>&copy; 2025 Cartify. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Quantity selector functions
        function changeQuantity(productId, change) {
            const qtyElement = document.getElementById('qty-' + productId);
            let currentQty = parseInt(qtyElement.textContent);
            currentQty = Math.max(1, currentQty + change);
            qtyElement.textContent = currentQty;
        }

        // Add to cart function
        function addToCart(productId, productName) {
            const qtyElement = document.getElementById('qty-' + productId);
            const quantity = parseInt(qtyElement.textContent);
            
            // Get existing cart from localStorage
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            
            // Check if product already in cart
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    quantity: quantity
                });
            }
            
            // Save cart
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();
            
            // Show alert
            alert('Added ' + quantity + ' ' + productName + ' to cart!');
            
            // Reset quantity to 1
            qtyElement.textContent = '1';
        }

        // Update cart count
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = totalItems;
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>