<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// If it's desired to kill the session cookie, then use the following lines:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
