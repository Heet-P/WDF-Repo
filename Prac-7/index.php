<?php
require_once 'config.php';

// If user is logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System - Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Our System</h1>
        
        <div style="text-align: center; margin: 2rem 0;">
            <p style="color: #2d4a22; margin-bottom: 1rem;">Please login to access your dashboard</p>
            <a href="login.php" class="btn" style="display: inline-block; text-decoration: none; width: auto; padding: 0.75rem 2rem;">Login</a>
        </div>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e6c200;">
            <p style="color: #2d4a22; font-size: 0.9rem;">
                <strong>Demo Credentials:</strong><br>
                Username: admin<br>
                Password: admin123
            </p>
        </div>
    </div>
</body>
</html>
