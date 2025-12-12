<?php
require_once 'db-connect.php';

$product_id = $_GET['id'] ?? 0;

if ($product_id > 0) {
    $conn = getConnection();
    
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        header("Location: index.php?message=deleted");
    } else {
        header("Location: index.php?message=error");
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
}

exit();
?>