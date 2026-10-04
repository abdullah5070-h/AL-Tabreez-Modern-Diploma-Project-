<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    
     <style>
     
     	/* menu bar */	
		
ul, li {
    margin: 0;
    padding: 0;
    list-style-type: none;
}

  
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
 

.cart-section {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.cart-section table {
    width: 80%;
     margin: 0 auto; /* Center the table */
    border-collapse: collapse;
}

.cart-section th, .cart-section td {
    border: 1px solid #ddd;
   padding: 26px;
    text-align: left;
    font-size:16px;
     width: 200%;
}

.cart-section th {
    background-color: #0F4B46;
    color: white;
}

.cart-section td {
    background-color: #f2f2f2;
}
      
  </style>

    
</head>

<body>

    <?php
session_start();
require 'dbconnect.php';

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove product from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $remove_product_id = $_POST['remove_product_id'];
    unset($_SESSION['cart'][$remove_product_id]);
}


// Update quantity in the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    foreach ($_POST['update_quantity'] as $product_id => $quantity) {
        // Validate and update the quantity
        if (isset($_SESSION['cart'][$product_id]) && $quantity >= 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
}
// Fetch product details for the cart
$cart_items = [];
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    if ($product) {
        $cart_items[$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image_path' => $product['image_path'],
        ];
    }
}

?>

    <header>
        <img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
            style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle; ">
        <h1>Welcome to Al Tabreez Modern</h1>
         <nav class="navbar">
<ul class="nav-list">
    <li><a href="index.php">Home</a></li>
    <li>
        <a href="#">category</a>
        <ul class="sublist">
            <li><a href="product_details.php?category=Cap">Cap</a></li>
            <li><a href="product_details.php?category=Stick">Stick</a></li>
            <li><a href="product_details.php?category=Musar">Musar</a></li>
        </ul>
    </li>
    <li>
        <a href="#">Cart</a>
        <ul class="sublist">
            <li><a href="cart.php">My cart</a></li>
            <li><a href="checkout.php">Proceed to Checkout</a></li>
            <li><a href="remove_cart.php" id="remove-cart">Remove Cart</a></li>
        </ul>
    </li>
    <li>
        <a href="#">Orders</a>
        <ul class="sublist">
            <li><a href="orders.php">Order history</a></li>
        </ul>
    </li>
    <li>
        <a href="#">Profile</a>
        <ul class="sublist">
            <li><a href="profile.php">Edit Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </li>
</ul>
</nav>
</header>
      <section class="cart-section" >
        <h2>Your Shopping Cart</h2>
        <?php if (!empty($cart_items)): ?>
            <form action="cart.php" method="post" >
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($cart_items as $product_id => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($item['image_path']) ?>" width="200px" height="200px"
                                     alt="<?= htmlspecialchars($item['name']) ?>">
                            </td>
                            <td><?= number_format($item['price'], 2) ?> OMR</td>
                            <td>
                                <input type="number" name="update_quantity[<?= $product_id ?>]" value="<?= $item['quantity'] ?>" min="0">
                            </td>
                            <td> <?= number_format($item['quantity'] * $item['price'], 2) ?> OMR</td>
                            <td>
                                <button type="submit" name="remove_product_id" value="<?= $product_id ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="checkout">
                    <button type="submit" name="update_quantities">Update Quantities</button>
                    <button type="button" onclick="window.location.href='checkout.php';">Proceed to Checkout</button>
                </div>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </section>    </body>

</html>