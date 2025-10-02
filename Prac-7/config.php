<?php
// File-based user storage configuration
define('USERS_FILE', 'users.txt');
define('TOKENS_FILE', 'remember_tokens.txt');

// Start session
session_start();

// Helper function to read users from file
function getUsers() {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    $content = file_get_contents(USERS_FILE);
    return $content ? json_decode($content, true) : [];
}

// Helper function to save users to file
function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

// Helper function to read remember tokens from file
function getTokens() {
    if (!file_exists(TOKENS_FILE)) {
        return [];
    }
    $content = file_get_contents(TOKENS_FILE);
    return $content ? json_decode($content, true) : [];
}

// Helper function to save remember tokens to file
function saveTokens($tokens) {
    file_put_contents(TOKENS_FILE, json_encode($tokens, JSON_PRETTY_PRINT));
}

// Check for remember me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $tokens = getTokens();
    
    if (isset($tokens[$token])) {
        $username = $tokens[$token];
        $users = getUsers();
        
        if (isset($users[$username])) {
            $_SESSION['user_id'] = $users[$username]['id'];
            $_SESSION['username'] = $username;
        }
    }
}

// Create default admin user if users file doesn't exist
if (!file_exists(USERS_FILE)) {
    $defaultUsers = [
        'admin' => [
            'id' => 1,
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    saveUsers($defaultUsers);
}
?>
