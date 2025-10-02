<?php
session_start();
require_once 'classes/Admin.php';

$admin = new Admin();
$admin->requireAuth();

$currentAdmin = $admin->getCurrentAdmin();
$allAdmins = $admin->getAllAdmins();

// Handle AJAX requests for admin status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_admin_status' && isset($_POST['admin_id'])) {
        $adminId = (int)$_POST['admin_id'];
        $newStatus = $_POST['status'] === 'true' ? 1 : 0;
        
        // Prevent self-deactivation
        if ($adminId === $currentAdmin['id']) {
            echo json_encode(['success' => false, 'message' => 'Cannot deactivate your own account']);
            exit;
        }
        
        $success = $admin->updateStatus($adminId, $newStatus);
        
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Admin status updated successfully' : 'Failed to update admin status'
        ]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-primary: #667eea;
            --admin-secondary: #764ba2;
        }

        body {
            background-color: #f8f9fa;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .admin-sidebar {
            background: white;
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 76px;
        }

        .sidebar-item {
            padding: 12px 20px;
            border-bottom: 1px solid #eee;
            color: #495057;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
            text-decoration: none;
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
        }

        .admin-badge {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .status-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
        }

        .status-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 28px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #28a745;
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        .admin-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .admin-card:hover {
            transform: translateY(-5px);
        }

        .current-admin {
            border: 2px solid var(--admin-primary);
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>Admin Users
                    </h4>
                </div>
                <div class="col-md-6 text-end">
                    <span class="admin-badge me-3">
                        <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($currentAdmin['full_name']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="admin-sidebar">
                    <a href="admin-dashboard.php" class="sidebar-item">
                        <i class="fas fa-chart-bar me-2"></i>Dashboard
                    </a>
                    <a href="admin-events.php" class="sidebar-item">
                        <i class="fas fa-calendar-alt me-2"></i>Manage Events
                    </a>
                    <a href="admin-users.php" class="sidebar-item active">
                        <i class="fas fa-users me-2"></i>Admin Users
                    </a>
                    <a href="create.php" class="sidebar-item">
                        <i class="fas fa-plus me-2"></i>Add Event
                    </a>
                    <a href="index.php" class="sidebar-item" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Public View
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Administrator Management</h2>
                        <div class="alert alert-info mb-0" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            You can activate/deactivate admin accounts using the toggle switches
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($allAdmins as $adminUser): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card admin-card <?php echo $adminUser['id'] == $currentAdmin['id'] ? 'current-admin' : ''; ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title text-primary">
                                                    <?php echo htmlspecialchars($adminUser['full_name']); ?>
                                                    <?php if ($adminUser['id'] == $currentAdmin['id']): ?>
                                                        <span class="badge bg-warning text-dark ms-2">You</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i>
                                                        @<?php echo htmlspecialchars($adminUser['username']); ?>
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <label class="status-switch">
                                                    <input type="checkbox" 
                                                           <?php echo $adminUser['is_active'] ? 'checked' : ''; ?>
                                                           <?php echo $adminUser['id'] == $currentAdmin['id'] ? 'disabled' : ''; ?>
                                                           onchange="toggleAdminStatus(<?php echo $adminUser['id']; ?>, this)">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="fas fa-envelope me-1"></i>
                                                <?php echo htmlspecialchars($adminUser['email']); ?>
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-plus me-1"></i>
                                                Joined: <?php echo date('M d, Y', strtotime($adminUser['created_at'])); ?>
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge <?php echo $adminUser['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $adminUser['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                            
                                            <?php if ($adminUser['id'] == $currentAdmin['id']): ?>
                                                <small class="text-muted">Current Session</small>
                                            <?php else: ?>
                                                <small class="text-muted">
                                                    <?php echo $adminUser['is_active'] ? 'Can Login' : 'Login Disabled'; ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning" role="alert">
                                <h6 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Important Notes:
                                </h6>
                                <ul class="mb-0">
                                    <li>You cannot deactivate your own account</li>
                                    <li>Inactive admins cannot log in to the system</li>
                                    <li>Default password for demo accounts is: <strong>password</strong></li>
                                    <li>In production, ensure all admins use strong passwords</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleAdminStatus(adminId, checkbox) {
            const newStatus = checkbox.checked;
            
            // Send AJAX request to update admin status
            fetch('admin-users.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ajax=1&action=toggle_admin_status&admin_id=${adminId}&status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the badge
                    const card = checkbox.closest('.card');
                    const badge = card.querySelector('.badge');
                    badge.textContent = newStatus ? 'Active' : 'Inactive';
                    badge.className = `badge ${newStatus ? 'bg-success' : 'bg-secondary'}`;
                    
                    // Update the helper text
                    const helperText = card.querySelector('.text-muted:last-child');
                    if (helperText && !helperText.textContent.includes('Current Session')) {
                        helperText.textContent = newStatus ? 'Can Login' : 'Login Disabled';
                    }
                    
                    // Show success message
                    alert('Admin status updated successfully!');
                } else {
                    // Revert checkbox state on error
                    checkbox.checked = !checkbox.checked;
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                checkbox.checked = !checkbox.checked;
                alert('Network error occurred');
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
