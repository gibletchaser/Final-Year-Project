// =============================================
// script.js - Complete & Fixed Cart + PayPal Integration
// =============================================

// Global cart – load once from localStorage
let cart = JSON.parse(localStorage.getItem('cart') || '[]');

// Generate unique ID from item name
function getItemId(name) {
    return name.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
}

// Render cart in modal + update badges & totals
function renderCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const modalTotal = document.getElementById('cartTotal');

    // All possible places where item count is shown
    const countEls = [
        document.getElementById('cart-count'),
        document.getElementById('cart-item-count'),
        document.querySelector('.cart-item-count'),
        document.querySelector('#navbar-cart-count')
    ];

    // All possible places where total price is shown
    const totalEls = [
        modalTotal,
        document.getElementById('cart-total-display'),
        document.getElementById('cart-total'),
        document.querySelector('.cart-total'),
        document.querySelector('#navbar-cart-total')
    ];

    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0).toFixed(2);

    // Update count badges
    countEls.forEach(el => { if (el) el.textContent = totalItems; });

    // Update total displays
    totalEls.forEach(el => { if (el) el.textContent = totalPrice; });

    // Render items in modal
    if (!cartItemsContainer) return;

    cartItemsContainer.innerHTML = cart.length === 0
        ? '<p class="text-center text-muted">Your cart is empty</p>'
        : '';

    cart.forEach(item => {
        const subtotal = (item.price * item.quantity).toFixed(2);
        const itemDiv = document.createElement('div');
        itemDiv.className = 'd-flex justify-content-between align-items-center py-3 border-bottom';
        itemDiv.innerHTML = `
            <div class="flex-grow-1">
                <div class="font-weight-bold mb-1">${item.name}</div>
                <small class="text-muted">$${item.price.toFixed(2)} × ${item.quantity}</small>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary decrease" data-id="${item.id}">-</button>
                <span class="mx-3 font-weight-bold" style="min-width:40px;text-align:center;">${item.quantity}</span>
                <button class="btn btn-sm btn-outline-secondary increase" data-id="${item.id}">+</button>
                <button class="btn btn-sm btn-danger ml-3 remove" data-id="${item.id}">Remove</button>
            </div>
            <div class="text-right font-weight-bold text-primary" style="min-width:80px;">
                $${subtotal}
            </div>
        `;
        cartItemsContainer.appendChild(itemDiv);
    });

    // Disable checkout button if cart empty
    const checkoutBtn = document.querySelector('#cartModal .modal-footer .btn-success');
    if (checkoutBtn) checkoutBtn.disabled = (cart.length === 0);
}

// Add item to cart from menu page
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

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
            existing.quantity += quantity;
        } else {
            cart.push({ id, name, price, quantity });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();

        if (qtyInput) qtyInput.value = '1';

        alert(`${quantity} × ${name} added to cart!`);
    });
});

// Quantity controls on menu items (before adding to cart)
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

