<?php
require_once 'db_connect.php';
$conn = getConnection();

$message = '';
$product_id = $_GET['id'] ?? 0;

// Get product data
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $original_price = $_POST['original_price'] ? $_POST['original_price'] : NULL;
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];
    $badge = $_POST['badge'];
    
    $sql = "UPDATE products SET 
            name = ?, 
            description = ?, 
            price = ?, 
            original_price = ?, 
            category = ?, 
            stock_quantity = ?, 
            image_url = ?, 
            rating = ?, 
            reviews_count = ?, 
            badge = ? 
            WHERE product_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddsissdsi", $name, $description, $price, $original_price, $category, $stock, $image_url, $rating, $reviews, $badge, $product_id);
    
    if ($stmt->execute()) {
        header("Location: index.php?message=updated");
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Cartify</title>
    <link rel="stylesheet" href="css/admin-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>✏️ Edit Product</h1>
            <a href="index.php" class="btn btn-secondary">← Back to Products</a>
        </header>

        <?php if($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="Electronics" <?php echo $product['category'] == 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                            <option value="Fashion" <?php echo $product['category'] == 'Fashion' ? 'selected' : ''; ?>>Fashion</option>
                            <option value="Home & Kitchen" <?php echo $product['category'] == 'Home & Kitchen' ? 'selected' : ''; ?>>Home & Kitchen</option>
                            <option value="Sports" <?php echo $product['category'] == 'Sports' ? 'selected' : ''; ?>>Sports</option>
                            <option value="Books" <?php echo $product['category'] == 'Books' ? 'selected' : ''; ?>>Books</option>
                            <option value="Beauty" <?php echo $product['category'] == 'Beauty' ? 'selected' : ''; ?>>Beauty</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (FCFA) *</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="original_price">Original Price (FCFA)</label>
                        <input type="number" id="original_price" name="original_price" step="0.01" value="<?php echo $product['original_price']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock" value="<?php echo $product['stock_quantity']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL *</label>
                    <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="rating">Rating (0-5)</label>
                        <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" value="<?php echo $product['rating']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="reviews">Reviews Count</label>
                        <input type="number" id="reviews" name="reviews" value="<?php echo $product['reviews_count']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="badge">Badge</label>
                        <select id="badge" name="badge">
                            <option value="">No Badge</option>
                            <option value="Best Seller" <?php echo $product['badge'] == 'Best Seller' ? 'selected' : ''; ?>>Best Seller</option>
                            <option value="Deal" <?php echo $product['badge'] == 'Deal' ? 'selected' : ''; ?>>Deal</option>
                            <option value="Popular" <?php echo $product['badge'] == 'Popular' ? 'selected' : ''; ?>>Popular</option>
                            <option value="New" <?php echo $product['badge'] == 'New' ? 'selected' : ''; ?>>New</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Update Product</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>