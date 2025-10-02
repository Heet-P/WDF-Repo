# Student Management System

A simple PHP-MySQL application for storing and retrieving student data.

## Features

- ✅ **View All Students**: Display all students in a beautiful table format
- ➕ **Add New Student**: Add students with validation
- ✏️ **Edit Student**: Update existing student information
- 🗑️ **Delete Student**: Remove students from the database
- 🔍 **Search Functionality**: Search students by name, email, or course
- 📱 **Responsive Design**: Works on desktop and mobile devices

## Setup Instructions

### Prerequisites
- XAMPP (or similar local server with PHP and MySQL)
- Web browser

### Installation Steps

1. **Start XAMPP Services**
   - Start Apache and MySQL from the XAMPP Control Panel

2. **Create Database**
   - Open phpMyAdmin in your browser: `http://localhost/phpmyadmin`
   - Import the `database.sql` file or run the SQL commands manually:
   ```sql
   CREATE DATABASE IF NOT EXISTS student_db;
   USE student_db;
   
   CREATE TABLE IF NOT EXISTS students (
       id INT AUTO_INCREMENT PRIMARY KEY,
       first_name VARCHAR(50) NOT NULL,
       last_name VARCHAR(50) NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       phone VARCHAR(15),
       course VARCHAR(100),
       enrollment_date DATE DEFAULT CURRENT_DATE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   );
   ```

3. **Access the Application**
   - Open your web browser
   - Navigate to: `http://localhost/WDF/Prac-10/`

## File Structure

```
Prac-10/
├── index.php              # Main page - displays all students
├── add_student.php         # Add new student form
├── edit_student.php        # Edit existing student form
├── delete_student.php      # Delete student handler
├── config.php             # Database configuration
├── database.sql           # Database schema and sample data
└── README.md             # This file
```

## Database Configuration

The application uses the following default XAMPP MySQL settings:
- **Host**: localhost
- **Username**: root
- **Password**: (empty)
- **Database**: student_db

To modify these settings, edit the `config.php` file.

## Student Table Schema

| Field | Type | Description |
|-------|------|-------------|
| id | INT (Primary Key) | Auto-incrementing student ID |
| first_name | VARCHAR(50) | Student's first name (required) |
| last_name | VARCHAR(50) | Student's last name (required) |
| email | VARCHAR(100) | Student's email address (required, unique) |
| phone | VARCHAR(15) | Student's phone number (optional) |
| course | VARCHAR(100) | Student's course/program |
| enrollment_date | DATE | Date of enrollment |
| created_at | TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | Record last update timestamp |

## Available Courses

- Computer Science
- Information Technology
- Web Development
- Data Science
- Software Engineering
- Cybersecurity
- Mobile App Development
- Artificial Intelligence

## Features in Detail

### 1. View Students (index.php)
- Displays all students in a responsive table
- Search functionality across name, email, and course
- Quick action buttons for edit and delete
- Success/error message display

### 2. Add Student (add_student.php)
- Form validation for required fields
- Email format validation
- Duplicate email prevention
- Course selection dropdown
- Automatic enrollment date setting

### 3. Edit Student (edit_student.php)
- Pre-populated form with existing data
- Same validation as add student
- Prevents duplicate emails for other students
- Maintains data integrity

### 4. Delete Student (delete_student.php)
- Confirmation dialog before deletion
- Safe deletion with existence check
- Success/error feedback

## Security Features

- SQL injection prevention using prepared statements
- Input validation and sanitization
- HTML entity encoding for output
- Error handling and user feedback

## Browser Compatibility

- Chrome/Chromium
- Firefox
- Safari
- Edge
- Mobile browsers

## Responsive Design

The application is fully responsive and works well on:
- Desktop computers
- Tablets
- Mobile phones

## Troubleshooting

### Common Issues

1. **Database connection error**
   - Ensure MySQL is running in XAMPP
   - Check database credentials in `config.php`
   - Verify database exists

2. **Page not found**
   - Ensure files are in the correct XAMPP directory
   - Check that Apache is running
   - Verify the URL path

3. **Permission errors**
   - Ensure proper file permissions
   - Run XAMPP as administrator if needed

### Error Messages

The application provides clear error messages for:
- Database connection issues
- Validation errors
- Duplicate entries
- Missing records

## Future Enhancements

Potential improvements could include:
- Student photo upload
- Bulk import/export functionality
- Advanced filtering and sorting
- Student grade management
- Email notifications
- User authentication system
- Backup and restore features

## Technologies Used

- **PHP 7.4+**: Server-side scripting
- **MySQL 5.7+**: Database management
- **HTML5**: Structure and semantics
- **CSS3**: Styling and responsive design
- **JavaScript**: Client-side interactions

## License

This project is created for educational purposes as part of Web Development Fundamentals (WDF) practice exercises.
