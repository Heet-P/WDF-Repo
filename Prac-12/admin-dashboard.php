<?php
session_start();
require_once 'classes/Admin.php';
require_once 'classes/Event.php';

$admin = new Admin();
$admin->requireAuth();

$event = new Event();
$events = $event->readAll();
$openEvents = $event->getByStatus('open');
$closedEvents = $event->getByStatus('closed');
$currentAdmin = $admin->getCurrentAdmin();

// Handle AJAX requests for status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_status' && isset($_POST['event_id'])) {
        $eventId = (int)$_POST['event_id'];
        $currentEvent = $event->readById($eventId);
        
        if ($currentEvent) {
            $newStatus = $currentEvent['status'] === 'open' ? 'closed' : 'open';
            $success = $event->updateStatus($eventId, $newStatus);
            
            echo json_encode([
                'success' => $success,
                'new_status' => $newStatus,
                'message' => $success ? 'Status updated successfully' : 'Failed to update status'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Event not found']);
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-primary: #667eea;
            --admin-secondary: #764ba2;
            --sidebar-width: 250px;
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

        .sidebar-item:hover, .sidebar-item.active {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
            text-decoration: none;
        }

        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .status-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
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
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
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
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .admin-stats {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .quick-action-btn {
            border-radius: 10px;
            padding: 10px 20px;
            margin: 5px;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
        }

        .event-row {
            transition: all 0.3s ease;
        }

        .event-row:hover {
            background-color: #f8f9fa;
        }

        .admin-badge {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--admin-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
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
                    <a href="admin-dashboard.php" class="sidebar-item active">
                        <i class="fas fa-chart-bar me-2"></i>Dashboard
                    </a>
                    <a href="admin-events.php" class="sidebar-item">
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
                    <!-- Stats Overview -->
                    <div class="admin-stats">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h2><?php echo count($events); ?></h2>
                                <p class="mb-0">Total Events</p>
                            </div>
                            <div class="col-md-3">
                                <h2><?php echo count($openEvents); ?></h2>
                                <p class="mb-0">Open Events</p>
                            </div>
                            <div class="col-md-3">
                                <h2><?php echo count($closedEvents); ?></h2>
                                <p class="mb-0">Closed Events</p>
                            </div>
                            <div class="col-md-3">
                                <h2><?php echo date('H:i'); ?></h2>
                                <p class="mb-0">Current Time</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="mb-3">Quick Actions</h5>
                            <a href="create.php" class="btn btn-primary quick-action-btn">
                                <i class="fas fa-plus me-2"></i>Create Event
                            </a>
                            <button class="btn btn-success quick-action-btn" onclick="openAllEvents()">
                                <i class="fas fa-door-open me-2"></i>Open All Events
                            </button>
                            <button class="btn btn-secondary quick-action-btn" onclick="closeAllEvents()">
                                <i class="fas fa-door-closed me-2"></i>Close All Events
                            </button>
                            <a href="admin-events.php" class="btn btn-info quick-action-btn">
                                <i class="fas fa-list me-2"></i>Manage All Events
                            </a>
                        </div>
                    </div>

                    <!-- Events Management with Slide Switches -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dashboard-card card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-toggle-on me-2"></i>Event Status Management
                                    </h5>
                                    <small class="text-muted">Use the slide switches to quickly toggle event status</small>
                                </div>
                                <div class="card-body">
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
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Event Details</th>
                                                        <th>Date & Time</th>
                                                        <th>Location</th>
                                                        <th>Participants</th>
                                                        <th>Status Control</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($events as $eventData): ?>
                                                        <tr class="event-row" id="event-row-<?php echo $eventData['id']; ?>">
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($eventData['title']); ?></strong>
                                                                <?php if (!empty($eventData['description'])): ?>
                                                                    <br><small class="text-muted"><?php echo htmlspecialchars(substr($eventData['description'], 0, 60)); ?>...</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <i class="fas fa-calendar me-1"></i><?php echo date('M d, Y', strtotime($eventData['event_date'])); ?><br>
                                                                <i class="fas fa-clock me-1"></i><?php echo date('h:i A', strtotime($eventData['event_time'])); ?>
                                                            </td>
                                                            <td>
                                                                <i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($eventData['location']); ?>
                                                            </td>
                                                            <td>
                                                                <i class="fas fa-users me-1"></i><?php echo $eventData['max_participants']; ?>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <label class="status-switch me-2">
                                                                        <input type="checkbox" 
                                                                               id="status-<?php echo $eventData['id']; ?>"
                                                                               <?php echo $eventData['status'] === 'open' ? 'checked' : ''; ?>
                                                                               onchange="toggleEventStatus(<?php echo $eventData['id']; ?>)">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                    <div class="loading-spinner" id="loading-<?php echo $eventData['id']; ?>"></div>
                                                                    <span class="status-text" id="status-text-<?php echo $eventData['id']; ?>">
                                                                        <?php echo $eventData['status'] === 'open' ? 'Open' : 'Closed'; ?>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    <a href="view.php?id=<?php echo $eventData['id']; ?>" 
                                                                       class="btn btn-info btn-sm" title="View">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="edit.php?id=<?php echo $eventData['id']; ?>" 
                                                                       class="btn btn-warning btn-sm" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <a href="actions.php?action=delete&id=<?php echo $eventData['id']; ?>" 
                                                                       class="btn btn-danger btn-sm" title="Delete"
                                                                       onclick="return confirm('Are you sure you want to delete this event?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-bell text-primary me-2"></i>
                <strong class="me-auto">Admin Dashboard</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Status updated successfully!
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('statusToast');
            const toastMessage = document.getElementById('toastMessage');
            const toastHeader = toast.querySelector('.toast-header i');
            
            toastMessage.textContent = message;
            toastHeader.className = `fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-circle text-danger'} me-2`;
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Toggle event status function
        function toggleEventStatus(eventId) {
            const checkbox = document.getElementById(`status-${eventId}`);
            const statusText = document.getElementById(`status-text-${eventId}`);
            const loading = document.getElementById(`loading-${eventId}`);
            
            // Show loading spinner
            loading.style.display = 'inline-block';
            checkbox.disabled = true;
            
            // Send AJAX request
            fetch('admin-dashboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ajax=1&action=toggle_status&event_id=${eventId}`
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                checkbox.disabled = false;
                
                if (data.success) {
                    statusText.textContent = data.new_status === 'open' ? 'Open' : 'Closed';
                    showToast(data.message, 'success');
                } else {
                    // Revert checkbox state on error
                    checkbox.checked = !checkbox.checked;
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                checkbox.disabled = false;
                checkbox.checked = !checkbox.checked;
                showToast('Network error occurred', 'error');
                console.error('Error:', error);
            });
        }

        // Bulk actions
        function openAllEvents() {
            if (confirm('Are you sure you want to open all events?')) {
                // Implementation for bulk open
                showToast('Feature coming soon!', 'info');
            }
        }

        function closeAllEvents() {
            if (confirm('Are you sure you want to close all events?')) {
                // Implementation for bulk close
                showToast('Feature coming soon!', 'info');
            }
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
