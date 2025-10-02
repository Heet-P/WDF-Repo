# WDF Login System

A complete PHP login system with sessions, user registration, and a protected dashboard - built without any CSS frameworks.

## Features

- ✅ User Registration with validation
- ✅ Secure Login with password hashing
- ✅ Session management
- ✅ Protected dashboard
- ✅ Logout functionality
- ✅ Beautiful custom CSS design (no Bootstrap/Tailwind)
- ✅ Responsive design
- ✅ Input validation and error handling
- ✅ XSS protection with htmlspecialchars()
- ✅ SQL injection protection with prepared statements

## Setup Instructions

### 1. Database Setup

1. Start your XAMPP server (Apache + MySQL)
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Import the `database.sql` file or run the SQL commands:

```sql
CREATE DATABASE IF NOT EXISTS login_system;
USE login_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a sample user (password is 'password123')
INSERT INTO users (username, email, password) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
```

### 2. Configuration

Update the database configuration in `config.php` if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'login_system');
```

### 3. File Structure

```
Prac-9/
├── config.php          # Database connection and session functions
├── index.php           # Main entry point (redirects appropriately)
├── login.php           # Login form and authentication
├── register.php        # User registration form
├── dashboard.php       # Protected user dashboard
├── logout.php          # Logout functionality
├── database.sql        # Database schema and sample data
└── README.md           # This file
```

### 4. Access the Application

1. Navigate to: `http://localhost/WDF/Prac-9/`
2. You'll be redirected to the login page
3. Use the demo account or register a new one:
   - **Username:** admin
   - **Password:** password123

## File Descriptions

### config.php
- Database connection setup
- Session management functions
- Authentication helper functions

### login.php
- User authentication form
- Password verification
- Session creation on successful login
- Custom CSS styling

### register.php
- New user registration form
- Input validation (username length, email format, password confirmation)
- Password hashing before storage
- Duplicate username/email checking

### dashboard.php
- Protected area accessible only to logged-in users
- User information display
- Account statistics
- Feature cards for future functionality
- Logout button

### logout.php
- Session destruction
- Secure logout process
- Redirect to login with success message

## Security Features

1. **Password Hashing**: Uses PHP's `password_hash()` and `password_verify()`
2. **Prepared Statements**: Prevents SQL injection attacks
3. **XSS Protection**: All user input is escaped with `htmlspecialchars()`
4. **Session Security**: Proper session management and validation
5. **Input Validation**: Server-side validation for all forms
6. **Access Control**: Protected pages check authentication status

## Design Features

- **Custom CSS**: No external frameworks (Bootstrap/Tailwind)
- **Responsive Design**: Works on desktop and mobile devices
- **Modern UI**: Gradient backgrounds, blur effects, smooth animations
- **User-Friendly**: Clear error messages and success notifications
- **Accessibility**: Proper form labels and semantic HTML

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Future Enhancements

- [ ] Password reset functionality
- [ ] Email verification
- [ ] Two-factor authentication
- [ ] User profile management
- [ ] Remember me functionality
- [ ] Admin panel
- [ ] User roles and permissions

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check if MySQL is running in XAMPP
   - Verify database credentials in `config.php`
   - Ensure database and table exist

2. **Session Issues**
   - Make sure PHP sessions are enabled
   - Check file permissions
   - Clear browser cookies if needed

3. **CSS Not Loading**
   - Check file paths
   - Ensure no syntax errors in CSS
   - Clear browser cache

## License

This project is for educational purposes as part of WDF (Web Development Fundamentals) coursework.
