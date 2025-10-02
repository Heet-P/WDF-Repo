<?php
session_start();
require_once 'classes/Admin.php';
require_once 'classes/Event.php';

$admin = new Admin();
$admin->requireAuth();

$event = new Event();
$events = $event->readAll();
$currentAdmin = $admin->getCurrentAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Dashboard</title>
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

        .event-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .event-card:hover {
            transform: translateY(-5px);
        }

        .event-status {
            position: absolute;
            top: 15px;
            right: 15px;
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
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Manage Events
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
                    <a href="admin-events.php" class="sidebar-item active">
                        <i class="fas fa-calendar-alt me-2"></i>Manage Events
                    </a>
                    <a href="admin-users.php" class="sidebar-item">
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
                        <h2>Event Management</h2>
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Event
                        </a>
                    </div>

                    <?php if (empty($events)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">No Events Found</h4>
                            <p class="text-muted">Start by creating your first event!</p>
                            <a href="create.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Event
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($events as $eventData): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card event-card">
                                        <div class="card-body position-relative">
                                            <div class="event-status">
                                                <label class="status-switch">
                                                    <input type="checkbox" 
                                                           <?php echo $eventData['status'] === 'open' ? 'checked' : ''; ?>
                                                           onchange="toggleEventStatus(<?php echo $eventData['id']; ?>, this)">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>

                                            <h5 class="card-title text-primary">
                                                <?php echo htmlspecialchars($eventData['title']); ?>
                                            </h5>
                                            
                                            <?php if (!empty($eventData['description'])): ?>
                                                <p class="card-text text-muted small">
                                                    <?php echo htmlspecialchars(substr($eventData['description'], 0, 100)); ?>...
                                                </p>
                                            <?php endif; ?>

                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php echo date('M d, Y', strtotime($eventData['event_date'])); ?>
                                                </small>
                                            </div>

                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo date('h:i A', strtotime($eventData['event_time'])); ?>
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    <?php echo htmlspecialchars($eventData['location']); ?>
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-users me-1"></i>
                                                    Max: <?php echo $eventData['max_participants']; ?> participants
                                                </small>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge <?php echo $eventData['status'] === 'open' ? 'bg-success' : 'bg-secondary'; ?>">
                                                    <?php echo ucfirst($eventData['status']); ?>
                                                </span>
                                                
                                                <div class="btn-group" role="group">
                                                    <a href="view.php?id=<?php echo $eventData['id']; ?>" 
                                                       class="btn btn-outline-info btn-sm" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit.php?id=<?php echo $eventData['id']; ?>" 
                                                       class="btn btn-outline-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="actions.php?action=delete&id=<?php echo $eventData['id']; ?>" 
                                                       class="btn btn-outline-danger btn-sm" title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete this event?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEventStatus(eventId, checkbox) {
            const newStatus = checkbox.checked ? 'open' : 'closed';
            
            // Send AJAX request to update status
            fetch('admin-dashboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ajax=1&action=toggle_status&event_id=${eventId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the badge
                    const card = checkbox.closest('.card');
                    const badge = card.querySelector('.badge');
                    badge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                    badge.className = `badge ${data.new_status === 'open' ? 'bg-success' : 'bg-secondary'}`;
                    
                    // Show success message
                    alert('Status updated successfully!');
                } else {
                    // Revert checkbox state on error
                    checkbox.checked = !checkbox.checked;
                    alert('Error updating status: ' + data.message);
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
