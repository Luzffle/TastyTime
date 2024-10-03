// login.js
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Send login request to the server
        fetch('login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email, password: password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = 'dashboard.html'; // Redirect on successful login
            } else {
                alert('Login failed: ' + data.message); // Display error message
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
