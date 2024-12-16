// Clear error and success messages after 5 seconds
setTimeout(function() {
    var errorMessages = document.getElementById('error-messages');
    if (errorMessages) {
        errorMessages.style.display = 'none';
    }
    var successMessage = document.getElementById('success-message');
    if (successMessage) {
        successMessage.style.display = 'none';
    }
}, 5000);
