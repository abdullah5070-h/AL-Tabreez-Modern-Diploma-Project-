<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <script>
        function handleLogout() {
            if (confirm("Are you sure you want to log out?")) {
                // If user clicked "OK", clear the session and redirect to login page
                document.getElementById('logout-form').submit();
            } else {
                // If user clicked "Cancel", redirect them back to the previous page
                window.history.back();
            }
        }

        window.onload = handleLogout; 
    </script>
</head>
<body>
    <form id="logout-form" action="logout.php" method="POST" style="display: none;">
        <input type="hidden" name="confirmed" value="1">
    </form>
    <noscript>
        JavaScript is required for the logout confirmation. Please enable JavaScript and try again.
    </noscript>
</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmed'])) {
    // Unset all session variables
    $_SESSION = array();

    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }

    // Destroy the session.
    session_destroy();

    // Redirect to the login page.
    header('Location: admin_login.php');
    exit;
}
?>
