// script.js - Cleaned & Fixed for Stripe Only (no PayPal)

// Global cart
let cart = JSON.parse(localStorage.getItem('cart') || '[]');

// Simple item ID generator
function getItemId(name) {
    return name.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
}

// Render cart in modal + update count & total everywhere
function renderCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const modalTotal = document.getElementById('cartTotal');

    // Update all cart count badges
    const countEls = [
        document.getElementById('cart-count'),
        document.querySelector('.cart-item-count'),
        document.querySelector('#navbar-cart-count')
    ];
    const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
    countEls.forEach(el => { if (el) el.textContent = totalItems; });

    // Update total displays
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * (item.quantity || 1)), 0).toFixed(2);
    if (modalTotal) modalTotal.textContent = totalPrice;

    // Render items in modal
    if (!cartItemsContainer) return;

    cartItemsContainer.innerHTML = cart.length === 0
        ? '<p class="text-center text-muted">Your cart is empty</p>'
        : '';

    cart.forEach(item => {
        const subtotal = (item.price * (item.quantity || 1)).toFixed(2);
        const itemDiv = document.createElement('div');
        itemDiv.className = 'd-flex justify-content-between align-items-center py-3 border-bottom';
        itemDiv.innerHTML = `
            <div class="flex-grow-1">
                <div class="font-weight-bold mb-1">${item.name}</div>
                <small class="text-muted">$${item.price.toFixed(2)} × ${item.quantity || 1}</small>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary decrease" data-id="${item.id}">-</button>
                <span class="mx-3 font-weight-bold" style="min-width:40px;text-align:center;">${item.quantity || 1}</span>
                <button class="btn btn-sm btn-outline-secondary increase" data-id="${item.id}">+</button>
                <button class="btn btn-sm btn-danger ml-3 remove" data-id="${item.id}">Remove</button>
            </div>
            <div class="text-right font-weight-bold text-primary" style="min-width:80px;">
                $${subtotal}
            </div>
        `;
        cartItemsContainer.appendChild(itemDiv);
    });

    // Disable Place Order button if cart empty
    const placeBtn = document.getElementById('placeOrderBtn');
    if (placeBtn) placeBtn.disabled = (cart.length === 0);
}

// Add to cart from menu page
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        const container = this.closest('.menus') || this.closest('.col-lg-6');
        if (!container) return;

        const nameEl = container.querySelector('h3[data-name]') || container.querySelector('h3');
        const name = nameEl?.getAttribute('data-name') || nameEl?.textContent.trim();

        const priceEl = container.querySelector('.price[data-price]') || container.querySelector('.price');
        const priceText = priceEl?.getAttribute('data-price') || priceEl?.textContent.replace('$', '').trim();
        const price = parseFloat(priceText);

        const qtyInput = container.querySelector('.qty-input');
        const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

        if (!name || isNaN(price) || quantity < 1) {
            alert('Could not read item details');
            return;
        }

        const id = getItemId(name);
        const existing = cart.find(item => item.id === id);

        if (existing) {
            existing.quantity = (existing.quantity || 0) + quantity;
        } else {
            cart.push({ id, name, price, quantity });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();

        if (qtyInput) qtyInput.value = '1';

        alert(`${quantity} × ${name} added to cart!`);
    });
});

// Quantity controls on menu page
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.closest('.quantity-selector')?.querySelector('.qty-input');
        if (!input) return;
        let val = parseInt(input.value) || 1;
        if (this.classList.contains('plus')) val++;
        else if (this.classList.contains('minus') && val > 1) val--;
        input.value = val;
    });
});

