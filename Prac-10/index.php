<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(45deg, #2196F3, #21CBF3);
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
            border-color: #2196F3;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: linear-gradient(45deg, #2196F3, #21CBF3);
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-small {
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
        }

        .btn-edit {
            background: #FF9800;
        }

        .btn-edit:hover {
            background: #F57C00;
        }

        .btn-delete {
            background: #f44336;
        }

        .btn-delete:hover {
            background: #d32f2f;
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

        .search-container {
            margin-bottom: 20px;
        }

        .search-box {
            width: 100%;
            max-width: 400px;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 16px;
            background: white;
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
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Student Management System</h1>
            <p>Manage student records with ease</p>
        </div>

        <div class="nav-buttons">
            <a href="index.php" class="btn">📋 View All Students</a>
            <a href="add_student.php" class="btn success">➕ Add New Student</a>
        </div>

        <div class="content">
            <?php
            include 'config.php';

            // Display success/error messages
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
                $type = isset($_GET['type']) ? $_GET['type'] : 'success';
                echo "<div class='alert alert-$type'>$message</div>";
            }

            // Search functionality
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $searchQuery = '';
            if (!empty($search)) {
                $searchQuery = " WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%' OR course LIKE '%$search%'";
            }

            $sql = "SELECT * FROM students" . $searchQuery . " ORDER BY created_at DESC";
            $result = $conn->query($sql);
            ?>

            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="🔍 Search students..." class="search-box" value="<?php echo htmlspecialchars($search); ?>">
                </form>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Enrollment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['enrollment_date'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn-small btn-edit">✏️ Edit</a>
                                    <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure you want to delete this student?')">🗑️ Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-error">
                    <?php echo !empty($search) ? "No students found matching your search." : "No students found. Add some students to get started!"; ?>
                </div>
            <?php endif; ?>

            <?php $conn->close(); ?>
        </div>
    </div>
</body>
</html>
