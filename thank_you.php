<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    
    <style>
    
    .nav-list {
    display: flex;
    list-style-type: none;
}

.nav-list li {
    margin-right: 10px;
}


.nav-list a {
    text-decoration: none;
    color: white; 
    font-weight: bold;
}


.nav-list a:hover {
    color: #555; 
}

.sublist {
    display: none;
    position: absolute;
    background-color: #333; 
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); 
    z-index: 1;
}

.nav-list li:hover .sublist {
    display: block;
}

.sublist li {
    display: block;
}		
        

    
    </style>
 </head>

<body>

    <?php
    session_start();
    require 'dbconnect.php';

    if (isset($_SESSION['bank_notification'])) {
        $notification = $_SESSION['bank_notification'];
        // Clear the notification session variable so it doesn't show again on refresh
        unset($_SESSION['bank_notification']);
    } else {
        $notification = '';
    }

    // Retrieve the order_id from the query string
    $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

    if ($order_id === 0) {
        echo "<p>Invalid order ID. Please check your information and try again.</p>";
        exit;
    }

    // Fetch the order details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    if (!$order) {
        echo "<p>Order not found. Please check your information and try again.</p>";
        exit;
    }

    if ($_SESSION['user_id'] !== $order['user_id']) {
        header("Location: login.php");
    }

    // Fetch bank details from the database
    $bankStmt = $pdo->prepare("SELECT * FROM user_bank WHERE user_id = ?");
    $bankStmt->execute([$_SESSION['user_id']]);
    $bankDetails = $bankStmt->fetch();

    // Calculate remaining bank value
    $remainingValue = isset($bankDetails['bank_value']) ? $bankDetails['bank_value'] : 0;

    // Calculate the total cost of the order
    $total_cost = 0;
    $orderDetailsStmt = $pdo->prepare("SELECT * FROM order_details WHERE order_id = ?");
    $orderDetailsStmt->execute([$order_id]);
    $orderDetails = $orderDetailsStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orderDetails as $detail) {
        $total_cost += $detail['price'] * $detail['quantity'];
    }

    // Calculate the deducted amount
    $deductedAmount = $total_cost;

    // Update the remaining bank value
    $remainingValue -= $deductedAmount;

    // Update the bank value in the database
    $updateBankStmt = $pdo->prepare("UPDATE user_bank SET bank_value = ? WHERE user_id = ?");
    $updateBankStmt->execute([$remainingValue, $_SESSION['user_id']]);
    ?>

    <header>
        <img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
            style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle; ">
        <h1>Welcome to Al Tabreez Modern</h1>
        <nav class="navbar">
         <ul class="nav-list">
           <a href="index.php">Home</a>
        <li>
            <a href="index.php">category</a>
            
            <ul class="sublist">
                <li><a href="product_details.php?category=Cap">Cap</a></li>
                <li><a href="product_details.php?category=Stick">Stick</a></li>
				<li><a href="product_details.php?category=Musar">Musar</a></li>
               
                
            </ul>
        </li>
             <li>
		<a href="">Cart</a>
		   <ul class="sublist">
                <li><a href="cart.php">My cart</a></li>
				<li><a href="checkout.php"> Proceed to Checkout</a></li>
			    <li><a href="remove_cart.php" id="remove-cart">Remove Cart</a> </li>

              
                
            </ul>
		</li>
        <li>
		<a href="">Orders</a>
		   <ul class="sublist">
                <li><a href="orders.php">Order history</a></li>
               
                
            </ul>
		</li>
		   <li>
		<a href=''>Profile</a>
		   <ul class="sublist">
                <li><a href="profile.php">Edit Profile</a></li>
                 <li><a href="logout.php">Logout</a></li>

				
                
            </ul>
		</li>

        </ul>
</nav>

    </header>

    <main class="thank-you-container">
        <div class="thank-you-message">
            <h1>Thank You for Your Order!</h1>
            <p>Your order number is <strong><?= htmlspecialchars($order_id) ?></strong>.</p>
            <p>We are currently processing your order and will send you a confirmation email shortly.</p>
            <div class="continue-shopping">
                <a href="index.php" class="continue-shopping-link">Continue Shopping</a>
            </div>
        </div>
      
        <?php if ($bankDetails): ?>
        <div class="bank-details" style="background-color: #28a745; color: white; padding: 10px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); margin-top: 20px;">
            <h2>Bank Details:</h2>
            <?php if (isset($bankDetails['bank_name'])): ?>
                <p>Bank Name: <?= htmlspecialchars($bankDetails['bank_name']) ?></p>
            <?php endif; ?>
            <?php if (isset($bankDetails['account_number'])): ?>
                <p>Account Number: **** **** **** <?= substr($bankDetails['account_number'], -4) ?></p>
            <?php endif; ?>
             <p> <?= number_format($deductedAmount, 2) ?>  OMR Deducted from your bank</p>

            <p>Your remaining Bank Value is: <?= number_format($remainingValue, 2) ?>  OMR</p>
                   </div>        <?php endif; ?>
    </main>

</body>

</html>

