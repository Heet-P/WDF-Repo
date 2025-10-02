<?php
require_once __DIR__ . '/../config/database.php';

class Student {
    private $db;
    private $connection;

    public function __construct() {
        $this->db = new Database();
        $this->connection = $this->db->getConnection();
    }

    // Register a new student
    public function register($data) {
        try {
            // Check if email or student_id already exists
            $checkQuery = "SELECT id FROM students WHERE email = ? OR student_id = ?";
            $checkStmt = $this->connection->prepare($checkQuery);
            $checkStmt->bind_param("ss", $data['email'], $data['student_id']);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                return ['success' => false, 'message' => 'Email or Student ID already exists'];
            }

            // Hash the password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insert new student
            $query = "INSERT INTO students (student_id, first_name, last_name, email, password, phone, course, year_of_study, date_of_birth) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("sssssssis", 
                $data['student_id'],
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $hashedPassword,
                $data['phone'],
                $data['course'],
                $data['year_of_study'],
                $data['date_of_birth']
            );

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Registration successful'];
            } else {
                return ['success' => false, 'message' => 'Registration failed'];
            }

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Login student
    public function login($email, $password) {
        try {
            $query = "SELECT id, student_id, first_name, last_name, email, password FROM students WHERE email = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $student = $result->fetch_assoc();
                
                if (password_verify($password, $student['password'])) {
                    // Remove password from return data
                    unset($student['password']);
                    return ['success' => true, 'student' => $student];
                } else {
                    return ['success' => false, 'message' => 'Invalid password'];
                }
            } else {
                return ['success' => false, 'message' => 'Student not found'];
            }

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Get student by ID
    public function getStudentById($id) {
        try {
            $query = "SELECT id, student_id, first_name, last_name, email, phone, course, year_of_study, date_of_birth, created_at FROM students WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                return ['success' => true, 'student' => $result->fetch_assoc()];
            } else {
                return ['success' => false, 'message' => 'Student not found'];
            }

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Update student profile
    public function updateStudent($id, $data) {
        try {
            $query = "UPDATE students SET first_name = ?, last_name = ?, phone = ?, course = ?, year_of_study = ?, date_of_birth = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssssssi", 
                $data['first_name'],
                $data['last_name'],
                $data['phone'],
                $data['course'],
                $data['year_of_study'],
                $data['date_of_birth'],
                $id
            );

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Profile updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Update failed'];
            }

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
?>
