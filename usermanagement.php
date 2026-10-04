<?php
session_start();
require 'dbconnect.php';

// Admin authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all users
$usersStmt = $pdo->query("SELECT * FROM users");
$users = $usersStmt->fetchAll();

//handle deletng

if (isset($_GET['action'], $_GET['user_id']) && $_GET['action'] == 'delete') {
    $user_id = $_GET['user_id'];

    // Check for existing orders for the user before attempting to delete
    $orderCheckStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
    $orderCheckStmt->execute([$user_id]);
    $orderCount = $orderCheckStmt->fetchColumn();

    if ($orderCount > 0) {
        // User has orders, cannot delete
        $_SESSION['error_message'] = "This user has orders and cannot be deleted.";
    } else {
        // No orders, safe to delete
        try {
            $deleteStmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
            $deleteStmt->execute([$user_id]);
            $_SESSION['success_message'] = "User deleted successfully.";
        } catch (PDOException $e) {
            // Handle exception if something goes wrong with the database operation
            $_SESSION['error_message'] = "Error deleting user: " . $e->getMessage();
        }
    }

    header("Location: usermanagement.php");
    exit;
}


// Handle adding new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insertStmt->execute([$username, $email, $password]);
            $_SESSION['success_message'] = "User added successfully.";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error adding user: " . $e->getMessage();
        }

        header("Location: usermanagement.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" type="text/css" href="admin_styles.css">
    <style>

.error-message {
    color: #D8000C;
    background-color: #FFD2D2;
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
}

.success-message {
    color: #4F8A10;
    background-color: #DFF2BF;
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
}
</style>

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
    
        <section class="admin-section">
            <h2>User Management</h2>
            <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?= $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); // Clear the message after displaying it ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?= $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); // Clear the message after displaying it ?>
            </div>
        <?php endif; ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <a href="edit_user.php?user_id=<?= $user['user_id'] ?>" class="btn btn-blue">Edit</a>
                            <a href="usermanagement.php?action=delete&user_id=<?= $user['user_id'] ?>"
                                class="btn btn-red"
                                onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3><b>Adding new user:
            </b></h3>
               <!-- Add new user form -->
            <form method="POST" action="usermanagement.php">
                <label for="username">Username:</label>
                <input type="text" name="username" required>

                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" required>

                <input type="submit" name="add_user" value="Add User">
            </form>
        </section>
    </main>

</body>

</html>