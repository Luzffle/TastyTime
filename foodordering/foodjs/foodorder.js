       document.getElementById(logout).addEventListener;
       function logout() {
                    alert('are you sure you want to log out?')
                    if(<div class="alert alert-primary" role="alert">
                        <strong>ARE YOU SURE  YOU WANT TO LOG OUT?</strong> <a href="login.html" class="alert-link"></a>
                    </div>){
                         // Redirect to login page
                    }else{
                        return;
                    }
                    
                }
                document.addEventListener('DOMContentLoaded', () => {
                    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
                    const cartItemsContainer = document.getElementById('cart-items');
                
                    // Load cart items from localStorage on page load
                    loadCartItems();
                
                    // Add event listeners to all "Add to Cart" buttons in both the main page and modal
                    addToCartButtons.forEach(button => {
                        button.addEventListener('click', (event) => {
                            const foodCard = event.target.closest('.food-card, .item'); // Adjust to select items from both main page and modal
                            const itemName = foodCard.querySelector('p').textContent;
                            const itemPrice = foodCard.querySelector('span') ? foodCard.querySelector('span').textContent : foodCard.querySelectorAll('p')[1].textContent;
                            const itemImage = foodCard.querySelector('img').src;
                            
                            const item = {
                                name: itemName,
                                price: itemPrice,
                                image: itemImage
                            };
                            
                            addItemToCart(item);
                        });
                    });
                
                    // Function to add item to cart and save to localStorage
                    function addItemToCart(item) {
                        let cart = getCartFromLocalStorage();
                        cart.push(item);
                        localStorage.setItem('cart', JSON.stringify(cart));
                        renderCartItems(cart);
                        alert(`${item.name} has been added to your cart!`);
                    }
                
                    // Function to retrieve the cart from localStorage
                    function getCartFromLocalStorage() {
                        return JSON.parse(localStorage.getItem('cart')) || [];
                    }
                
                    // Function to render cart items in the cart section
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
                
                    // Function to remove an item from the cart and update localStorage
                    function removeItemFromCart(itemToRemove) {
                        let cart = getCartFromLocalStorage();
                        cart = cart.filter(item => item.name !== itemToRemove.name || item.price !== itemToRemove.price);
                        localStorage.setItem('cart', JSON.stringify(cart));
                        renderCartItems(cart);
                    }
                
                    // Function to load cart items from localStorage when the page is loaded
                    function loadCartItems() {
                        const cart = getCartFromLocalStorage();
                        renderCartItems(cart);
                    }
                });
                