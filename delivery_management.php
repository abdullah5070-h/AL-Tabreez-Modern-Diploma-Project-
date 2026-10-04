<?php
session_start();
require 'dbconnect.php';

// Admin authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Handle status update actions
if (isset($_GET['action'], $_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $action = $_GET['action'];

    if ($action === 'mark_as_shipped') {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'shipped' WHERE order_id = ?");
    } elseif ($action === 'mark_as_delivered') {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'delivered' WHERE order_id = ?");
    }

    if (isset($stmt)) {
        $stmt->execute([$order_id]);
    }
    
    // Redirect to avoid resubmission on refresh
    header("Location: delivery_management.php");
    exit;
}
$pendingOrders = $pdo->query("SELECT orders.*, users.username as user_name 
                              FROM orders 
                              JOIN users ON orders.user_id = users.user_id 
                              WHERE orders.status = 'pending'")->fetchAll();

$shippedOrders = $pdo->query("SELECT orders.*, users.username as user_name 
                              FROM orders 
                              JOIN users ON orders.user_id = users.user_id 
                              WHERE orders.status = 'shipped'")->fetchAll();

$deliveredOrders = $pdo->query("SELECT orders.*, users.username as user_name 
                                FROM orders 
                                JOIN users ON orders.user_id = users.user_id 
                                WHERE orders.status = 'delivered'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Management - Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin_styles.css">
    <style>
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        min-width: 400px;
        width: 100%;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }

    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #009879;
    }

    .btn {
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 5px;
        color: white;
    }

    .btn-green {
        background-color: #4CAF50;
    }

    .btn-green:hover {
        background-color: #45a049;
    }

    .btn-blue {
        background-color: #007bff;
    }

    .btn-blue:hover {
        background-color: #0069d9;
    }
    </style>
</head>

<body>

<header>
<img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
            style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle; ">
        
    <h1>Admin Dashboard</h1>
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
        <section class="admin-section">
            <h2>Pending Orders</h2>
            <!-- Pending Orders Table -->
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                       <th>User Name</th>
                        <th>Order Date</th>
                         <th>Delivery Date</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingOrders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td><?= htmlspecialchars($order['delivery_date']) ?></td>
                        <td><?= htmlspecialchars($order['total']) ?></td>
                        <td>
                            <a href="delivery_management.php?action=mark_as_shipped&order_id=<?= $order['order_id'] ?>"
                                class="btn btn-green">Mark as Shipped</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
                    </main>
                    <main class="admin-main">
        <section class="admin-section">
            <h2>Shipped Orders</h2>
            <!-- Shipped Orders Table -->
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                         <th>User Name</th>
                         <th>Order Date</th>
                          <th>Delivery Date</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shippedOrders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td><?= htmlspecialchars($order['delivery_date']) ?></td>
                        <td><?= htmlspecialchars($order['total']) ?></td>
                        <td>
                            <a href="delivery_management.php?action=mark_as_delivered&order_id=<?= $order['order_id'] ?>"
                                class="btn btn-blue">Mark as Delivered</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
                    </main>
                    <main class="admin-main">

        <section class="admin-section">
            <h2>Delivered Orders</h2>
            <!-- Delivered Orders Table -->
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deliveredOrders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td><?= htmlspecialchars($order['delivery_date']) ?></td>
                        <td><?= htmlspecialchars($order['total']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </main>

</body>

</html>