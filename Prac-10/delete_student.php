<?php
include 'config.php';

// Get student ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?message=Invalid student ID&type=error");
    exit();
}

$student_id = (int)$_GET['id'];

// Check if student exists
$stmt = $conn->prepare("SELECT first_name, last_name FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    header("Location: index.php?message=Student not found&type=error");
    exit();
}

$student = $result->fetch_assoc();
$student_name = $student['first_name'] . ' ' . $student['last_name'];
$stmt->close();

// Delete student
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: index.php?message=Student '$student_name' deleted successfully!&type=success");
    exit();
} else {
    $stmt->close();
    $conn->close();
    header("Location: index.php?message=Error deleting student: " . $conn->error . "&type=error");
    exit();
}
?>
