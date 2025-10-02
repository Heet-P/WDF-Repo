<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

require_once __DIR__ . '/../classes/Student.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$action = $_POST['action'] ?? '';
$student = new Student();

switch ($action) {
    case 'update':
        // Validate required fields
        $requiredFields = ['first_name', 'last_name', 'course', 'year_of_study', 'date_of_birth'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
                exit;
            }
        }

        // Prepare data for update
        $data = [
            'first_name' => trim($_POST['first_name']),
            'last_name' => trim($_POST['last_name']),
            'phone' => trim($_POST['phone'] ?? ''),
            'course' => trim($_POST['course']),
            'year_of_study' => (int)$_POST['year_of_study'],
            'date_of_birth' => $_POST['date_of_birth']
        ];

        $result = $student->updateStudent($_SESSION['student_id'], $data);
        
        if ($result['success']) {
            // Update session data
            $updatedStudent = $student->getStudentById($_SESSION['student_id']);
            if ($updatedStudent['success']) {
                $_SESSION['student_data'] = $updatedStudent['student'];
            }
        }
        
        echo json_encode($result);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
