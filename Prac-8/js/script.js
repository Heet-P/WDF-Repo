// Form toggle functionality
function showLogin() {
    document.getElementById('loginForm').classList.add('active');
    document.getElementById('registerForm').classList.remove('active');
    document.querySelectorAll('.toggle-btn')[0].classList.add('active');
    document.querySelectorAll('.toggle-btn')[1].classList.remove('active');
    clearMessages();
}

function showRegister() {
    document.getElementById('registerForm').classList.add('active');
    document.getElementById('loginForm').classList.remove('active');
    document.querySelectorAll('.toggle-btn')[1].classList.add('active');
    document.querySelectorAll('.toggle-btn')[0].classList.remove('active');
    clearMessages();
}

function clearMessages() {
    document.getElementById('loginMessage').textContent = '';
    document.getElementById('registerMessage').textContent = '';
    document.getElementById('loginMessage').className = 'message';
    document.getElementById('registerMessage').className = 'message';
}

// Form validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validateStudentId(studentId) {
    return studentId.length >= 3;
}

// Display messages
function showMessage(elementId, message, type) {
    const messageElement = document.getElementById(elementId);
    messageElement.textContent = message;
    messageElement.className = `message ${type}`;
}

// Login form submission
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;
    
    // Validation
    if (!validateEmail(email)) {
        showMessage('loginMessage', 'Please enter a valid email address', 'error');
        return;
    }
    
    if (!validatePassword(password)) {
        showMessage('loginMessage', 'Password must be at least 6 characters long', 'error');
        return;
    }
    
    // Submit form data
    try {
        const formData = new FormData();
        formData.append('action', 'login');
        formData.append('email', email);
        formData.append('password', password);
        
        const response = await fetch('api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('loginMessage', 'Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 1500);
        } else {
            showMessage('loginMessage', result.message, 'error');
        }
    } catch (error) {
        showMessage('loginMessage', 'An error occurred. Please try again.', 'error');
        console.error('Login error:', error);
    }
});

// Registration form submission
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');
    const email = formData.get('email');
    const studentId = formData.get('student_id');
    
    // Validation
    if (!validateEmail(email)) {
        showMessage('registerMessage', 'Please enter a valid email address', 'error');
        return;
    }
    
    if (!validateStudentId(studentId)) {
        showMessage('registerMessage', 'Student ID must be at least 3 characters long', 'error');
        return;
    }
    
    if (!validatePassword(password)) {
        showMessage('registerMessage', 'Password must be at least 6 characters long', 'error');
        return;
    }
    
    if (password !== confirmPassword) {
        showMessage('registerMessage', 'Passwords do not match', 'error');
        return;
    }
    
    // Submit form data
    try {
        formData.append('action', 'register');
        
        const response = await fetch('api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('registerMessage', 'Registration successful! You can now login.', 'success');
            setTimeout(() => {
                showLogin();
                document.getElementById('registerForm').reset();
            }, 2000);
        } else {
            showMessage('registerMessage', result.message, 'error');
        }
    } catch (error) {
        showMessage('registerMessage', 'An error occurred. Please try again.', 'error');
        console.error('Registration error:', error);
    }
});

// Real-time validation feedback
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value.trim();
    if (email && !validateEmail(email)) {
        this.style.borderColor = '#f44336';
    } else {
        this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
    }
});

document.getElementById('studentId').addEventListener('blur', function() {
    const studentId = this.value.trim();
    if (studentId && !validateStudentId(studentId)) {
        this.style.borderColor = '#f44336';
    } else {
        this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
    }
});

document.getElementById('confirmPassword').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.style.borderColor = '#f44336';
    } else {
        this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    showLogin();
});
