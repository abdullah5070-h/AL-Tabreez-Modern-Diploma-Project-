<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    
    <style>
    
.nav-list {
	display: flex;
	list-style-type: upper-alpha;
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

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$message = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Validation (simple example)
    if (empty($username) || empty($email)) {
        $message = "Please fill all fields.";
    } else {
        // Prepare update statement
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        if ($stmt->execute([$username, $email, $_SESSION['user_id']])) {
            
            header("Location: profile.php"); 
        } else {
            $message = "An error occurred.";
        }
    }
}

// Fetch user data from the database
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

?>

<header>
    <h1>Welcome to Our Online Store</h1>
  <nav class="navbar">
       

    <ul class="nav-list">
        <li>
         <a href="index.php">Home</a>
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

<section class="profile-section">
    <h2>Edit Your Profile</h2>
    <p><?= $message ?></p>
    <form action="edit_profile.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <button type="submit">Update Profile</button>
    </form>
</section>

</body>
</html>
