document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const resetButton = document.querySelector('button[type="submit"]');

    // Password validation
    newPasswordInput.addEventListener('input', function () {
        validatePassword();
    });

    form.addEventListener('submit', function (event) {
        if (!validatePassword()) {
            event.preventDefault(); // Prevent form submission if password is invalid
            alert('Your password must be at least 8 characters long, contain one uppercase letter, one lowercase letter, and one number.');
        }
    });

    // Password validation function
    function validatePassword() {
        const passwordValue = newPasswordInput.value;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

        if (!passwordPattern.test(passwordValue)) {
            newPasswordInput.classList.add('invalid');
            return false;
        } else {
            newPasswordInput.classList.remove('invalid');
            return true;
        }
    }

    // Handle reset button click
    resetButton.addEventListener('click', function () {
        if (validatePassword()) {
            resetButton.textContent = 'Resetting...';
        }
    });
});
