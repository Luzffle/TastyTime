// Get the modal
var modal = document.getElementById("popupModal");

// Get the "View All" link
var viewAllLink = document.querySelector(".view-all-link");

// Get the <span> element that closes the modal
var closeBtn = document.querySelector(".close");

// When the user clicks on the "View All" link, open the modal
viewAllLink.onclick = function(event) {
    event.preventDefault();  // Prevent the default anchor behavior
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Add to Cart and Buy Now functionality
var addToCartButtons = document.querySelectorAll(".add-to-cart-btn");
var buyNowButtons = document.querySelectorAll(".buy-now-btn");
var foodOrderList = document.getElementById("foodOrderList");

// Function to add items to the "Food Order" section
function addToFoodOrder(itemName, itemPrice) {
    var listItem = document.createElement("li");
    listItem.innerHTML = `<strong>${itemName}</strong> - ${itemPrice}`;
    foodOrderList.appendChild(listItem);
}

// Event listeners for Add to Cart buttons
addToCartButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        var item = this.parentElement;
        var itemName = item.querySelector("p:nth-child(2)").textContent;
        var itemPrice = item.querySelector("p:nth-child(3)").textContent;
        addToFoodOrder(itemName, itemPrice);
    });
});

// Event listeners for Buy Now buttons (You could also redirect to a checkout page)
buyNowButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        var item = this.parentElement;
        var itemName = item.querySelector("p:nth-child(2)").textContent;
        var itemPrice = item.querySelector("p:nth-child(3)").textContent;
        addToFoodOrder(itemName, itemPrice);
        alert("Proceeding to checkout for: " + itemName);
        // Here you could redirect to a checkout page if needed
    });
});