// Cart modal item actions (increase, decrease, remove)
document.getElementById('cartItems')?.addEventListener('click', function(e) {
    let btn = e.target.closest('button');
    if (!btn || !btn.dataset?.id) return;

    const id = btn.dataset.id;
    const idx = cart.findIndex(i => i.id === id);
    if (idx === -1) return;

    if (btn.classList.contains('increase')) {
        cart[idx].quantity += 1;
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

// Refresh cart display when modal opens
$('#cartModal').on('show.bs.modal', renderCart);

// Initial render when page loads
renderCart();

// Submit order to server (used for both COD and PayPal success)
function submitOrder(name, phone, method, notes, cart, total, paypalTransactionId = null) {
    console.log("Submitting order to place-order.php:", {
        name, phone, method, total, paypalId: paypalTransactionId, cartLength: cart.length
    });

    const orderData = {
        customer_name: name,
        phone: phone,
        payment_method: method,
        notes: notes,
        items: cart,
        total_amount: total,
        paypal_transaction_id: paypalTransactionId || null
    };

    fetch('place-order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    })
    .then(res => {
        console.log("place-order.php status:", res.status);
        if (!res.ok) {
            return res.text().then(text => {
                console.error("place-order.php raw error:", text);
                throw new Error(`HTTP ${res.status}: ${text.substring(0,200)}`);
            });
        }
        return res.json();
    })
    .then(data => {
        console.log("place-order.php response:", data);
        if (data.success && data.order_id) {
            console.log("Order saved successfully! ID:", data.order_id);
            window.location.href = `receipt.php?order_id=${data.order_id}`;
        } else {
            console.warn("place-order.php did not confirm success:", data);
            alert("Payment captured, but order save failed: " + (data.message || "Unknown"));
        }
    })
    .catch(err => {
        console.error("Order submission failed:", err);
        alert("Failed to save order details: " + err.message + ". Payment was captured – contact support.");
    });
}

// COD path: Place Order button
document.getElementById('placeOrderBtn')?.addEventListener('click', function(e) {
    e.preventDefault();

    const name   = document.getElementById('orderName')?.value.trim();
    const phone  = document.getElementById('orderPhone')?.value.trim();
    const method = document.getElementById('paymentMethod')?.value;
    const notes  = document.getElementById('orderNotes')?.value.trim();

    if (!name || !phone) return alert("Name and phone required");
    if (cart.length === 0) return alert("Cart empty");

    const total = cart.reduce((s, i) => s + i.price * i.quantity, 0).toFixed(2);

    if (method === 'paypal') {
        alert("Please complete PayPal payment first.");
        return;
    }

    submitOrder(name, phone, method, notes, cart, total);
});

// PayPal Button – only ONE render
document.getElementById('paymentMethod')?.addEventListener('change', function() {
    const container = document.getElementById('paypal-button-container');
    const placeBtn  = document.getElementById('placeOrderBtn');

    if (!container) return;

    container.style.display = this.value === 'paypal' ? 'block' : 'none';
    if (placeBtn) placeBtn.style.display = this.value === 'paypal' ? 'none' : 'block';

    if (this.value === 'paypal' && !container.hasChildNodes()) {
        console.log("Rendering PayPal buttons (only once)");

        paypal.Buttons({
            style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'paypal' },

            createOrder: (data, actions) => {
                const total = cart.reduce((s, i) => s + i.price * i.quantity, 0).toFixed(2);
                if (total <= 0) {
                    alert("Cart is empty");
                    throw new Error("Zero total");
                }

                return fetch('create-paypal-order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ total, currency: 'MYR', description: 'Yob Yong Order' })
                })
                .then(r => {
                    if (!r.ok) throw new Error(`HTTP ${r.status}`);
                    return r.json();
                })
                .then(o => {
                    if (o.error) throw new Error(o.error);
                    return o.id;
                })
                .catch(err => {
                    alert("Failed to create PayPal order: " + err.message);
                    throw err;
                });
            },

            onApprove: (data, actions) => {
                console.log("Payment approved – capturing:", data.orderID);

                return fetch('capture-paypal-order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ orderID: data.orderID })
                })
                .then(r => {
                    console.log("Capture status:", r.status);
                    if (!r.ok) {
                        return r.text().then(text => {
                            console.error("Capture raw error:", text);
                            throw new Error(`Capture failed HTTP ${r.status}: ${text.substring(0,200)}`);
                        });
                    }
                    return r.json();
                })
                .then(result => {
                    console.log("Capture full result:", result);

                    if (result && result.success === true && result.your_order_id) {
                        alert("Payment successful! Order #" + result.your_order_id);

                        const name  = document.getElementById('orderName')?.value.trim() || "Unknown";
                        const phone = document.getElementById('orderPhone')?.value.trim() || "Unknown";
                        const notes = document.getElementById('orderNotes')?.value.trim() || "";

                        const total = cart.reduce((s, i) => s + i.price * i.quantity, 0).toFixed(2);

                        // Save order + items to database
                        submitOrder(name, phone, 'paypal', notes, cart, total, result.your_order_id || data.orderID);

                        // Clear cart & UI
                        localStorage.removeItem('cart');
                        renderCart?.();
                        $('#cartModal').modal('hide');

                        // Redirect to receipt
                        window.location.href = `receipt.php?order_id=${result.your_order_id}`;
                    } else {
                        console.warn("Capture succeeded but invalid response:", result);
                        throw new Error("Server did not return success/order ID");
                    }
                })
                .catch(err => {
                    console.error("Finalization error:", err);
                    alert("Error finalizing payment: " + err.message + ". Check console for details.");
                });
            },

            onCancel: () => alert("Payment cancelled."),
            onError: err => {
                console.error("PayPal SDK error:", err);
                alert("PayPal error: " + (err.message || "Unknown error"));
            }
        }).render('#paypal-button-container')
        .catch(err => console.error("PayPal render failed:", err));
    }
});
