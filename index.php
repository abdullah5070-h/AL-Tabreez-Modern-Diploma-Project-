<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Online Store</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    
     <style>
      
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1em;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer a {
            color: white; /* Set the link color to white */
            text-decoration: none;
            cursor: pointer;
        }

        #aboutUsInfo, #contactUsInfo {
            display: none; /* Set display to none by default */
            margin-top: 20px;
             color: white; /* Set the text color inside the About Us and Contact Us sections */
        } 
		
	/* menu bar */	
		
ul, li {
    margin: 0;
    padding: 0;
    list-style-type: none;
}

.header-logo {
    height: 100px;
    width: 100px;
    vertical-align: middle;
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
        
  </style>
</head>

<body>

    <?php
session_start(); // Start the session
require 'dbconnect.php'; // Include the database connection

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Initialize variables for filtering and sorting
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'name';
$order = $_GET['order'] ?? 'ASC';

// Validate sort and order to prevent SQL injection
$allowedSorts = ['name', 'price'];
$allowedOrders = ['ASC', 'DESC'];

// Only allow predefined columns and directions
if (!in_array($sort, $allowedSorts, true)) {
    $sort = 'name';
}
if (!in_array($order, $allowedOrders, true)) {
    $order = 'ASC';
}

// Use placeholders for the search term and bind them later
$query = "SELECT * FROM products WHERE name LIKE :searchName OR description LIKE :searchDescription ORDER BY {$sort} {$order}";
$stmt = $pdo->prepare($query);

// Bind parameters for each placeholder
$stmt->bindParam(':searchName', $searchTerm);
$stmt->bindParam(':searchDescription', $searchTerm);
$searchTerm = '%'.$search.'%';

// Execute the query
$stmt->execute();
$products = $stmt->fetchAll();

?>

    <header>
        <img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo" style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle; ">
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
    <?php 

    if(isset($_SESSION['username']))
    {
        echo '<h3 style="text-align:center;background:#0F4B46; color: white;">Welcome '.$_SESSION["username"].' </h3>';
    }



?>
    <section class="search-sort-section">
        <form action="index.php" method="get">
            <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
            <select name="sort">
                <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option>
                <option value="price" <?= $sort === 'price' ? 'selected' : '' ?>>Price</option>
            </select>
            <select name="order">
                <option value="ASC" <?= $order === 'ASC' ? 'selected' : '' ?>>Ascending</option>
                <option value="DESC" <?= $order === 'DESC' ? 'selected' : '' ?>>Descending</option>
            </select>
            <button type="submit">Apply</button>
        </form>
    </section>

    <section class="product-section">
        <?php foreach ($products as $product): ?>
        <div class="product">
            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                style="width:100%;height:auto;">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <p class="price">OMR<?= number_format($product['price'], 2) ?></p>
            <a href="productinfo.php?product_id=<?= $product['product_id'] ?>" class="view-product-btn">View
                Product</a>
        </div>
        <?php endforeach; ?>
    </section>
    
    
      <footer>
        <p><a onclick="showAboutUs()">ABOUT US</a> | <a onclick="showContactUs()">&#9742; CONTACT US</a></p>

        <div id="aboutUsInfo">
            <p>Al Tabreez Modern is a shop that sells Omani hats, Omani turbans, and sticks. Al Tabreez Modern project began in 2018 and gained wide popularity and fame through Instagram, where it gained approximately 20k followers. Al Tabreez Modern aims to provide the finest types of Omani hats that are hand-woven and also uses the finest types of terma for the Omani turbans so that the buyer has a sophisticated and beautiful look. It also provides traditional sticks that are made of the finest types of wood and crafted in pure Omani silver to appear elegant and shiny.</p>
        </div>

        <div id="contactUsInfo">
            <p>For inquiries, please contact us:</p>
            <ul>
                <li>Instagram Account: <a href="https://www.instagram.com/al_tabreez" target="_blank">@al_tabreez</a></li>
                <li>&#9742; Phone No: +968 96498765</li>
            </ul>
        </div>
    </footer>

 <script>
    function showAboutUs() {
        var aboutUsInfo = document.getElementById("aboutUsInfo");
        var contactUsInfo = document.getElementById("contactUsInfo");

        aboutUsInfo.style.display = (aboutUsInfo.style.display === "none") ? "block" : "none";
        contactUsInfo.style.display = "none";
    }

    function showContactUs() {
        var aboutUsInfo = document.getElementById("aboutUsInfo");
        var contactUsInfo = document.getElementById("contactUsInfo");

        aboutUsInfo.style.display = "none";
        contactUsInfo.style.display = (contactUsInfo.style.display === "none") ? "block" : "none";
    }
</script>
</body>

</html>