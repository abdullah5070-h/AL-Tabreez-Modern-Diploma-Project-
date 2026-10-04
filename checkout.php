<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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

$user_id = $_SESSION['user_id'] ?? 0;
$errorMessage = "";
$cardHolderName = $cardNumber = $cardExpiry = $cardCvv = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $paymentType = $_POST['payment_type'];
     $deliveryDate = $_POST['delivery_date'];

    if ($paymentType === 'card') {
        $cardHolderName = $_POST['card_holder_name'];
        $cardNumber = $_POST['card_number'];
        $cardExpiry = $_POST['card_expiry'];
        $cardCvv = $_POST['card_cvv'];

        if (empty($cardHolderName) || empty($cardNumber) || empty($cardExpiry) || empty($cardCvv)) {
            $errorMessage = "Please fill out all payment information.";
        } elseif (!preg_match("/^\d{4}\d{4}\d{4}\d{4}$/", $cardNumber)) {
            $errorMessage = "Invalid card number format.";
        } elseif (strtotime($cardExpiry) < strtotime('2023-12-26')) {
            $errorMessage = "Your Card has expired ";
        }
    }

    if (!$errorMessage) {
        // Begin database transaction
    $pdo->beginTransaction();
    
    $order_id = 0; // Initialize $order_id
    try {
        // Calculate the total cost
        $total_cost = 0;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            $total_cost += $product['price'] * $quantity;
        }

// Insert order into orders table
    $orderStmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, delivery_date) VALUES (?, ?, 'pending', ?)");
    $orderStmt->execute([$user_id, $total_cost, $deliveryDate]);
     $order_id = $pdo->lastInsertId();
        // Insert each item into order_details table
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            $detailStmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $detailStmt->execute([$order_id, $product_id, $quantity, $product['price']]);
        }

        // Insert payment details into payments table
        
        $paymentStmt = $pdo->prepare("INSERT INTO payments (order_id, payment_type, card_holder_name, card_number, card_expiry, card_cvv, payment_status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $paymentStmt->execute([$order_id, $paymentType, $cardHolderName, $cardNumber, $cardExpiry, $cardCvv]);
        // dummy bank

        // Calculate the total cost
        $total_cost = 0;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            $total_cost += $product['price'] * $quantity;
        }

        $bankValueStmt = $pdo->prepare("SELECT bank_value FROM user_bank WHERE user_id = ?");
        $bankValueStmt->execute([$user_id]); // use $user_id, which is set at the beginning
        $bankValue = $bankValueStmt->fetchColumn();

        $newBankValue = $bankValue - $total_cost; // use $total_cost instead of $orderTotal
        $updateBankStmt = $pdo->prepare("UPDATE user_bank SET bank_value = ? WHERE user_id = ?");
        $updateBankStmt->execute([$newBankValue, $user_id]); // again, use $user_id


        // Commit the transaction
        $pdo->commit();

        // Clear the cart session
        $_SESSION['cart'] = [];
        // Set a session variable to show a notification to the user
        $_SESSION['bank_notification'] = "OMR {$total_cost} deducted from your bank. New value is OMR {$newBankValue}.";


         // Redirect to the order summary page
        header("Location: order_summary.php?order_id=" . $order_id);
        exit;
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        $pdo->rollBack();
        $error_message = "Error processing checkout: " . $e->getMessage();
        
    }
    }
}
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

    <main class="checkout-main">
        <section class="checkout-section">
            <h2>Checkout</h2>
            <form action="checkout.php" method="post" class="checkout-form">
                <h3>Delivery Information</h3>
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                     <label for="delivery_date">Delivery Date:</label>
                     <input type="date" id="delivery_date" name="delivery_date" required>
              </div>
                <h3>Payment Information</h3>
                <div class="form-group">
                    <label for="payment_type">Payment Type:</label>
                    <select name="payment_type" id="payment_type" required>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                    </select>
                </div>
                <div id="card_details" style="display:none;">
                    <div class="form-group">
                        <label for="card_holder_name">Card Holder Name:</label>
                        <input type="text" id="card_holder_name" name="card_holder_name" value="<?= $cardHolderName ?>">
                    </div>
                    <div class="form-group">
                        <label for="card_number">Card Number:</label>
                        <input type="text" id="card_number" name="card_number" placeholder="16 digits" value="<?= $cardNumber ?>">
                    </div>
                    <div class="form-group">
                        <label for="card_expiry">Card Expiry Date:</label>
                        <input type="month" id="card_expiry" name="card_expiry" value="<?= $cardExpiry ?>">
                    </div>
                    <div class="form-group">
                        <label for="card_cvv">Card CVV:</label>
                        <input type="text" id="card_cvv" name="card_cvv" value="<?= $cardCvv ?>">
                    </div>
                </div>
                <?php if ($errorMessage): ?>
                <div class="error-message" style="color: red;">                    <?= $errorMessage ?>
                </div>
                <?php endif; ?>
                <button type="submit" class="btn-checkout">Place Order</button>
            </form>
        </section>
        
        
        
   </main>

    <script>
    document.getElementById('payment_type').addEventListener('change', function() {
        var cardDetailsDiv = document.getElementById('card_details');
        cardDetailsDiv.style.display = this.value === 'card' ? 'block' : 'none';
    });
    </script>

</body>

</html>