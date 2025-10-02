<?php
require_once 'config.php';

$error = '';
$success = '';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $users = getUsers();
        
        if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
            // Login successful
            $_SESSION['user_id'] = $users[$username]['id'];
            $_SESSION['username'] = $username;
            
            // Handle remember me functionality
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $tokens = getTokens();
                $tokens[$token] = $username;
                saveTokens($tokens);
                
                // Set cookie for 30 days
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
            }
            
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Secure System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Remember me for 30 days</label>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="links">
            <a href="index.php">← Back to Home</a>
        </div>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e6c200;">
            <p style="color: #2d4a22; font-size: 0.9rem;">
                <strong>Demo Credentials:</strong><br>
                Username: admin | Password: admin123
            </p>
        </div>
    </div>
</body>
</html>