// Cart item actions (increase, decrease, remove)
document.getElementById('cartItems')?.addEventListener('click', function(e) {
    const btn = e.target.closest('button');
    if (!btn || !btn.dataset?.id) return;

    const id = btn.dataset.id;
    const idx = cart.findIndex(i => i.id === id);
    if (idx === -1) return;

    if (btn.classList.contains('increase')) {
        cart[idx].quantity = (cart[idx].quantity || 0) + 1;
    } else if (btn.classList.contains('decrease')) {
        if (cart[idx].quantity > 1) {
            cart[idx].quantity -= 1;
        } else if (confirm('Remove this item?')) {
            cart.splice(idx, 1);
        } else return;
    } else if (btn.classList.contains('remove')) {
        if (confirm('Remove this item?')) cart.splice(idx, 1);
        else return;
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
});

// Refresh cart when modal opens
$('#cartModal').on('show.bs.modal', renderCart);

// Initial render on page load
renderCart();

// =============================================
// Stripe-Only Order Submission
// =============================================

document.getElementById('placeOrderBtn')?.addEventListener('click', async function(e) {
    e.preventDefault();

    const name   = document.getElementById('orderName')?.value.trim();
    const phone  = document.getElementById('orderPhone')?.value.trim();
    const notes  = document.getElementById('orderNotes')?.value.trim();
    const total  = cart.reduce((sum, item) => sum + (item.price * (item.quantity || 1)), 0);

    // Validation
    if (!name || !phone) {
        return alert("Please fill in your full name and phone number.");
    }
    if (cart.length === 0 || total <= 0) {
        return alert("Your cart is empty! Add some items first.");
    }

    this.disabled = true;
    this.textContent = 'Processing...';

    try {
        // Step 1: Create Stripe Checkout Session
        const sessionRes = await fetch('create-stripe-checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                customer_name: name,
                phone: phone,
                notes: notes,
                amount: total,
                cart: cart
            })
        });

        if (!sessionRes.ok) {
            const text = await sessionRes.text();
            throw new Error(`Session creation failed (HTTP ${sessionRes.status}): ${text.substring(0, 200)}`);
        }

        const sessionData = await sessionRes.json();

        if (!sessionData.success || !sessionData.sessionId) {
            throw new Error(sessionData.error || "Failed to create payment session");
        }

        console.log("Stripe session created:", sessionData.sessionId);

        // Step 2: Save pending order to database
        const orderRes = await fetch('place-order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                customer_name: name,
                phone: phone,
                notes: notes,
                total_amount: total,
                items: cart,
                payment_method: 'stripe',
                stripe_session_id: sessionData.sessionId
            })
        });

        if (!orderRes.ok) {
            const text = await orderRes.text();
            throw new Error(`Order save failed (HTTP ${orderRes.status}): ${text.substring(0, 200)}`);
        }

        const orderData = await orderRes.json();

        if (!orderData.success || !orderData.order_id) {
            throw new Error(orderData.message || "Failed to save order");
        }

        console.log("Order saved successfully! ID:", orderData.order_id);

        // Step 3: Redirect to Stripe Checkout
        const stripe = Stripe('pk_test_51T4boFHWrfyRRRiKL7MLXoVgQRh15T7tTzc5LxW2KVoe34r5gf5CCtXSk7bfl6ppeyUIAt3iV5PGaaozjhC9N0wV00y6EcdaLs'); // ← REPLACE with your REAL pk_test_...
        const { error } = await stripe.redirectToCheckout({
            sessionId: sessionData.sessionId
        });

        if (error) {
            console.error("Stripe redirect error:", error);
            alert("Payment redirect error: " + (error.message || "Unknown"));
        }
        } catch (err) {
    console.error("Error details:", err);

    let msg = "Something went wrong.";
    
    if (err.message.includes('Unexpected token') || err.message.includes('<')) {
        msg = "Server sent an error page instead of JSON (probably PHP notice or error). Check place-order.php";
    } else if (err.message.includes('json')) {
        msg = "Invalid response from server – not valid JSON";
    }

    alert(msg + "\n\nDetail: " + err.message);
    console.log("Full response (if available):", err);   // ← helps debugging
    } finally {
        this.disabled = false;
        this.textContent = 'Place Order';
    }
});

