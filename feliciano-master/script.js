let cart = JSON.parse(localStorage.getItem('cart')) || []; 

const menuItems = document.querySelectorAll('.menu-item');
const cartItemsDiv = document.getElementById('cart-items');
const cartCount = document.getElementById('cart-count');
const cartTotal = document.getElementById('cart-total');

menuItems.forEach(item => {
    const addBtn = item.querySelector('.add-to-cart');
    addBtn.addEventListener('click', () => {
        const id = parseInt(item.dataset.id);        
        const name = item.dataset.name;
        const price = parseFloat(item.dataset.price);

        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        updateCart();
    });
});

function updateCart() {
    cartItemsDiv.innerHTML = '';
    let total = 0;
    let count = 0;

    cart.forEach((item, index) => {
        const subtotal = item.price * item.qty;
        total += subtotal;
        count += item.qty;

        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <span>${item.name}</span>
            <span>$${item.price} × ${item.qty} = $${subtotal.toFixed(2)}</span>
            <button onclick="removeItem(${index})">Remove</button>
        `;
        cartItemsDiv.appendChild(div);
    });

    cartCount.textContent = count > 0 ? `Cart: ${count} items` : 'Cart: empty';
    cartTotal.textContent = `Total: $${total.toFixed(2)}`;

    localStorage.setItem('cart', JSON.stringify(cart));
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

updateCart();