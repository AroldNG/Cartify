<?php
require_once 'db-connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConnection();
    
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
    
    $sql = "INSERT INTO products (name, description, price, original_price, category, stock_quantity, image_url, rating, reviews_count, badge) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddsissds", $name, $description, $price, $original_price, $category, $stock, $image_url, $rating, $reviews, $badge);
    
    if ($stmt->execute()) {
        $message = "Product added successfully!";
        header("Location: index.php?message=added");
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Cartify</title>
    <link rel="stylesheet" href="css/admin-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>➕ Add New Product</h1>
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
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Fashion">Fashion</option>
                            <option value="Home & Kitchen">Home & Kitchen</option>
                            <option value="Sports">Sports</option>
                            <option value="Books">Books</option>
                            <option value="Beauty">Beauty</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (FCFA) *</label>
                        <input type="number" id="price" name="price" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="original_price">Original Price (FCFA)</label>
                        <input type="number" id="original_price" name="original_price" step="0.01">
                        <small>Leave empty if no discount</small>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL *</label>
                    <input type="url" id="image_url" name="image_url" required>
                    <small>Example: https://images.unsplash.com/photo-...</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="rating">Rating (0-5)</label>
                        <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" value="0">
                    </div>

                    <div class="form-group">
                        <label for="reviews">Reviews Count</label>
                        <input type="number" id="reviews" name="reviews" value="0">
                    </div>

                    <div class="form-group">
                        <label for="badge">Badge</label>
                        <select id="badge" name="badge">
                            <option value="">No Badge</option>
                            <option value="Best Seller">Best Seller</option>
                            <option value="Deal">Deal</option>
                            <option value="Popular">Popular</option>
                            <option value="New">New</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Add Product</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>