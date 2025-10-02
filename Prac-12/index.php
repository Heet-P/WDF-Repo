<?php
session_start();
require_once 'classes/Event.php';

$event = new Event();
$events = $event->readAll();
$openEvents = $event->getByStatus('open');
$closedEvents = $event->getByStatus('closed');

include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="display-4 text-center mb-4">
            <i class="fas fa-calendar-alt text-primary"></i> Event Dashboard
        </h1>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo count($events); ?></h4>
                        <p class="card-text">Total Events</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo count($openEvents); ?></h4>
                        <p class="card-text">Open Events</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-door-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-secondary card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo count($closedEvents); ?></h4>
                        <p class="card-text">Closed Events</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-door-closed fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-list me-2"></i>All Events</h2>
            <a href="create.php" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Add New Event
            </a>
        </div>
    </div>
</div>

<!-- Events Table -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($events)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                        <h3 class="text-muted">No Events Found</h3>
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
                                    <th>Title</th>
                                    <th>Date & Time</th>
                                    <th>Location</th>
                                    <th>Participants</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($events as $eventData): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($eventData['title']); ?></strong>
                                            <?php if (!empty($eventData['description'])): ?>
                                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($eventData['description'], 0, 50)); ?>...</small>
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
                                            <?php if ($eventData['status'] === 'open'): ?>
                                                <span class="badge bg-success status-badge">
                                                    <i class="fas fa-door-open me-1"></i>Open
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary status-badge">
                                                    <i class="fas fa-door-closed me-1"></i>Closed
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="view.php?id=<?php echo $eventData['id']; ?>" 
                                                   class="btn btn-info btn-sm btn-action" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit.php?id=<?php echo $eventData['id']; ?>" 
                                                   class="btn btn-warning btn-sm btn-action" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($eventData['status'] === 'open'): ?>
                                                    <a href="actions.php?action=close&id=<?php echo $eventData['id']; ?>" 
                                                       class="btn btn-secondary btn-sm btn-action" title="Close Event"
                                                       onclick="return confirmStatusChange('<?php echo htmlspecialchars($eventData['title']); ?>', 'close')">
                                                        <i class="fas fa-door-closed"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="actions.php?action=open&id=<?php echo $eventData['id']; ?>" 
                                                       class="btn btn-success btn-sm btn-action" title="Open Event"
                                                       onclick="return confirmStatusChange('<?php echo htmlspecialchars($eventData['title']); ?>', 'open')">
                                                        <i class="fas fa-door-open"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="actions.php?action=delete&id=<?php echo $eventData['id']; ?>" 
                                                   class="btn btn-danger btn-sm btn-action" title="Delete"
                                                   onclick="return confirmDelete('<?php echo htmlspecialchars($eventData['title']); ?>')">
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

<?php include 'includes/footer.php'; ?>
