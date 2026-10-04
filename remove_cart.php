<?php
session_start();

// Check if there's an existing cart
if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Clear the entire cart
    $_SESSION['cart'] = [];

    // Optionally, perform other logic related to clearing the cart

    // Redirect back to the cart page or wherever you want
    header("Location: cart.php");
    exit;
}

// If there's no existing cart, redirect to the cart page
header("Location: cart.php");
exit;
?>
