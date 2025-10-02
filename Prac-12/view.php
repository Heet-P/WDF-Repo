<?php
session_start();
require_once 'classes/Event.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid event ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit;
}

$event = new Event();
$eventData = $event->readById($_GET['id']);

if (!$eventData) {
    $_SESSION['message'] = "Event not found.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-eye me-2"></i>Event Details
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h2 class="text-primary"><?php echo htmlspecialchars($eventData['title']); ?></h2>
                        <?php if ($eventData['status'] === 'open'): ?>
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-door-open me-1"></i>Open for Registration
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary fs-6">
                                <i class="fas fa-door-closed me-1"></i>Registration Closed
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($eventData['description'])): ?>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5><i class="fas fa-align-left me-2"></i>Description</h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($eventData['description'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-calendar text-primary me-2"></i>Date & Time
                                </h6>
                                <p class="card-text">
                                    <strong>Date:</strong> <?php echo date('F j, Y', strtotime($eventData['event_date'])); ?><br>
                                    <strong>Time:</strong> <?php echo date('g:i A', strtotime($eventData['event_time'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>Location
                                </h6>
                                <p class="card-text"><?php echo htmlspecialchars($eventData['location']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-users text-info me-2"></i>Participants
                                </h6>
                                <p class="card-text">
                                    <strong>Maximum:</strong> <?php echo $eventData['max_participants']; ?> people
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle text-warning me-2"></i>Event Info
                                </h6>
                                <p class="card-text">
                                    <strong>Created:</strong> <?php echo date('M j, Y', strtotime($eventData['created_at'])); ?><br>
                                    <strong>Last Updated:</strong> <?php echo date('M j, Y', strtotime($eventData['updated_at'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                            </a>
                            <div>
                                <a href="edit.php?id=<?php echo $eventData['id']; ?>" class="btn btn-warning me-2">
                                    <i class="fas fa-edit me-1"></i>Edit Event
                                </a>
                                <?php if ($eventData['status'] === 'open'): ?>
                                    <a href="actions.php?action=close&id=<?php echo $eventData['id']; ?>" 
                                       class="btn btn-secondary me-2"
                                       onclick="return confirmStatusChange('<?php echo htmlspecialchars($eventData['title']); ?>', 'close')">
                                        <i class="fas fa-door-closed me-1"></i>Close Registration
                                    </a>
                                <?php else: ?>
                                    <a href="actions.php?action=open&id=<?php echo $eventData['id']; ?>" 
                                       class="btn btn-success me-2"
                                       onclick="return confirmStatusChange('<?php echo htmlspecialchars($eventData['title']); ?>', 'open')">
                                        <i class="fas fa-door-open me-1"></i>Open Registration
                                    </a>
                                <?php endif; ?>
                                <a href="actions.php?action=delete&id=<?php echo $eventData['id']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirmDelete('<?php echo htmlspecialchars($eventData['title']); ?>')">
                                    <i class="fas fa-trash me-1"></i>Delete Event
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
