<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Clean up remember token if it exists
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $tokens = getTokens();
    
    // Remove token from storage
    unset($tokens[$token]);
    saveTokens($tokens);
    
    // Delete the cookie
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
}

// Destroy session
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Secure System</title>
    <link rel="stylesheet" href="styles.css">
    <meta http-equiv="refresh" content="3;url=index.php">
</head>
<body>
    <div class="container">
        <h1>Logout Successful</h1>
        
        <div class="alert alert-success">
            You have been securely logged out!
        </div>
        
        <div style="text-align: center; margin: 2rem 0;">
            <p style="color: #2d4a22; margin-bottom: 1rem;">✅ Session cleared</p>
            <p style="color: #2d4a22; margin-bottom: 1rem;">✅ Remember me cookie removed</p>
            <p style="color: #2d4a22; margin-bottom: 1rem;">✅ All authentication data cleared</p>
        </div>
        
        <div style="text-align: center;">
            <p style="color: #2d4a22; margin-bottom: 1rem;">You will be redirected to the home page in 3 seconds...</p>
            <a href="index.php" class="btn" style="display: inline-block; text-decoration: none; width: auto; padding: 0.75rem 2rem;">
                Go to Home Page Now
            </a>
        </div>
    </div>
</body>
</html>
