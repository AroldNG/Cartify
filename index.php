<?php
require_once 'db-onnect.php';
$conn = getConnection();

// Get all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartify - Product Management</title>
    <link rel="stylesheet" href="css/admin-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🛒 Cartify Product Management</h1>
            <a href="add-product.html" class="btn btn-primary">➕ Add New Product</a>
        </header>

        <div class="products-table">
            <h2>All Products (<?php echo $result->num_rows; ?>)</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price (FCFA)</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['product_id']; ?></td>
                                <td>
                                    <img src="<?php echo $row['image_url']; ?>" 
                                         alt="<?php echo $row['name']; ?>" 
                                         class="product-thumb">
                                </td>
                                <td>
                                    <strong><?php echo $row['name']; ?></strong>
                                    <?php if($row['badge']): ?>
                                        <span class="badge"><?php echo $row['badge']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo number_format($row['price'], 0, ',', ' '); ?> FCFA</strong>
                                    <?php if($row['original_price']): ?>
                                        <br><small class="original-price">
                                            <?php echo number_format($row['original_price'], 0, ',', ' '); ?> FCFA
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row['category']; ?></td>
                                <td>
                                    <span class="stock <?php echo $row['stock_quantity'] > 0 ? 'in-stock' : 'out-stock'; ?>">
                                        <?php echo $row['stock_quantity']; ?> units
                                    </span>
                                </td>
                                <td>
                                    ⭐ <?php echo $row['rating']; ?> 
                                    <small>(<?php echo $row['reviews_count']; ?>)</small>
                                </td>
                                <td class="actions">
                                    <a href="edit-product.php?id=<?php echo $row['product_id']; ?>" 
                                       class="btn btn-edit">✏️ Edit</a>
                                    <a href="delete-product.php?id=<?php echo $row['product_id']; ?>" 
                                       class="btn btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this product?')">
                                       🗑️ Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-products">No products found. <a href="add-product.html">Add your first product!</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>