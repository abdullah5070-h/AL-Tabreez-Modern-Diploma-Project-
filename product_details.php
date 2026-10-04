<?php
session_start();
require 'dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Get the product ID from the query string
$product_id = $_GET['product_id'] ?? 0;

// Fetch product details from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// Initialize $category
$category = '';

// Check if the product is not found
if (!$product) {
    echo "Product not found.";
    // You might want to handle this case differently, e.g., redirect to a products listing page
} else {
    // Product found, set the category
    $category = $product['category'];
}// Get the category from the query string
$category = $_GET['category'] ?? '';

// Fetch products based on the category
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
$stmt->execute([$category]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .product-image {
            position: relative;
            cursor: pointer;
            display: inline-block;
        }

        .product-image img {
            max-width: 100%;
            transition: transform 0.25s ease;
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

    <header>
        <h1>Welcome to Our Online Store</h1>
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

    <h1>Products in <?php echo $category; ?></h1>
<?php if (isset($products) && is_array($products) && !empty($products)): ?>        <?php foreach ($products as $product): ?>
            <main class="product-details-main">
                <section class="product-details">
                    <div class="product-image" onclick="zoomImage(this)">
                        <?php if (isset($product['image_path'])): ?>
                            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <p>Invalid product data.</p>
                        <?php endif; ?>
                    </div>

                    <div class="product-info">
                        <?php if (isset($product['name'])): ?>
                            <h2><?= htmlspecialchars($product['name']) ?></h2>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p class="price">OMR<?= number_format($product['price'], 2) ?></p>
                            <div class="product-actions">
                                <form action="cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>" required>
                                    <button type="submit" class="btn-add-to-cart">Add to Cart</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <p>No products</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products available in this category.</p>
    <?php endif; ?>
    <script>
        function zoomImage(element) {
            var img = element.querySelector('img');
            if (img.style.transform === 'scale(2)') {
                img.style.transform = 'scale(1)';
            } else {
                img.style.transform = 'scale(2)';
            }
        }
    </script>

</body>

</html>
