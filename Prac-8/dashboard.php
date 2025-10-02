<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.html');
    exit;
}

require_once 'classes/Student.php';

$student = new Student();
$studentData = $_SESSION['student_data'];

// Get updated student data
$result = $student->getStudentById($_SESSION['student_id']);
if ($result['success']) {
    $studentData = $result['student'];
    $_SESSION['student_data'] = $studentData;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?php echo htmlspecialchars($studentData['first_name'] . ' ' . $studentData['last_name']); ?></title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-graduation-cap"></i> Student Portal</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($studentData['first_name']); ?>!</span>
                    <button onclick="logout()" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="dashboard-grid">
                <!-- Profile Card -->
                <div class="card profile-card">
                    <div class="card-header">
                        <h2><i class="fas fa-user"></i> Profile Information</h2>
                        <button onclick="toggleEdit()" class="btn-edit" id="editBtn">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                    <div class="card-content">
                        <form id="profileForm">
                            <div class="profile-grid">
                                <div class="profile-item">
                                    <label><i class="fas fa-id-card"></i> Student ID</label>
                                    <input type="text" value="<?php echo htmlspecialchars($studentData['student_id']); ?>" readonly>
                                </div>
                                <div class="profile-item">
                                    <label><i class="fas fa-envelope"></i> Email</label>
                                    <input type="email" value="<?php echo htmlspecialchars($studentData['email']); ?>" readonly>
                                </div>
                                <div class="profile-item">
                                    <label><i class="fas fa-user"></i> First Name</label>
                                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($studentData['first_name']); ?>" readonly>
                                </div>
                                <div class="profile-item">
                                    <label><i class="fas fa-user"></i> Last Name</label>
                                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($studentData['last_name']); ?>" readonly>
                                </div>
                                <div class="profile-item">
                                    <label><i class="fas fa-phone"></i> Phone</label>
                                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($studentData['phone'] ?? ''); ?>" readonly>
                                </div>
                                <div class="profile-item">
                                    <label><i class="fas fa-calendar"></i> Date of Birth</label>
                                    <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($studentData['date_of_birth']); ?>" readonly>
                                </div>
                                <div class="profile-item">
                                    <label><i class="fas fa-graduation-cap"></i> Course</label>
                                    <select name="course" disabled>
                                        <option value="Computer Science" <?php echo ($studentData['course'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                                        <option value="Information Technology" <?php echo ($studentData['course'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                        <option value="Software Engineering" <?php echo ($studentData['course'] == 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                        <option value="Data Science" <?php echo ($studentData['course'] == 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                                        <option value="Cybersecurity" <?php echo ($studentData['course'] == 'Cybersecurity') ? 'selected' : ''; ?>>Cybersecurity</option>
                                        <option value="Business Administration" <?php echo ($studentData['course'] == 'Business Administration') ? 'selected' : ''; ?>>Business Administration</option>
                                        <option value="Engineering" <?php echo ($studentData['course'] == 'Engineering') ? 'selected' : ''; ?>>Engineering</option>
                                        <option value="Mathematics" <?php echo ($studentData['course'] == 'Mathematics') ? 'selected' : ''; ?>>Mathematics</option>
                                    </select>
                                </div>
                                <div class="profile-item">
                                    <label><i class="fas fa-layer-group"></i> Year of Study</label>
                                    <select name="year_of_study" disabled>
                                        <option value="1" <?php echo ($studentData['year_of_study'] == 1) ? 'selected' : ''; ?>>1st Year</option>
                                        <option value="2" <?php echo ($studentData['year_of_study'] == 2) ? 'selected' : ''; ?>>2nd Year</option>
                                        <option value="3" <?php echo ($studentData['year_of_study'] == 3) ? 'selected' : ''; ?>>3rd Year</option>
                                        <option value="4" <?php echo ($studentData['year_of_study'] == 4) ? 'selected' : ''; ?>>4th Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="edit-actions" style="display: none;">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <button type="button" onclick="cancelEdit()" class="btn-cancel">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                        <div id="profileMessage" class="message"></div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Registered Since</h3>
                            <p><?php echo date('M d, Y', strtotime($studentData['created_at'])); ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Current Year</h3>
                            <p><?php echo $studentData['year_of_study']; ?><?php 
                                $suffix = ['', 'st', 'nd', 'rd', 'th'];
                                echo isset($suffix[$studentData['year_of_study']]) ? $suffix[$studentData['year_of_study']] : 'th';
                            ?> Year</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Course</h3>
                            <p><?php echo htmlspecialchars($studentData['course']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>
