<?php
session_start();
require 'dbconnect.php';

// Admin authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Fetch summary data for display

$totalOrdersStmt = $pdo->query("SELECT COUNT(*) FROM orders");
$totalOrders = $totalOrdersStmt->fetchColumn();

$totalProductsStmt = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $totalProductsStmt->fetchColumn();

$totalUsersStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $totalUsersStmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin_styles.css">
</head>
<body>

<header>
<img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
            style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle; ">
        
    <h1>Admin Dashboard</h1> 
    <?php 
    if(isset($_SESSION['admin']))
    {
        echo "<h3> Welcome ".$_SESSION['admin']." </h3>";
    }

    ?>
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="inventory_management.php">Inventory Management</a>
        <a href="reports.php">Reports</a>
        <a href="delivery_management.php">Delivery Management</a>
        <a href="usermanagement.php">User Management</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="admin-main">
    <section class="admin-overview">
        <h2>Overview</h2>
        <div class="admin-statistics">
            <p>Total Orders: <?= $totalOrders ?></p>
            <p>Total Products: <?= $totalProducts ?></p>
            <p>Total Users: <?= $totalUsers ?></p>
        </div>
    </section>

    
</main>

</body>
</html>
