<?php
include 'config.php';

// Process form submission before any HTML output
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);
    $enrollment_date = $_POST['enrollment_date'];

    // Validation
    $errors = [];
    
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

    // Check if email already exists
    if (empty($errors)) {
        $check_email = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email already exists";
        }
        $check_email->close();
    }

    if (empty($errors)) {
        // Insert new student
        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, email, phone, course, enrollment_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $course, $enrollment_date);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: index.php?message=Student added successfully!&type=success");
            exit();
        } else {
            $errors[] = "Error adding student: " . $conn->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Management System</title>
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
            background: linear-gradient(45deg, #4CAF50, #45a049);
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

        .btn.success {
            background: #4CAF50;
        }

        .btn.success:hover {
            background: #45a049;
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
            border-color: #4CAF50;
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
            <h1>➕ Add New Student</h1>
            <p>Enter student information below</p>
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
                            <input type="text" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="required">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="enrollment_date">Enrollment Date</label>
                            <input type="date" id="enrollment_date" name="enrollment_date" value="<?php echo isset($_POST['enrollment_date']) ? $_POST['enrollment_date'] : date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="course">Course <span class="required">*</span></label>
                        <select id="course" name="course" required>
                            <option value="">Select a course</option>
                            <option value="Computer Science" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                            <option value="Information Technology" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                            <option value="Web Development" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Web Development') ? 'selected' : ''; ?>>Web Development</option>
                            <option value="Data Science" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                            <option value="Software Engineering" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                            <option value="Cybersecurity" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Cybersecurity') ? 'selected' : ''; ?>>Cybersecurity</option>
                            <option value="Mobile App Development" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Mobile App Development') ? 'selected' : ''; ?>>Mobile App Development</option>
                            <option value="Artificial Intelligence" <?php echo (isset($_POST['course']) && $_POST['course'] == 'Artificial Intelligence') ? 'selected' : ''; ?>>Artificial Intelligence</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn success">✅ Add Student</button>
                        <a href="index.php" class="btn">❌ Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
