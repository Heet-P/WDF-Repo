<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$users = getUsers();
$userInfo = $users[$username];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Secure System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <div class="logo">🔐 Secure Dashboard</div>
            <div class="nav-links">
                <span style="color: #2d4a22; margin-right: 1rem;">Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></span>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
        
        <div class="welcome-card">
            <h2>🎉 Login Successful!</h2>
            <p>You have successfully logged into the secure system.</p>
        </div>
        
        <div class="user-info">
            <h3 style="color: #2d4a22; margin-bottom: 1rem;">👤 User Information</h3>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($userInfo['id']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>Account Created:</strong> <?php echo htmlspecialchars($userInfo['created_at']); ?></p>
            <p><strong>Session ID:</strong> <?php echo htmlspecialchars(session_id()); ?></p>
            <p><strong>Login Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
        
        <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
            <h3 style="color: #2d4a22; margin-bottom: 1rem;">🔒 Session & Cookie Status</h3>
            <p><strong>Session Active:</strong> ✅ Yes</p>
            <p><strong>Remember Me Cookie:</strong> 
                <?php echo isset($_COOKIE['remember_token']) ? '✅ Active' : '❌ Not Set'; ?>
            </p>
            <?php if (isset($_COOKIE['remember_token'])): ?>
                <p><strong>Remember Token:</strong> <?php echo substr($_COOKIE['remember_token'], 0, 16) . '...'; ?></p>
            <?php endif; ?>
        </div>
        
        <div style="background: linear-gradient(135deg, #e6c200 0%, #b8960a 100%); padding: 1.5rem; border-radius: 8px; color: #2d4a22;">
            <h3 style="margin-bottom: 1rem;">🛡️ Security Features</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 0.5rem;">✅ Session-based authentication</li>
                <li style="margin-bottom: 0.5rem;">✅ Secure password hashing</li>
                <li style="margin-bottom: 0.5rem;">✅ Remember me functionality</li>
                <li style="margin-bottom: 0.5rem;">✅ File-based user storage</li>
                <li style="margin-bottom: 0.5rem;">✅ CSRF protection ready</li>
                <li>✅ Secure logout with cleanup</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="logout.php" class="btn" style="background: linear-gradient(135deg, #c41e3a 0%, #8b0000 100%); color: white; width: auto; padding: 0.75rem 2rem; text-decoration: none;">
                🚪 Secure Logout
            </a>
        </div>
    </div>
</body>
</html>
