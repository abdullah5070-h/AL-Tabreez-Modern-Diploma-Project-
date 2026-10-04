<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <header>
    <img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
            style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle; ">
        <h1>Welcome to Al Tabreez Modern</h1>
        
    </header>

    <section>
        <form action="login.php" method="post" id="loginForm">
            <h2>User Login</h2>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
            <p> Are you Admin ? <a href="admin_login.php"> Click Here </a> </p>
            <br>
            <p> Don't have an account ? <a href="register.php"> Register Now </a> </p>


        </form>
    </section>

    <?php
    require 'dbconnect.php'; // Include the database connection
    session_start(); // Start the session

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Simple PHP validation for empty fields
        if (empty($username) || empty($password)) {
            echo "<p>Please enter username and password.</p>";
        } else {
            // Prepare SQL
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // Verify password and check if user exists
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['user_id'];

                // Redirect to the home page
                header("Location: index.php"); 
                exit;
            } else {
                echo "<p>Incorrect username or password.</p>";
            }
        }
    }
    ?>

</body>
</html>
