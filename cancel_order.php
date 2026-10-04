<?php
session_start();
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? 0;

    // Delete the order from the orders table
    $deleteOrderStmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
    $deleteOrderStmt->execute([$order_id]);

    // Delete order details from the order_details table
    $deleteDetailsStmt = $pdo->prepare("DELETE FROM order_details WHERE order_id = ?");
    $deleteDetailsStmt->execute([$order_id]);
    
     // Set a session variable for confirmation message
    $_SESSION['cancel_confirmation'] = "Order canceled successfully.";

    // Redirect to index.php after canceling the order
    header("Location: order_summary.php");
    exit;
} else {
    // Redirect to index.php if accessed without a POST request
    header("Location: index.php");
    exit;
}
?>
