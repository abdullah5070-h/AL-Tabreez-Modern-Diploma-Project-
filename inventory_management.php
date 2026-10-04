<?php
session_start();
require 'dbconnect.php';

// Check for admin session to secure access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // validate form inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $category= filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
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

    try {
        $pdo->beginTransaction();

        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO products (name,category, description, price, image_path, stock_quantity) VALUES (:name,:category, :description, :price,:ipath, :stock_quantity)");
        
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':ipath', $imagePath);
        $stmt->bindParam(':stock_quantity', $stock_quantity);

        // Execute the statement
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        $_SESSION['message'] = "Product added successfully!";
    } catch (PDOException $e) {
        // If an error occurs, roll back the transaction and save the error message in the session
        $pdo->rollBack();
        $_SESSION['error'] = "Error adding product: " . $e->getMessage();
    }
    header("Location: inventory_management.php");
    exit;
}

// Fetch all products from the database
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" type="text/css" href="admin_styles.css">
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
    <section class="inventory-management">
        <h2>Inventory Management</h2>

        <!-- Form to add a new product -->
        <form action="inventory_management.php" method="post" enctype="multipart/form-data">
            <h3>Add New Product</h3>
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="text" name="category" placeholder="Product category" required>
            <input type="text" name="description" placeholder="Product Description" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock_quantity" placeholder="Stock Quantity" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" name="action" value="add_product">Add Product</button>
        </form>

        <!-- List of products -->
        <h3>Current Inventory</h3>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                 <th>category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['product_id']) ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?></td>
                <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                <td>
                    <!-- Add action buttons or links for edit/delete -->
                    <a href="edit_product.php?id=<?= $product['product_id'] ?>">Edit</a>
                    <a href="delete_product.php?id=<?= $product['product_id'] ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

</body>
</html>
