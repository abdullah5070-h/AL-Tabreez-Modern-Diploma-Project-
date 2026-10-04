<?php
session_start();
require 'dbconnect.php';

// Check for admin session to secure access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$product_id = $_GET['id'] ?? 0;

if ($product_id) {
    // Delete the product from the database
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);

    $_SESSION['message'] = "Product deleted successfully!";
}

header("Location: inventory_management.php");
exit;
?>
