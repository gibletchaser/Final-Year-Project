// Cart array
let cart = [];

// Generate unique ID from name
function getItemId(name) {
    return name.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
}

// Update cart display in modal + cart count badge
function renderCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const modalTotal = document.getElementById('cartTotal'); // Inside modal

    // Top summary elements – update ALL possible IDs
    const countDisplays = [
        document.getElementById('cart-count'),
        document.getElementById('cart-item-count'),
        document.querySelector('.cart-item-count'),
        document.querySelector('#navbar-cart-count')
    ];

    const totalDisplays = [
        document.getElementById('cart-total-display'),
        document.getElementById('cart-total'),
        document.querySelector('.cart-total'),
        document.querySelector('#navbar-cart-total')
    ];

    // Calculate total
    let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    let totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    // Update item count (top bar + badge)
    countDisplays.forEach(el => {
        if (el) el.textContent = totalItems;
    });

    // Update total price (top bar + modal)
    const allTotalEls = [modalTotal, ...totalDisplays];
    allTotalEls.forEach(el => {
        if (el) el.textContent = totalPrice.toFixed(2);
    });

    // Update modal content
    if (!cartItemsContainer) return;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p class="text-center text-muted">Your cart is empty</p>';
        return;
    }

    cartItemsContainer.innerHTML = '';
   cart.forEach(item => {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'd-flex justify-content-between align-items-center py-3 border-bottom';

    const subtotal = (item.price * item.quantity).toFixed(2);

    itemDiv.innerHTML = `
        <div class="flex-grow-1">
            <div class="font-weight-bold mb-1">${item.name}</div>
            <small class="text-muted">$ ${item.price.toFixed(2)} × ${item.quantity}</small>
        </div>

        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-secondary decrease" data-id="${item.id}">-</button>
            <span class="mx-3 font-weight-bold" style="min-width: 40px; text-align: center;">${item.quantity}</span>
            <button class="btn btn-sm btn-outline-secondary increase" data-id="${item.id}">+</button>
            <button class="btn btn-sm btn-danger ml-3 remove" data-id="${item.id}">Remove</button>
        </div>

        <div class="text-right font-weight-bold text-primary" style="min-width: 80px;">
            $ ${subtotal}
        </div>
    `;

    cartItemsContainer.appendChild(itemDiv);
});
const checkoutBtn = document.querySelector('#cartModal .modal-footer .btn-success');
if (checkoutBtn) {
    checkoutBtn.disabled = (cart.length === 0);
}
}

// === ADD TO CART FROM MENU ===
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        // Find the closest menu item container
        const menuContainer = this.closest('.menus') || this.closest('.col-lg-6');

        const name = menuContainer.querySelector('[data-name]')?.getAttribute('data-name') ||
                     menuContainer.querySelector('h3')?.textContent.trim();

        const priceText = menuContainer.querySelector('[data-price]')?.getAttribute('data-price') ||
                          menuContainer.querySelector('.price')?.textContent.replace('$', '').trim();

        const price = parseFloat(priceText);

        // Get current quantity from the input field on the menu item
        const qtyInput = menuContainer.querySelector('.qty-input');
        const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

        if (!name || isNaN(price)) {
            alert('Error reading item info');
            return;
        }

        const id = getItemId(name);

        // Check if item already in cart
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.quantity += quantity;
        } else {
            cart.push({ id, name, price, quantity });
        }

        // Reset the quantity selector back to 1 for next time
        if (qtyInput) qtyInput.value = 1;

        renderCart();

        // Optional: Auto-open modal or show success
        alert(`${quantity} × ${name} added to cart!`);
    });
});

// === QUANTITY +/- ON MENU ITEM (before adding to cart) ===
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.closest('.quantity-selector').querySelector('.qty-input');
        let value = parseInt(input.value) || 1;

        if (this.classList.contains('plus')) {
            value += 1;
        } else if (this.classList.contains('minus')) {
            value = value > 1 ? value - 1 : 1;
        }

        input.value = value;
    });
});

// === HANDLE + / - / REMOVE INSIDE CART MODAL - ATTACH ONLY ONCE ===
document.addEventListener('DOMContentLoaded', () => {
    const cartItemsContainer = document.getElementById('cartItems');
    
    if (cartItemsContainer) {
        cartItemsContainer.addEventListener('click', function(e) {
            let target = e.target;
            
            // If clicked on text inside button, go up to button
            if (target.tagName !== 'BUTTON') {
                target = target.closest('button');
            }
            
            if (!target || !target.dataset || !target.dataset.id) return;
            
            const id = target.dataset.id;
            const itemIndex = cart.findIndex(i => i.id === id);
            if (itemIndex === -1) return;
            
            if (target.classList.contains('increase')) {
                cart[itemIndex].quantity += 1;
            } 
            else if (target.classList.contains('decrease')) {
                if (cart[itemIndex].quantity > 1) {
                    cart[itemIndex].quantity -= 1;
                } else {
                    // Remove item if quantity reaches 0
                    if (confirm('Remove this item from your cart?')) {
                        cart.splice(itemIndex, 1);
                    } else {
                        return;
                    }
                }
            } 
            else if (target.classList.contains('remove')) {
                if (confirm('Remove this item from your cart?')) {
                    cart.splice(itemIndex, 1);
                } else {
                    return;
                }
            }
            
            renderCart(); // Update display
        });
    }
});

// Re-render cart when modal opens
$('#cartModal').on('show.bs.modal', renderCart);

// Initial render (for cart count on page load)
renderCart();
