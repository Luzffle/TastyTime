document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById("popupModal");
    const modal1 = document.getElementById("popupModal1");
    const modal2 = document.getElementById("popupModal2");
    const confirmationModal = document.getElementById('confirmationModal');
    const viewAllLinks = document.querySelectorAll(".view-all-link");
    const viewAllLinks1 = document.querySelectorAll(".view-all-link1");
    const viewAllLinks2 = document.querySelectorAll(".view-all-link2");
    const closeBtn = document.querySelector(".close");
    const closeBtn1 = document.querySelector(".close1");
    const closeBtn2 = document.querySelector(".close2");
    const proceedToPaymentBtn = document.getElementById('proceed-to-payment');
    const cartItemsContainer = document.getElementById('cart-items');
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");
    const buyNowButtons = document.querySelectorAll(".buy-now-btn");
    const userProfile = document.getElementById('user-profile');
    const accountInfo = document.getElementById('account-info');

    // Toggle account info visibility
    userProfile.addEventListener('click', () => {
        accountInfo.classList.toggle('hidden');
    });

    // Function to open and close modals
    function openModal(modalElement) {
        modalElement.style.display = 'block';
    }

    function closeModal(modalElement) {
        modalElement.style.display = 'none';
    }

    // Show modal when "View All" link is clicked
    viewAllLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            openModal(modal);
        });
    });

    // Close modal with close button
    closeBtn.addEventListener('click', () => closeModal(modal));

    // Close modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal(modal);
        }
    });
    /* link 1 */
    viewAllLinks1.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            openModal(modal1);
        });
    });

    // Close modal with close button
    closeBtn1.addEventListener('click', () => closeModal(modal1));

    // Close modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === modal1) {
            closeModal(modal1);
        }
    });
 /* link 2*/
 viewAllLinks2.forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();
        openModal(modal2);
    });
});

// Close modal with close button
closeBtn2.addEventListener('click', () => closeModal(modal2));

// Close modal when clicking outside of it
window.addEventListener('click', (event) => {
    if (event.target === modal2) {
        closeModal(modal2);
    }
});
    // Add to Cart functionality
    addToCartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const foodItem = e.target.closest('.item').querySelector('p').innerText;
            const price = e.target.closest('.item').querySelector('p:nth-of-type(2)').innerText;
            console.log(`${foodItem} added to cart for ${price}`);
            // Additional logic can be added here
        });
    });

    // Buy Now functionality
    buyNowButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const foodItem = e.target.closest('.item').querySelector('p').innerText;
            const price = e.target.closest('.item').querySelector('p:nth-of-type(2)').innerText;

            // Show confirmation modal
            document.getElementById('order-item').innerText = foodItem;
            document.getElementById('order-price').innerText = price;
            openModal(confirmationModal);
        });
    });

    // Proceed to Payment
    proceedToPaymentBtn.addEventListener('click', () => {
        closeModal(confirmationModal);
        window.location.href = 'payment.html';
    });

    // Logout button
    document.querySelectorAll('.logout-btn').forEach(button => {
        button.addEventListener('click', () => {
            alert('Logging out, thank you for using our service.');
            window.location.href = 'login.html';
        });
    });

    // Load cart items from localStorage on page load
    loadCartItems();

    // Functions for managing cart items
    function addItemToCart(item) {
        let cart = getCartFromLocalStorage();
        cart.push(item);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCartItems(cart);
    }

    function getCartFromLocalStorage() {
        return JSON.parse(localStorage.getItem('cart')) || [];
    }

    function renderCartItems(cart) {
        cartItemsContainer.innerHTML = ''; // Clear the cart container
        cart.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <p>${item.name}</p>
                <span>${item.price}</span>
                <button class="remove-item-btn">Remove</button>
            `;
            cartItemsContainer.appendChild(cartItem);

            // Remove item event listener
            cartItem.querySelector('.remove-item-btn').addEventListener('click', () => {
                removeItemFromCart(item);
            });
        });
    }

    function removeItemFromCart(itemToRemove) {
        let cart = getCartFromLocalStorage();
        cart = cart.filter(item => item.name !== itemToRemove.name || item.price !== itemToRemove.price);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCartItems(cart);
    }

    function loadCartItems() {
        const cart = getCartFromLocalStorage();
        renderCartItems(cart);
    }
});
