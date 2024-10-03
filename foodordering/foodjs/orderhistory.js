document.getElementById('.logout-btn  ').forEach(button => {
    button.addEventListener('click', function () {
        alert('logging out thank you for using.');
            window.location.href = 'login.html'; 
            
    });
});