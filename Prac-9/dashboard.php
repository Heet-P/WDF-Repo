<?php
require_once 'config.php';

// Require login to access dashboard
requireLogin();

// Get user information
try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        // User not found, destroy session
        startSession();
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    $error = 'Database error.';
    $user = ['username' => $_SESSION['username'], 'email' => '', 'created_at' => ''];
}

// Get some stats
$stats = [
    'login_time' => date('Y-m-d H:i:s'),
    'session_id' => substr(session_id(), 0, 8),
    'user_id' => $_SESSION['user_id'],
    'days_since_join' => $user['created_at'] ? (int)((time() - strtotime($user['created_at'])) / (60 * 60 * 24)) : 0
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WDF Login System</title>
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
            color: #333;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .nav-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }
        
        .dashboard-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .dashboard-header p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }
        
        .card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 12px 24px;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
            transform: translateY(-2px);
        }
        
        .stats-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            display: block;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        .welcome-message {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🔐 WDF Login System</div>
            <div class="nav-user">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <span>Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
                </div>
                <a href="logout.php" class="btn btn-danger">
                    🚪 Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome-message">
            <div class="alert-success">
                🎉 <strong>Welcome to your dashboard!</strong> You have successfully logged in to the WDF Login System.
            </div>
            <h2>Hello, <?php echo htmlspecialchars($user['username']); ?>! 👋</h2>
            <p>This is your secure dashboard. Here you can manage your account and access various features of the system.</p>
        </div>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Manage your account and explore system features</p>
        </div>

        <!-- Feature Cards -->
        <div class="cards-grid">
            <div class="card">
                <span class="card-icon">👤</span>
                <h3>Profile Management</h3>
                <p>Update your personal information, change your password, and manage your account settings.</p>
                <a href="#" class="btn btn-primary">Manage Profile</a>
            </div>

            <div class="card">
                <span class="card-icon">🔒</span>
                <h3>Security Settings</h3>
                <p>Configure two-factor authentication, review login history, and enhance your account security.</p>
                <a href="#" class="btn btn-primary">Security Settings</a>
            </div>

            <div class="card">
                <span class="card-icon">📊</span>
                <h3>Analytics</h3>
                <p>View your account statistics, login patterns, and system usage analytics.</p>
                <a href="#" class="btn btn-primary">View Analytics</a>
            </div>

            <div class="card">
                <span class="card-icon">⚙️</span>
                <h3>System Settings</h3>
                <p>Customize your experience with theme preferences, notifications, and system configurations.</p>
                <a href="#" class="btn btn-primary">Configure System</a>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <h3 style="margin-bottom: 30px; text-align: center;">Account Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $stats['user_id']; ?></span>
                    <div class="stat-label">User ID</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $stats['days_since_join']; ?></span>
                    <div class="stat-label">Days Since Join</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $stats['session_id']; ?></span>
                    <div class="stat-label">Session ID</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo date('H:i'); ?></span>
                    <div class="stat-label">Login Time</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
