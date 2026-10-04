<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>

    <header>
        <h1>Register</h1>
    </header>

    <section>
        <?php
    $message = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require 'dbconnect.php'; // Include the database connection
        session_start(); // Start the session

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $email = trim($_POST['email']);

        // Regular expression for password validation
        $passwordPattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/";

        // PHP validation
        if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
            $message = "<p>Please fill out all fields.</p>";
        } elseif ($password !== $confirm_password) {
            $message = "<p>Passwords do not match.</p>";
        } elseif (!preg_match($passwordPattern, $password)) {
            $message = "<p>Password must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters.</p>";
        } else {
            // Passwords match and meet the pattern, proceed with account creation
            $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

            try {
                // Begin transaction
                $pdo->beginTransaction();

                // Prepare SQL to prevent SQL injection
                $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
                $stmt->execute([$username, $password_hash, $email]);

                // Get the ID of the newly created user
                $user_id = $pdo->lastInsertId();

                // Set initial bank value for the new user
                $initialBankValue = 10000; // OMR 10,000 for every new user
                $bankStmt = $pdo->prepare("INSERT INTO user_bank (user_id, bank_value) VALUES (?, ?)");
                $bankStmt->execute([$user_id, $initialBankValue]);

                // Commit the transaction
                $pdo->commit();

                // Redirect to login page or display success message
                header("Location: login.php");
                exit;
            } catch (PDOException $e) {
                // If an error occurs, rollback the transaction
                $pdo->rollBack();
                $message = "<p>There was an error creating your account: " . $e->getMessage() . "</p>";
            }
        }
    }
?>



        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registerForm">
            <h2>Create Your Account</h2>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <?php if ($message): ?>
                <div class="alert">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
            </div>


            <button type="submit">Register</button>
        </form>
    </section>

    

</body>

</html>