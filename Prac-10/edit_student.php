<?php
include 'config.php';

// Get student ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?message=Invalid student ID&type=error");
    exit();
}

$student_id = (int)$_GET['id'];

// Fetch current student data
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
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
$stmt->close();

// Initialize errors array
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);
    $enrollment_date = $_POST['enrollment_date'];

    // Validation
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($course)) {
        $errors[] = "Course is required";
    }

    // Check if email already exists for other students
    if (empty($errors)) {
        $check_email = $conn->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
        $check_email->bind_param("si", $email, $student_id);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email already exists for another student";
        }
        $check_email->close();
    }

    if (empty($errors)) {
        // Update student
        $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, course = ?, enrollment_date = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $course, $enrollment_date, $student_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: index.php?message=Student updated successfully!&type=success");
            exit();
        } else {
            $errors[] = "Error updating student: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Update student array with new values for form display
        $student['first_name'] = $first_name;
        $student['last_name'] = $last_name;
        $student['email'] = $email;
        $student['phone'] = $phone;
        $student['course'] = $course;
        $student['enrollment_date'] = $enrollment_date;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(45deg, #FF9800, #F57C00);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .nav-buttons {
            margin: 30px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 5px;
            background: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #1976D2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn.warning {
            background: #FF9800;
        }

        .btn.warning:hover {
            background: #F57C00;
        }

        .content {
            padding: 30px;
        }

        .form-container {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus, input[type="date"]:focus, select:focus {
            outline: none;
            border-color: #FF9800;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .required {
            color: red;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            
            .nav-buttons {
                margin: 20px;
            }
            
            .btn {
                display: block;
                margin: 10px 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Edit Student</h1>
            <p>Update student information</p>
        </div>

        <div class="nav-buttons">
            <a href="index.php" class="btn">📋 Back to Student List</a>
        </div>

        <div class="content">
            <?php
            // Display errors if form was submitted and had validation errors
            if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($errors)) {
                echo "<div class='alert alert-error'>";
                foreach ($errors as $error) {
                    echo $error . "<br>";
                }
                echo "</div>";
            }
            ?>

            <div class="form-container">
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="required">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="required">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="enrollment_date">Enrollment Date</label>
                            <input type="date" id="enrollment_date" name="enrollment_date" value="<?php echo $student['enrollment_date']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="course">Course <span class="required">*</span></label>
                        <select id="course" name="course" required>
                            <option value="">Select a course</option>
                            <option value="Computer Science" <?php echo ($student['course'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                            <option value="Information Technology" <?php echo ($student['course'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                            <option value="Web Development" <?php echo ($student['course'] == 'Web Development') ? 'selected' : ''; ?>>Web Development</option>
                            <option value="Data Science" <?php echo ($student['course'] == 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                            <option value="Software Engineering" <?php echo ($student['course'] == 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                            <option value="Cybersecurity" <?php echo ($student['course'] == 'Cybersecurity') ? 'selected' : ''; ?>>Cybersecurity</option>
                            <option value="Mobile App Development" <?php echo ($student['course'] == 'Mobile App Development') ? 'selected' : ''; ?>>Mobile App Development</option>
                            <option value="Artificial Intelligence" <?php echo ($student['course'] == 'Artificial Intelligence') ? 'selected' : ''; ?>>Artificial Intelligence</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn warning">💾 Update Student</button>
                        <a href="index.php" class="btn">❌ Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
