<?php
session_start();
require 'dbconnect.php';

$message = '';
$registration_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Login logic
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Query to select the user
        $stmt = $pdo->prepare("SELECT * FROM `staff` WHERE `username` = ? and `password` = ?");
        $stmt->execute([$username,$password]);
        $row = $stmt->fetch();

        if ($row) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin'] = $row['username']; 
            header("Location: admin.php");

            exit;
        } else {
            $message = 'Incorrect username or password.';
        }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
         
            margin: 0;
            padding: 0;
        }
        .container {
            width: 300px;
            margin: 0 auto;
            padding-top: 50px;
        }
        form {
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=submit] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background: #4cae4c;
        }
   .error-message {
        color: #FF0000;
        background: #f2dede;
        border: 1px solid #ebccd1;
        margin: 10px 0;
        padding: 10px;
        border-radius: 4px;
    }

    .success-message {
        color: #008000;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        margin: 10px 0;
        padding: 10px;
        border-radius: 4px;
    }

    a {
		color: #000080;
		text-decoration: none;
	}

    a:hover {
        color: #4cae4c;
    }    </style>
</head>
<body>


<header>
    <img src="images/logo.jpg" alt="Al Tabreez Modern Logo" class="header-logo"
         style="height: 100px; width: 100px; margin-right: 10px; vertical-align: middle;">
    <h1 style="text-align:center">Welcome to Al Tabreez Modern Admin Login</h1>
</header>


<div class="admin-login-form">
   
    <form action="admin_login.php" method="post">
     <h1>Admin Login</h1>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
            <?php if (!empty($message)): ?>
    <p class="<?= $registration_success ? 'success-message' : 'error-message' ?>"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>   

    
    <p> Don't have an account?: <a href="admin_register.php"> Register here</a></p>
<p> Back to user login :<a href="login.php"> Back</a></p>

 </form>

    </div>


</body>
</html>
