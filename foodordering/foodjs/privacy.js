window.onload = function() {
    var popup = document.getElementById("popup");
    var acceptBtn = document.getElementById("acceptBtn");
    var closeBtn = document.getElementById("closeBtn");
  
    // Show popup after 1 second
    setTimeout(function() {
      popup.style.display = "flex";
    }, 1000);
  
    // Accept button event
    acceptBtn.onclick = function() {
      popup.style.display = "none";
      localStorage.setItem('acceptedPrivacy', true); 
      window.location.href = "createaccount.html"; // Redirect to create account page// Save acceptance status
    };
  
    // Close button event
    closeBtn.onclick = function() {
      popup.style.display = "none";
      window.location.href = "createaccount.html"; // Redirect to create account page
    };
  
    // Check if user already accepted
    if (localStorage.getItem('acceptedPrivacy')) {
      popup.style.display = "none";
    }
  };
  