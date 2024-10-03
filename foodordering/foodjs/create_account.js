window.onload = function() {
    var form = document.getElementById("create-account-form");
    var termsCheckbox = document.getElementById("terms-checkbox");
    var robotCheckbox = document.getElementById("robotCheckbox");
    var captchaSection = document.getElementById("captchaSection");
    var passwordField = document.getElementById("password");

    // Show/hide CAPTCHA when the "I’m not a robot" checkbox is clicked
    robotCheckbox.onchange = function() {
        if (robotCheckbox.checked) {
            checkboxLabel.classList.add("checked");
            captchaSection.style.display = "block";
        } else {
            captchaSection.style.display = "none";
            captchaAnswer.value = "";
            checkboxLabel.classList.remove("checked");
        }
    };

    // Function to validate password strength
    function validatePassword(password) {
        const minLength = 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        
        return (
            password.length >= minLength &&
            hasUpperCase &&
            hasLowerCase &&
            hasNumbers &&
            hasSpecialChars
        );
    }

    form.onsubmit = function(event) {
        event.preventDefault(); // Prevent form submission for validation

        // Initialize an array to hold error messages
        let errorMessages = [];

        // Check if all fields are filled
        const email = document.getElementById("email").value;
        const password = passwordField.value;
        const name = document.getElementById("name").value;

        if (name === "") {
            errorMessages.push("Please enter your full name.");
        }

        if (email === "") {
            errorMessages.push("Please enter your email.");
        }

        if (password === "") {
            errorMessages.push("Please enter your password.");
        }

        // Check if terms are agreed
        if (!termsCheckbox.checked) {
            errorMessages.push("You must agree to the Terms of Use and Privacy Policy.");
        }

        // AJAX request to check if email is already used
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_email.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.exists) {
                    errorMessages.push("This email is already used. Please use another email.");
                }

                // Check password validity
                if (!validatePassword(password)) {
                    errorMessages.push("Password must be at least 8 characters long and include uppercase, lowercase letters, numbers, and special characters.");
                }
// Simple CAPTCHA validation
               

                // Display all error messages if there are any
                if (errorMessages.length > 0) {
                    alert(errorMessages.join("\n"));
                } else {
                    // If all validations pass
                    alert("Account created successfully! Redirecting to login page.");
                    setTimeout(() => {
                        window.location.href = "login.html"; // Redirect to login page
                    }, 2000); // Delay of 2 seconds
                }
            }
        };
        xhr.send("email=" + encodeURIComponent(email));
    };

    // Check terms & privacy checkbox and provide alert if not checked
    document.getElementById('create-account-btn').addEventListener('click', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault(); // Prevent submission
            alert("You must agree to the Terms of Use and Privacy Policy.");
        }
    });

    // Client-side password validation
    document.getElementById('create-account-form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        
        // Example: Simple client-side validation for password length
        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long!');
        }
    });
};
