<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Ensure session is started before destroying it
startSession();

// Destroy the session
session_unset();
session_destroy();

// Redirect to login page with success message
header('Location: login.php?message=logged_out');
exit;
?>
