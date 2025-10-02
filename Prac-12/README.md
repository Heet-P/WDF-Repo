# Event Management System

A complete CRUD (Create, Read, Update, Delete) application for managing events with PHP and MySQL.

## Features

- **Full CRUD Operations**: Create, Read, Update, and Delete events
- **Event Status Management**: Open/Closed status for events
- **Modern UI**: Bootstrap-based responsive design
- **Form Validation**: Client and server-side validation
- **Success/Error Messages**: User feedback for all operations
- **Database Integration**: MySQL database with proper error handling
- **Security**: PDO prepared statements to prevent SQL injection

## Requirements

- XAMPP (Apache, MySQL, PHP)
- Modern web browser
- Bootstrap 5 (included via CDN)
- Font Awesome (included via CDN)

## Installation

1. **Start XAMPP**:
   - Start Apache and MySQL services

2. **Create Database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database.sql` file or run the SQL commands manually

3. **Configure Database**:
   - Edit `config/database.php` if needed (default settings work with XAMPP)

4. **Access Application**:
   - Open http://localhost/WDF/Prac-12/ in your browser

## Project Structure

```
Prac-12/
├── classes/
│   └── Event.php              # Event class with CRUD methods
├── config/
│   └── database.php           # Database configuration
├── includes/
│   ├── header.php             # Header template
│   └── footer.php             # Footer template
├── index.php                  # Main dashboard (Read)
├── create.php                 # Create new event
├── edit.php                   # Edit existing event
├── view.php                   # View event details
├── actions.php                # Handle delete and status updates
├── database.sql               # Database schema and sample data
└── README.md                  # This file
```

## Database Schema

### Events Table
- `id`: Primary key (auto-increment)
- `title`: Event title (required)
- `description`: Event description (optional)
- `event_date`: Event date (required)
- `event_time`: Event time (required)
- `location`: Event location (required)
- `status`: Event status ('open' or 'closed')
- `max_participants`: Maximum number of participants
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

## Key Features Implemented

### 1. CRUD Operations
- ✅ **Create**: Add new events with validation
- ✅ **Read**: Display all events in dashboard
- ✅ **Update**: Edit existing events
- ✅ **Delete**: Remove events with confirmation

### 2. UI-DB Integration
- ✅ Proper database connection using PDO
- ✅ Error handling for database operations
- ✅ Form data validation and sanitization
- ✅ Responsive design with Bootstrap

### 3. Success/Failure Messages
- ✅ Success messages for successful operations
- ✅ Error messages for failed operations
- ✅ Form validation error messages
- ✅ Auto-dismissing alerts

### 4. Event Status Management
- ✅ Open/Closed status for events
- ✅ Quick status toggle buttons
- ✅ Visual status indicators
- ✅ Status-based filtering capability

## Usage

### Dashboard (index.php)
- View all events in a table format
- See event statistics (total, open, closed)
- Quick actions: View, Edit, Delete, Toggle Status

### Create Event (create.php)
- Form to add new events
- Validation for required fields
- Date validation (no past dates)
- Redirect to dashboard on success

### Edit Event (edit.php)
- Pre-populated form with existing data
- Same validation as create form
- Update confirmation message

### View Event (view.php)
- Detailed view of single event
- All event information displayed
- Action buttons for edit/delete/status change

### Actions (actions.php)
- Handle delete operations
- Handle status updates (open/close)
- Confirmation messages for all actions

## Security Features

- **PDO Prepared Statements**: Prevents SQL injection
- **Input Validation**: Server-side validation for all inputs
- **HTML Escaping**: Prevents XSS attacks
- **Error Logging**: Database errors are logged, not displayed to users

## Learning Outcomes Achieved

1. **PHP Development**: Object-oriented PHP with classes and methods
2. **MySQL Integration**: Database design and CRUD operations
3. **Form Handling**: POST/GET request processing and validation
4. **Session Management**: Success/error message handling
5. **UI/UX Design**: Modern, responsive interface
6. **Security Best Practices**: Safe database operations and input handling

## Admin Tools Application

This system serves as a foundation for admin tools where administrators can:
- Manage events for an organization
- Control event registration status
- Track event details and capacity
- Generate reports on events

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Safari
- Edge

## Troubleshooting

1. **Database Connection Error**: Check XAMPP MySQL service and database credentials
2. **404 Errors**: Ensure files are in the correct XAMPP htdocs directory
3. **Form Not Submitting**: Check PHP errors in XAMPP error logs
4. **Styling Issues**: Verify internet connection for Bootstrap/FontAwesome CDN

## Future Enhancements

- User authentication and authorization
- Event registration system for participants
- Email notifications
- Event categories and filtering
- Export functionality (PDF, Excel)
- Image uploads for events
- Calendar view integration
