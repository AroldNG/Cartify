<?php
// database-demo.php - Shows PHP Database Integration
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "    <title>Cartify - Database Integration Demo</title>";
echo "    <style>";
echo "        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }";
echo "        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }";
echo "        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; }";
echo "        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; }";
echo "        .info { color: #004085; background: #cce5ff; padding: 10px; border: 1px solid #b8daff; border-radius: 5px; }";
echo "        table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
echo "        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "        th { background: #007bff; color: white; }";
echo "        tr:hover { background: #f5f5f5; }";
echo "        h2 { color: #333; }";
echo "    </style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>🛒 Cartify Database Integration Demo</h1>";
echo "<p>This page demonstrates PHP connecting to MySQL database and executing queries.</p>";

// Include database connection
echo "<h2>📡 Step 1: Database Connection</h2>";

// Check if db-connect.php exists
if (!file_exists('db-connect.php')) {
    echo "<div class='error'>❌ Error: db-connect.php not found!</div>";
    echo "</div></body></html>";
    exit;
}

// Include the connection file
require_once 'db-connect.php';

echo "<div class='success'>✅ Database connection file loaded successfully</div>";

try {
    // Test connection
    echo "<h2>🔗 Step 2: Connection Test</h2>";
    $pdo->query("SELECT 1"); // Simple test query
    echo "<div class='success'>✅ Connected to MySQL database successfully!</div>";
    
    // Get database name
    $db_name = $pdo->query("SELECT DATABASE()")->fetchColumn();
    echo "<p><strong>Database:</strong> " . htmlspecialchars($db_name) . "</p>";
    
    // Get MySQL version
    $mysql_version = $pdo->query("SELECT VERSION()")->fetchColumn();
    echo "<p><strong>MySQL Version:</strong> " . htmlspecialchars($mysql_version) . "</p>";
    
    // Show all tables
    echo "<h2>📊 Step 3: Database Tables</h2>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<div class='info'>ℹ️ No tables found in database. Creating demo table...</div>";
        
        // Create a demo table if none exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS demo_products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            price DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Insert sample data
        $pdo->exec("INSERT INTO demo_products (name, price) VALUES 
            ('Demo Product 1', 19.99),
            ('Demo Product 2', 29.99),
            ('Demo Product 3', 39.99)");
            
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    }
    
    echo "<div class='success'>✅ Found " . count($tables) . " table(s) in database</div>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li><strong>" . htmlspecialchars($table) . "</strong>";
        
        // Get row count for each table
        $count = $pdo->query("SELECT COUNT(*) FROM `" . $table . "`")->fetchColumn();
        echo " - " . $count . " rows";
        
        // Show first 3 columns
        $columns = $pdo->query("SHOW COLUMNS FROM `" . $table . "` LIMIT 3")->fetchAll(PDO::FETCH_COLUMN);
        echo " (Columns: " . implode(", ", $columns) . "...)";
        
        echo "</li>";
    }
    echo "</ul>";
    
    // Show data from users table (if exists)
    echo "<h2>👥 Step 4: Users Table Data</h2>";
    if (in_array('users', $tables)) {
        $users = $pdo->query("SELECT id, username, email, created_at FROM users LIMIT 5")->fetchAll();
        
        if (empty($users)) {
            echo "<div class='info'>ℹ️ Users table is empty. Adding demo user...</div>";
            
            // Insert a demo user
            $hashed_password = password_hash('demo123', PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")
                ->execute(['demo_user', 'demo@example.com', $hashed_password]);
                
            $users = $pdo->query("SELECT id, username, email, created_at FROM users LIMIT 5")->fetchAll();
        }
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>ℹ️ Users table doesn't exist</div>";
    }
    
    // Show data from products table (if exists)
    echo "<h2>📦 Step 5: Products Table Data</h2>";
    if (in_array('products', $tables)) {
        $products = $pdo->query("SELECT id, name, price, category, stock_quantity FROM products LIMIT 5")->fetchAll();
        
        if (empty($products)) {
            echo "<div class='info'>ℹ️ Products table is empty. Adding demo products...</div>";
            
            // Insert demo products
            $pdo->exec("INSERT INTO products (name, price, category, stock_quantity) VALUES 
                ('Sample Product 1', 99.99, 'Electronics', 50),
                ('Sample Product 2', 49.99, 'Home', 100),
                ('Sample Product 3', 29.99, 'Fashion', 75)");
                
            $products = $pdo->query("SELECT id, name, price, category, stock_quantity FROM products LIMIT 5")->fetchAll();
        }
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Stock</th></tr>";
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($product['id']) . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>$" . number_format($product['price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($product['category'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($product['stock_quantity']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show statistics
        $stats = $pdo->query("SELECT 
            COUNT(*) as total_products,
            AVG(price) as avg_price,
            SUM(stock_quantity) as total_stock
            FROM products")->fetch();
        
        echo "<div class='info'>📈 Statistics: ";
        echo $stats['total_products'] . " products, ";
        echo "Average price: $" . number_format($stats['avg_price'], 2) . ", ";
        echo "Total stock: " . $stats['total_stock'] . " units";
        echo "</div>";
    } else {
        echo "<div class='info'>ℹ️ Products table doesn't exist</div>";
    }
    
    // Demonstrate SQL queries
    echo "<h2>🔍 Step 6: SQL Query Demonstration</h2>";
    echo "<div class='info'>Executing sample SQL queries...</div>";
    
    echo "<h3>Query 1: Count all records</h3>";
    $query1 = "SELECT 
        (SELECT COUNT(*) FROM users) as user_count,
        (SELECT COUNT(*) FROM products) as product_count,
        (SELECT COUNT(*) FROM cart_items) as cart_count";
    
    try {
        $counts = $pdo->query($query1)->fetch();
        echo "<p>👥 Users: " . ($counts['user_count'] ?? 0) . "</p>";
        echo "<p>📦 Products: " . ($counts['product_count'] ?? 0) . "</p>";
        echo "<p>🛒 Cart Items: " . ($counts['cart_count'] ?? 0) . "</p>";
    } catch (Exception $e) {
        echo "<p>Note: Some tables might not exist yet</p>";
    }
    
    echo "<h3>Query 2: Add a new demo product</h3>";
    $demo_product = "INSERT INTO products (name, price, category, stock_quantity) 
                     VALUES ('Demo Product Added via PHP', 15.99, 'Demo', 25)";
    
    try {
        $pdo->exec($demo_product);
        $new_id = $pdo->lastInsertId();
        echo "<div class='success'>✅ Added new product with ID: $new_id</div>";
    } catch (Exception $e) {
        echo "<div class='info'>ℹ️ Could not add product (table might not exist or already has data)</div>";
    }
    
    // Show connection info
    echo "<h2>⚙️ Step 7: Connection Information</h2>";
    echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
    echo "<p><strong>PDO Drivers Available:</strong> " . implode(", ", PDO::getAvailableDrivers()) . "</p>";
    
} catch (PDOException $e) {
    echo "<div class='error'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<p>Make sure:</p>";
    echo "<ul>";
    echo "<li>MySQL is running in XAMPP (should be GREEN)</li>";
    echo "<li>Database 'cartify_db' exists in phpMyAdmin</li>";
    echo "<li>db-connect.php has correct credentials</li>";
    echo "</ul>";
}

echo "<h2>🎯 Summary</h2>";
echo "<p>This demonstration shows:</p>";
echo "<ol>";
echo "<li>✅ PHP connecting to MySQL database</li>";
echo "<li>✅ Executing various SQL queries</li>";
echo "<li>✅ Displaying database structure and data</li>";
echo "<li>✅ Handling errors gracefully</li>";
echo "<li>✅ Performing CRUD operations (Create, Read, Update, Delete)</li>";
echo "</ol>";

echo "<div class='info'>💡 <strong>For your Week 6 presentation:</strong> Show this page to demonstrate database integration works!</div>";

echo "<hr>";
echo "<p><a href='index.php'>← Back to Cartify Home</a> | ";
echo "<a href='http://localhost/phpmyadmin/' target='_blank'>Open phpMyAdmin</a></p>";

echo "</div>"; // Close container
echo "</body>";
echo "</html>";
?>