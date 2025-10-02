<?php
session_start();
require_once 'classes/Event.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event = new Event();
    
    $data = [
        'title' => trim($_POST['title']),
        'description' => trim($_POST['description']),
        'event_date' => $_POST['event_date'],
        'event_time' => $_POST['event_time'],
        'location' => trim($_POST['location']),
        'status' => $_POST['status'],
        'max_participants' => (int)$_POST['max_participants']
    ];
    
    // Validation
    $errors = [];
    
    if (empty($data['title'])) {
        $errors[] = "Event title is required.";
    }
    
    if (empty($data['event_date'])) {
        $errors[] = "Event date is required.";
    } elseif (strtotime($data['event_date']) < strtotime(date('Y-m-d'))) {
        $errors[] = "Event date cannot be in the past.";
    }
    
    if (empty($data['event_time'])) {
        $errors[] = "Event time is required.";
    }
    
    if (empty($data['location'])) {
        $errors[] = "Event location is required.";
    }
    
    if ($data['max_participants'] <= 0) {
        $errors[] = "Maximum participants must be greater than 0.";
    }
    
    if (empty($errors)) {
        if ($event->create($data)) {
            $_SESSION['message'] = "Event created successfully!";
            $_SESSION['message_type'] = "success";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['message'] = "Error creating event. Please try again.";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
        $_SESSION['message_type'] = "danger";
    }
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Create New Event
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="create.php">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>Event Title *
                            </label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                                   required maxlength="255">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                      placeholder="Enter event description..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="event_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Event Date *
                            </label>
                            <input type="date" class="form-control" id="event_date" name="event_date" 
                                   value="<?php echo isset($_POST['event_date']) ? $_POST['event_date'] : ''; ?>" 
                                   min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="event_time" class="form-label">
                                <i class="fas fa-clock me-1"></i>Event Time *
                            </label>
                            <input type="time" class="form-control" id="event_time" name="event_time" 
                                   value="<?php echo isset($_POST['event_time']) ? $_POST['event_time'] : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="location" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Location *
                            </label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>" 
                                   required maxlength="255" placeholder="Enter event location">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>Status
                            </label>
                            <select class="form-select" id="status" name="status">
                                <option value="open" <?php echo (isset($_POST['status']) && $_POST['status'] === 'open') ? 'selected' : ''; ?>>Open</option>
                                <option value="closed" <?php echo (isset($_POST['status']) && $_POST['status'] === 'closed') ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="max_participants" class="form-label">
                                <i class="fas fa-users me-1"></i>Maximum Participants *
                            </label>
                            <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                   value="<?php echo isset($_POST['max_participants']) ? $_POST['max_participants'] : ''; ?>" 
                                   min="1" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Event
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
