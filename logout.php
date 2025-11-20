<?php
// Find the session
session_start();

// Empty the session variables
$_SESSION = array();

// Remove cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session storage on server
session_destroy();

// Redirect to Login Page
header("Location: login.php");
exit();
?>