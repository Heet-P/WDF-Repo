<?php
session_start();
require_once 'classes/Event.php';

if (!isset($_GET['action']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid action or event ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit;
}

$event = new Event();
$eventId = $_GET['id'];
$action = $_GET['action'];

// Verify event exists
$eventData = $event->readById($eventId);
if (!$eventData) {
    $_SESSION['message'] = "Event not found.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit;
}

switch ($action) {
    case 'delete':
        if ($event->delete($eventId)) {
            $_SESSION['message'] = "Event '{$eventData['title']}' has been deleted successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting event. Please try again.";
            $_SESSION['message_type'] = "danger";
        }
        break;
        
    case 'open':
        if ($event->updateStatus($eventId, 'open')) {
            $_SESSION['message'] = "Event '{$eventData['title']}' is now open for registration.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating event status. Please try again.";
            $_SESSION['message_type'] = "danger";
        }
        break;
        
    case 'close':
        if ($event->updateStatus($eventId, 'closed')) {
            $_SESSION['message'] = "Event '{$eventData['title']}' registration is now closed.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating event status. Please try again.";
            $_SESSION['message_type'] = "danger";
        }
        break;
        
    default:
        $_SESSION['message'] = "Invalid action.";
        $_SESSION['message_type'] = "danger";
        break;
}

header('Location: index.php');
exit;
?>
