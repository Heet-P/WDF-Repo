# Student Registration & Login Portal

This is a complete PHP-based student registration and login portal with MySQL database integration.

## Features

- **Student Registration**: Complete registration form with validation
- **Student Login**: Secure login system with password hashing
- **User Dashboard**: Profile management and information display
- **MySQL Integration**: Secure database operations with prepared statements
- **Responsive Design**: Modern, mobile-friendly interface
- **Session Management**: Secure user sessions
- **Profile Updates**: Students can update their profile information

## Setup Instructions

### 1. Database Setup

1. Start your XAMPP server (Apache and MySQL)
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Import the `database.sql` file or run the SQL commands manually:
   - Create database: `student_portal`
   - Create the students table with all required fields
   - Sample data is included for testing

### 2. Configuration

The database configuration is in `config/database.php`:
```php
private $host = 'localhost';
private $username = 'root';
private $password = '';
private $database = 'student_portal';
```

Update these settings if your MySQL configuration is different.

### 3. File Structure

```
Prac-8/
├── index.html              # Main login/registration page
├── dashboard.php           # Student dashboard
├── database.sql           # Database setup script
├── config/
│   └── database.php       # Database configuration
├── classes/
│   └── Student.php        # Student class with all methods
├── api/
│   ├── auth.php          # Authentication API
│   └── profile.php       # Profile management API
├── css/
│   ├── style.css         # Main page styles
│   └── dashboard.css     # Dashboard styles
└── js/
    ├── script.js         # Main page JavaScript
    └── dashboard.js      # Dashboard JavaScript
```

### 4. Access the Application

1. Open your web browser
2. Navigate to: `http://localhost/WDF/Prac-8/`
3. Use the registration form to create a new student account
4. Login with your credentials to access the dashboard

## Sample Login Credentials

Test accounts are included in the database:
- **Email**: john.doe@example.com
- **Password**: password
- **Email**: jane.smith@example.com
- **Password**: password

## Features Overview

### Registration Form
- Student ID validation
- Email validation
- Password confirmation
- Course selection
- Year of study selection
- Phone number (optional)
- Date of birth

### Login System
- Email-based authentication
- Secure password hashing (PHP password_hash)
- Session management
- Error handling

### Dashboard
- Profile information display
- Editable profile fields
- Statistics cards
- Logout functionality
- Responsive design

### Security Features
- Prepared SQL statements (SQL injection prevention)
- Password hashing
- Session-based authentication
- Input validation and sanitization
- CSRF protection ready

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: CSS Grid, Flexbox, CSS animations
- **Icons**: Font Awesome 6.0
- **Security**: PHP password hashing, prepared statements

## Customization

You can easily customize:
- Course options in the registration form
- Styling in CSS files
- Database fields in the Student class
- Validation rules in JavaScript and PHP

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Mobile browsers

## Troubleshooting

1. **Database Connection Issues**: Check XAMPP MySQL service and database credentials
2. **File Path Issues**: Ensure all files are in the correct directory structure
3. **Session Issues**: Check that session is started and cookies are enabled
4. **JavaScript Errors**: Check browser console for any script errors

## Future Enhancements

- Password reset functionality
- Email verification
- Admin panel
- Grade management
- Course enrollment
- File upload for profile pictures
- Email notifications
