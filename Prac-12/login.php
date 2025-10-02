<?php
session_start();
require_once 'classes/Admin.php';

$admin = new Admin();

// Redirect if already logged in
if ($admin->isLoggedIn()) {
    header('Location: admin-dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $adminData = $admin->authenticate($username, $password);
        if ($adminData) {
            $admin->login($adminData);
            header('Location: admin-dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .form-floating label {
            color: #6c757d;
        }
        .btn-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-admin:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
        }
        .demo-credentials {
            background: #f8f9fa;
            border-left: 4px solid #17a2b8;
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-user-shield fa-3x mb-3"></i>
                        <h2>Admin Login</h2>
                        <p class="mb-0">Event Management System</p>
                    </div>
                    <div class="login-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="login.php">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                                <label for="username">
                                    <i class="fas fa-user me-2"></i>Username
                                </label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Password" required>
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                            </div>

                            <button type="submit" class="btn btn-admin btn-lg w-100 text-white">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Admin Panel
                            </button>
                        </form>

                        <div class="demo-credentials">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-info-circle me-2"></i>Demo Credentials
                            </h6>
                            <small class="text-muted">
                                <strong>Username:</strong> admin<br>
                                <strong>Password:</strong> password<br>
                                <em>or</em><br>
                                <strong>Username:</strong> manager<br>
                                <strong>Password:</strong> password
                            </small>
                        </div>

                        <div class="text-center mt-3">
                            <a href="index.php" class="text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Back to Public View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
