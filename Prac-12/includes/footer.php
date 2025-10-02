    </div>
    
    <footer class="bg-dark text-light text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Event Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Confirmation for delete actions
        function confirmDelete(eventTitle) {
            return confirm(`Are you sure you want to delete the event "${eventTitle}"? This action cannot be undone.`);
        }

        // Status update confirmation
        function confirmStatusChange(eventTitle, newStatus) {
            return confirm(`Are you sure you want to ${newStatus} the event "${eventTitle}"?`);
        }
    </script>
</body>
</html>
