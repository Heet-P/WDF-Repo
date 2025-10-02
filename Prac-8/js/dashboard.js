// Dashboard functionality
let isEditing = false;
let originalData = {};

// Toggle edit mode
function toggleEdit() {
    const editBtn = document.getElementById('editBtn');
    const editActions = document.querySelector('.edit-actions');
    const inputs = document.querySelectorAll('.profile-item input:not([readonly])');
    const selects = document.querySelectorAll('.profile-item select');
    
    if (!isEditing) {
        // Enter edit mode
        isEditing = true;
        editBtn.innerHTML = '<i class="fas fa-times"></i> Cancel';
        editBtn.className = 'btn-cancel';
        editActions.style.display = 'flex';
        
        // Store original data
        originalData = {};
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            originalData[input.name] = input.value;
        });
        selects.forEach(select => {
            select.removeAttribute('disabled');
            originalData[select.name] = select.value;
        });
    } else {
        // Exit edit mode
        cancelEdit();
    }
}

// Cancel edit mode
function cancelEdit() {
    const editBtn = document.getElementById('editBtn');
    const editActions = document.querySelector('.edit-actions');
    const inputs = document.querySelectorAll('.profile-item input:not([type="text"][value*="STU"]):not([type="email"])');
    const selects = document.querySelectorAll('.profile-item select');
    
    isEditing = false;
    editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
    editBtn.className = 'btn-edit';
    editActions.style.display = 'none';
    
    // Restore original data and disable inputs
    inputs.forEach(input => {
        if (originalData[input.name] !== undefined) {
            input.value = originalData[input.name];
        }
        input.setAttribute('readonly', 'true');
    });
    selects.forEach(select => {
        if (originalData[select.name] !== undefined) {
            select.value = originalData[select.name];
        }
        select.setAttribute('disabled', 'true');
    });
    
    clearMessage();
}

// Display messages
function showMessage(message, type) {
    const messageElement = document.getElementById('profileMessage');
    messageElement.textContent = message;
    messageElement.className = `message ${type}`;
}

function clearMessage() {
    const messageElement = document.getElementById('profileMessage');
    messageElement.textContent = '';
    messageElement.className = 'message';
}

// Handle profile form submission
document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!isEditing) return;
    
    try {
        const formData = new FormData(this);
        formData.append('action', 'update');
        
        const response = await fetch('api/profile.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Profile updated successfully!', 'success');
            setTimeout(() => {
                location.reload(); // Reload to show updated data
            }, 1500);
        } else {
            showMessage(result.message, 'error');
        }
    } catch (error) {
        showMessage('An error occurred. Please try again.', 'error');
        console.error('Update error:', error);
    }
});

// Logout function
async function logout() {
    try {
        const formData = new FormData();
        formData.append('action', 'logout');
        
        const response = await fetch('api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = 'index.html';
        }
    } catch (error) {
        console.error('Logout error:', error);
        // Redirect anyway
        window.location.href = 'index.html';
    }
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
    console.log('Dashboard loaded successfully');
});
