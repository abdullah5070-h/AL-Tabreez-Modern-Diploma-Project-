<?php
session_start();
require 'dbconnect.php';

// Check for admin session to secure access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$product_id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock_quantity = filter_input(INPUT_POST, 'stock_quantity', FILTER_SANITIZE_NUMBER_INT);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Assign variables for the uploaded file
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file extensions
        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg', 'webp'];

        // Check if file has allowed type
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directory where the file will be stored
            $uploadFileDir = 'images/';
            // Create the directory if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            // Create a unique filename to prevent overwriting existing files
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            // Move the file to the images directory
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = $dest_path;
            } else {
                $_SESSION['error'] = 'Error moving the file.';
                header("Location: inventory_management.php");
                exit;
            }
        } else {
            $_SESSION['error'] = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            header("Location: inventory_management.php");
            exit;
        }
    } else {
        // Handle error or set a default image path
        $imagePath = 'images/product1.png';
    }

    // Update the product details in the database
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, image_path = ?, price = ?, stock_quantity = ? WHERE product_id = ?");
    $stmt->execute([$name, $description, $imagePath, $price, $stock_quantity, $product_id]);

    
    header("Location: inventory_management.php");
    exit;
}

// Fetch the product data for editing
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    exit('Product not found.');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" type="text/css" href="admin_styles.css">
</head>
<body>

<header>
<img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
            style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle; ">
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="inventory_management.php">Inventory Management</a>
        <a href="reports.php">Reports</a>
        <a href="delivery_management.php">Delivery Management</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="admin-main">
    <section class="edit-product">
        <h2>Edit Product</h2>
        <form action="edit_product.php?id=<?= htmlspecialchars($product_id) ?>" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" required>

            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($product['stock_quantity']) ?>" required>

            <input type="file" name="image" accept="image/*" value="<?= htmlspecialchars($product['image_path']) ?>">
            <button type="submit">Update Product</button>
        </form>
    </section>
</main>

</body>
</html>
