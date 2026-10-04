<?php
session_start();
require 'dbconnect.php';

// Check for admin session to secure access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Initialize an error message
$error_message = '';

// Check if we have a user_id query parameter
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Fetch user details if we have a user_id
if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract posted form data
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update the user's details
    $updateStmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $result = $updateStmt->execute([$username, $email, $user_id]);

    if ($result) {
        $_SESSION['message'] = "User updated successfully.";
        header("Location: usermanagement.php");
        exit;
    } else {
        $error_message = "There was a problem updating the user.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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

<main>
    <h2>Edit User</h2>
    
    <?php if ($error_message): ?>
    <p class="error"><?= $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="edit_user.php?user_id=<?= htmlspecialchars($user_id); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

        <button type="submit">Update User</button>
    </form>
</main>

</body>
</html>
