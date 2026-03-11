let cart = JSON.parse(localStorage.getItem("cart") || "[]");

function saveCart(){
localStorage.setItem("cart",JSON.stringify(cart));
}

function renderCart(){

const container = document.getElementById("cartItems");
const totalEl = document.getElementById("cartTotal");

let total = 0;
let count = 0;

if(!container) return;

container.innerHTML="";

if(cart.length===0){
container.innerHTML='<p class="text-muted text-center">Cart empty</p>';
}

cart.forEach(item=>{

total += item.price * item.quantity;
count += item.quantity;

const div = document.createElement("div");

div.className="cart-row";

div.innerHTML=`

<div class="cart-name">${item.name}</div>

<div class="cart-controls">

<button class="dec" data-id="${item.id}">-</button>

<span>${item.quantity}</span>

<button class="inc" data-id="${item.id}">+</button>

<button class="remove" data-id="${item.id}">x</button>

</div>

<div>$${(item.price*item.quantity).toFixed(2)}</div>

`;

container.appendChild(div);

});

if(totalEl) totalEl.textContent = total.toFixed(2);

document.querySelectorAll(".cart-count").forEach(el=>{
el.textContent=count;
});

saveCart();
}

document.addEventListener("click",function(e){

const btn = e.target;

if(btn.classList.contains("add-to-cart")){

const item = btn.closest("[data-id]");

const id = parseInt(item.dataset.id);

const name = item.dataset.name;

const price = parseFloat(item.dataset.price);

let qtyInput = item.querySelector(".qty-input");

let qty = qtyInput ? parseInt(qtyInput.value) : 1;

if(qty<1) qty=1;

let existing = cart.find(i=>i.id===id);

if(existing){

existing.quantity += qty;

}else{

cart.push({
id:id,
name:name,
price:price,
quantity:qty
});

}

renderCart();

alert(name+" added to cart");

}

if(btn.classList.contains("inc")){

let id = parseInt(btn.dataset.id);

let item = cart.find(i=>i.id===id);

if(item){

item.quantity++;

renderCart();

}

}

if(btn.classList.contains("dec")){

let id = parseInt(btn.dataset.id);

let item = cart.find(i=>i.id===id);

if(item){

item.quantity--;

if(item.quantity<=0){

cart = cart.filter(i=>i.id!==id);

}

renderCart();

}

}

if(btn.classList.contains("remove")){

let id = parseInt(btn.dataset.id);

cart = cart.filter(i=>i.id!==id);

renderCart();

}

});

renderCart();

document.getElementById("placeOrderBtn")?.addEventListener("click",async function(){

const name = document.getElementById("orderName").value.trim();
const phone = document.getElementById("orderPhone").value.trim();
const notes = document.getElementById("orderNotes").value.trim();

if(!name || !phone){

alert("Fill name and phone");

return;

}

if(cart.length===0){

alert("Cart empty");

return;

}

let total = cart.reduce((s,i)=>s+i.price*i.quantity,0);

try{

const sessionRes = await fetch("create-stripe-checkout.php",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({
customer_name:name,
phone:phone,
notes:notes,
amount:total,
cart:cart
})

});

const sessionText = await sessionRes.text();

let sessionData;

try{
sessionData = JSON.parse(sessionText);
}catch{

console.error("HTML returned:",sessionText);

throw new Error("Server returned HTML instead of JSON");

}

if(!sessionData.success){

throw new Error(sessionData.error);

}

const orderRes = await fetch("place-order.php",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({
customer_name:name,
phone:phone,
notes:notes,
total_amount:total,
items:cart,
payment_method:"stripe",
stripe_session_id:sessionData.sessionId
})

});

const orderText = await orderRes.text();

let orderData;

try{
orderData = JSON.parse(orderText);
}catch{

console.error(orderText);

throw new Error("Order returned HTML");

}

if(!orderData.success){

throw new Error(orderData.message);

}

const stripe = Stripe("pk_test_REPLACE_WITH_YOUR_KEY");

await stripe.redirectToCheckout({
sessionId:sessionData.sessionId
});

}catch(err){

console.error(err);

alert("Checkout error: "+err.message);

}

});
