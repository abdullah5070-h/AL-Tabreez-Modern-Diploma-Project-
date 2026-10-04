<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
    
            
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1em;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

 .confirmation-message {
        color: green; /* Change the color to green */
        font-weight: bold;
        margin-bottom: 10px;

        h2 {
            color: #333;
        }

        p {
            margin: 10px 0;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .auto-style1 {
            text-align: center;
        }
    </style>
</head>

<body>

    <?php
    session_start();
    require 'dbconnect.php';

    $order_id = $_GET['order_id'] ?? 0;

    // Fetch order details based on the order_id
    $orderStmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $orderStmt->execute([$order_id]);
    $order = $orderStmt->fetch();

    // Fetch order details from the order_details table
    $detailsStmt = $pdo->prepare("SELECT * FROM order_details WHERE order_id = ?");
    $detailsStmt->execute([$order_id]);
    $orderDetails = $detailsStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <header>
        <h1 class="auto-style1">Order Summary</h1>
    </header>

    <main>
    
    <?php
    // Display cancel confirmation message if set
    if (isset($_SESSION['cancel_confirmation'])) {
        echo '<p class="confirmation-message">' . $_SESSION['cancel_confirmation'] . '</p>';
        unset($_SESSION['cancel_confirmation']); // Clear the session variable
        
             // Redirect button
            echo '<form method="post" action="index.php">';
            echo '<button type="submit" class="btn-redirect">Back</button>';
            echo '</form>'; }
    ?>
        <?php if ($order): ?>
            <p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
            <p><strong>Total Amount:</strong> OMR <?= number_format($order['total'], 2) ?></p>
            <p><strong>Status:</strong> <?= $order['status'] ?></p>
        <?php else: ?>
            <p>Order not found.</p>
        <?php endif; ?>

        <?php if ($orderDetails): ?>
            <h2>Order Details</h2>
            <ul>
                <?php foreach ($orderDetails as $detail): ?>
                    <li>
                        <strong>Product:</strong> <?= getProductTitle($detail['product_id']) ?><br>
                        <strong>Quantity:</strong> <?= $detail['quantity'] ?><br>
                        <strong>Price:</strong> OMR <?= number_format($detail['price'], 2) ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php
            if ($order):
                // Cancel Order button
                echo '<form method="post" action="cancel_order.php">';
                echo '<input type="hidden" name="order_id" value="' . $order_id . '">';
                echo '<button type="submit" class="btn-cancel-order">Cancel Order</button>';
                echo '</form>';

                // Done button with order_id
                echo '<form method="get" action="thank_you.php">';
                echo '<input type="hidden" name="order_id" value="' . $order_id . '">';
                echo '<button type="submit" class="btn-done">Done</button>';
                echo '</form>';
            endif;            ?>
        <?php else: ?>
            <p>No order details found.</p>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> ALTABREEZ MODERN
    </footer>

</body>

</html>

<?php
// Function to get the product title based on product_id
function getProductTitle($product_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT name FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    return $product ? $product['name'] : 'Product Not Found';
}
?>
