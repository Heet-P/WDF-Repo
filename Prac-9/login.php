<?php
require_once 'config.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
$success = '';

// Check for logout message
if (isset($_GET['message']) && $_GET['message'] === 'logged_out') {
    $success = 'You have been successfully logged out.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                startSession();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username/email or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WDF Login System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header {
            margin-bottom: 30px;
        }
        
        .user-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        h3 {
            color: #333;
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: bold;
        }
        
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .alert-danger {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }
        
        .alert-success {
            background-color: #efe;
            border: 1px solid #cfc;
            color: #3c3;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .text-center {
            text-align: center;
        }
        
        .link {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        
        .link:hover {
            text-decoration: underline;
        }
        
        .divider {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #eee;
        }
        
        .demo-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-size: 12px;
            color: #666;
        }
        
        .demo-info strong {
            color: #333;
        }
        
        .icon {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="user-icon">👤</div>
                <h3>Welcome Back</h3>
                <p class="subtitle">Please sign in to your account</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    ✅ <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">
                        <span class="icon">👤</span>Username or Email
                    </label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        <span class="icon">🔒</span>Password
                    </label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <span class="icon">🔑</span>Sign In
                    </button>
                </div>
            </form>

            <div class="text-center">
                <p>Don't have an account? 
                    <a href="register.php" class="link">Sign up here</a>
                </p>
            </div>

            <hr class="divider">
            
            <div class="demo-info">
                <strong>Demo Account:</strong><br>
                Username: admin<br>
                Password: password123
            </div>
        </div>
    </div>

</body>
</html>
