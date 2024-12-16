
    // Clear error messages after 5 seconds
    setTimeout(function() {
        var errorMessages = document.getElementById('error-messages');
        if (errorMessages) {
            errorMessages.style.display = 'none';
        }
    }, 5000);
