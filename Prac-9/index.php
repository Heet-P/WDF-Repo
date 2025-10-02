<?php
require_once 'config.php';

// Check if user is logged in and redirect accordingly
if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
?>
