<?php
session_start();
require_once __DIR__ . '/../classes/Student.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$action = $_POST['action'] ?? '';
$student = new Student();

switch ($action) {
    case 'register':
        // Validate required fields
        $requiredFields = ['student_id', 'first_name', 'last_name', 'email', 'password', 'course', 'year_of_study', 'date_of_birth'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
                exit;
            }
        }

        // Additional validation
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit;
        }

        if (strlen($_POST['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
            exit;
        }

        if ($_POST['password'] !== $_POST['confirm_password']) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
            exit;
        }

        // Prepare data for registration
        $data = [
            'student_id' => trim($_POST['student_id']),
            'first_name' => trim($_POST['first_name']),
            'last_name' => trim($_POST['last_name']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'phone' => trim($_POST['phone'] ?? ''),
            'course' => trim($_POST['course']),
            'year_of_study' => (int)$_POST['year_of_study'],
            'date_of_birth' => $_POST['date_of_birth']
        ];

        $result = $student->register($data);
        echo json_encode($result);
        break;

    case 'login':
        // Validate required fields
        if (empty($_POST['email']) || empty($_POST['password'])) {
            echo json_encode(['success' => false, 'message' => 'Email and password are required']);
            exit;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit;
        }

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $result = $student->login($email, $password);
        
        if ($result['success']) {
            // Store student data in session
            $_SESSION['student_id'] = $result['student']['id'];
            $_SESSION['student_data'] = $result['student'];
            $_SESSION['logged_in'] = true;
        }

        echo json_encode($result);
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
