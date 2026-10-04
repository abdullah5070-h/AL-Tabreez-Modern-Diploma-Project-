<?php
require 'dbconnect.php';
$registration_message = '';
$registration_success = false; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['reg_username']);
    $password = trim($_POST['reg_password']);

    // Simple PHP validation for empty fields
    if (empty($username) || empty($password)) {
        $registration_message = "Please enter username and password.";
    } else {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT `username` FROM `staff` WHERE `username` = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $registration_message = "Username already exists!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin user into the database
            $insertStmt = $pdo->prepare("INSERT INTO `staff` (`username`, `password`) VALUES (?, ?)");
            $registration_success = $insertStmt->execute([$username, $hashed_password]);

            if ($registration_success) {
                $registration_message = "Admin registration successful!";
            } else {
                $registration_message = "Registration failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    
     <style>
        .success {
		 color: #008000;
	 }

        .error {
		 color: #FF0000;
	 }
    </style>
    
</head>
<body>

<header>
    <img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
         style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle;">
    <h1 style="text-align:center">Welcome to Al Tabreez Modern Admin Registration</h1>
</header>

<section>
    <form action="admin_register.php" method="post" id="adminRegisterForm">
        <h2>Register New Admin</h2>

        <?php if (!empty($registration_message)): ?>
            <p class="registration-message <?= $registration_success ? 'success' : 'error' ?>">
                <?= htmlspecialchars($registration_message) ?>
            </p>
        <?php endif; ?>

        <div class="form-group">
            <label for="reg_username">Username:</label>
            <input type="text" id="reg_username" name="reg_username" required>
        </div>

        <div class="form-group">
            <label for="reg_password">Password:</label>
            <input type="password" id="reg_password" name="reg_password" required>
        </div>

        <button type="submit" name="register">Register</button>
        <p>Already have an account? <a href="admin_login.php">Login here</a></p>
    </form>
</section>

</body>
</html>
