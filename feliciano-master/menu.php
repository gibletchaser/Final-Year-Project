<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yob Yong Ordering System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    $servername = "localhost"; // Adjust if needed
    $username = "root"; // Adjust if needed
    $password = ""; // Adjust if needed
    $dbname = "yobyong"; // Adjust if needed

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to generate all menu items (ignoring category)
    function generateMenuItems($conn) {
        $sql = "SELECT id, name, price, image FROM menu";
        $result = $conn->query($sql);
        
        if (!$result) {
            return "<p>Error: Query failed - " . $conn->error . "</p>";
        }
        
        $output = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $output .= '<div class="col-md-12 col-lg-6 d-flex align-self-stretch">';
                $output .= '<div class="menus d-sm-flex ftco-animate align-items-stretch">';
                $output .= '<div class="menu-img img" style="background-image: url(\'images/' . htmlspecialchars($row["image"]) . '\');"></div>';
                $output .= '<div class="text d-flex align-items-center">';
                $output .= '<div>';
                $output .= '<div class="d-flex">';
                $output .= '<div class="one-half">';
                $output .= '<h3 data-name="' . htmlspecialchars($row["name"]) . '">' . htmlspecialchars($row["name"]) . '</h3>';
                $output .= '</div>';
                $output .= '<div class="one-forth">';
                $output .= '<span class="price" data-price="' . htmlspecialchars($row["price"]) . '">$' . htmlspecialchars($row["price"]) . '</span>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>'; // Hardcoded; add to DB if needed
                $output .= '<div class="quantity-selector d-flex align-items-center mt-3">';
                $output .= '<button class="qty-btn minus" type="button">-</button>';
                $output .= '<input type="number" class="qty-input" value="1" min="1" readonly>';
                $output .= '<button class="qty-btn plus" type="button">+</button>';
                $output .= '</div>';
                $output .= '<button class="btn btn-primary add-to-cart">Add to Cart</button>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
        } else {
            $output = '<p>No items available.</p>';
        }

        return $output;
    }
    ?>

    <div class="py-1 bg-black top">
    	<div class="container">
    		<div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
	    		<div class="col-lg-12 d-block">
		    		<div class="row d-flex">
		    			<div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
						    <span class="text">+6012-26828864</span>
					    </div>
					    <div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
						    <span class="text">yobyong24@gmail.com</span>
					    </div>
					    <div class="col-md-5 pr-4 d-flex topper align-items-center text-lg-right justify-content-end">
						    <p class="mb-0 register-link"><span>Open hours:</span> <span>Monday - Sunday</span> <span>10:00AM - 9:00PM</span></p>
					    </div>
				    </div>
			    </div>
		    </div>
		  </div>
    </div>
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.php">Yob Yong</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	        	<li class="nav-item"><a href="about.php" class="nav-link">Home</a></li>
	        	<li class="nav-item active"><a href="menu.php" class="nav-link">Menu</a></li>
				<li class="nav-item">
   				 <a href="#" class="nav-link" data-toggle="modal" data-target="#cartModal">
        			<i class="fas fa-shopping-cart fa-lg"></i>
       				 <span class="badge badge-pill badge-warning ml-1" id="cart-count">0</span>
    			</a>
				</li>
				<li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
         <li class="nav-item d-flex align-items-center" id="auth-area">
        <a href="sign in.php" class="nav-link btn btn-primary px-4 py-2" style="border-radius: 5px;">Sign In</a>
    </li>
			  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	        </ul>
	      </div>	
	    </div>
	  </nav>
    <!-- END nav -->
    
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate text-center mb-4">
            <h1 class="mb-2 bread">Specialties</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Menu <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>


		<section class="ftco-section">
    	<div class="container">
        <div class="ftco-search">
		<div class="row">
            <div class="col-md-12 nav-link-wrap">
	            <div class="nav nav-pills d-flex text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
	              <a class="nav-link ftco-animate active" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Breakfast</a>

	              <a class="nav-link ftco-animate" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2" role="tab" aria-controls="v-pills-2" aria-selected="false">Lunch</a>

	              <a class="nav-link ftco-animate" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab" aria-controls="v-pills-3" aria-selected="false">Dinner</a>

	              <a class="nav-link ftco-animate" id="v-pills-4-tab" data-toggle="pill" href="#v-pills-4" role="tab" aria-controls="v-pills-4" aria-selected="false">Drinks</a>

	              <a class="nav-link ftco-animate" id="v-pills-5-tab" data-toggle="pill" href="#v-pills-5" role="tab" aria-controls="v-pills-5" aria-selected="false">Desserts</a>

	            </div>
	          </div>
	          
    </header>

		<div class="col-md-12 tab-wrap">
	            
	        <div class="tab-content" id="v-pills-tabContent">

	            <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="day-1-tab">
	              	<div class="row no-gutters d-flex align-items-stretch">
					        	<?php echo generateMenuItems($conn); ?>
					</div>
				</div>
	        </div>
	    </div>
	    </div>
	    </div>
        </div>
    	</div>
    </section>

<div class="modal fade" id="cartModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Your Cart</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div id="cartItems" class="cart-items"></div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <h5>Total:</h5>
                    <h5>$<span id="cartTotal">0.00</span></h5>
                </div>

                <!-- Place Order Form -->
                <form id="placeOrderForm">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" id="orderName" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" class="form-control" id="orderPhone" required placeholder="012-3456789">
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
          <select class="form-control" required>
                <option value="">-- Select Payment Method --</option>
                <option value="stripe">Credit/Debit Card or FPX (Stripe)</option>
            </select>
        </div>

        <!-- Stripe container – right after select, outside of it -->
        <div id="stripe-payment-container" style="margin-top: 20px; display: none; text-align: center;">
            <button id="stripe-pay-btn" class="btn btn-primary btn-lg">Pay with Stripe →</button>
            <p id="stripe-message" style="margin-top:10px; color:#666;"></p>
        </div>
 
                    <div class="form-group">
                        <label>Additional Notes</label>
                        <textarea class="form-control" id="orderNotes" rows="2" placeholder="e.g. Less spicy, No onion..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue Shopping</button>
                <button type="button" class="btn btn-success" id="placeOrderBtn">Place Order</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/your-kit-code.js"></script> <!-- if using fa icons -->
<script src="script.js"></script>

    <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-6 col-lg-3">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Yob Yong</h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                <li class="ftco-animate"><a href="https://www.facebook.com/YobnYongsSemarak/"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="https://www.instagram.com/yobyongs_utmkl/"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Open Hours</h2>
              <ul class="list-unstyled open-hours">
                <li class="d-flex"><span>Monday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Tuesday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Wednesday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Thursday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Friday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Saturday</span><span>10:00 - 21:00</span></li>
              </ul>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Instagram</h2>
              <div class="thumb d-sm-flex">
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-1.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-2.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-3.jpg);">
	            	</a>
	            </div>
	            <div class="thumb d-flex">
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-4.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-5.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-6.jpg);">
	            	</a>
	            </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Newsletter</h2>
            	<p>Far far away, behind the word mountains, far from the countries.</p>
              <form action="#" class="subscribe-form">
                <div class="form-group">
                  <input type="text" class="form-control mb-2 text-center" placeholder="Enter email address">
                  <input type="submit" value="Subscribe" class="form-control submit px-3">
                </div>
              </form>
            </div>
          </div>
        </div>

          
        
      </div>
    </footer>
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
  <script src="script.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSession = localStorage.getItem('yobYongSession');
    const authArea = document.getElementById('auth-area');

    if (userSession && authArea) {
        const user = JSON.parse(userSession);

        // This replaces the button with ONLY the circle user icon
        authArea.innerHTML = `
            <div class="dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center" id="userMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #c4a47c; display: flex; align-items: center; justify-content: center; background: transparent; color: #c4a47c;">
                        <span class="icon-user" style="font-size: 20px;"></span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="userMenu" style="background: #fff; margin-top: 10px; border-radius: 10px; min-width: 180px;">
                    <div class="dropdown-header" style="color: #333; font-weight: 600; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 5px;">
                        Hi, ${user.name}
                    </div>
                    <a class="dropdown-item py-2" href="profile.php" style="color: #444; font-size: 14px;">
                        <span class="icon-person mr-2"></span> View My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item py-2" href="#" onclick="handleLogout()" style="color: #e74c3c; font-size: 14px; font-weight: 600;">
                        <span class="icon-log-out mr-2"></span> Sign Out
                    </a>
                </div>
            </div>
        `;
    }
});

function handleLogout() {
    if(confirm("Are you sure you want to sign out?")) {
        localStorage.removeItem('yobYongSession');
        window.location.href = 'index.php'; 
    }
}
</script>

<script>
// Handle Pay button click
document.getElementById('stripe-pay-btn')?.addEventListener('click', async function() {
    const name  = document.getElementById('orderName').value.trim();
    const phone = document.getElementById('orderPhone').value.trim();
    const notes = document.getElementById('orderNotes').value.trim();
    const total = parseFloat(document.getElementById('cartTotal').textContent) || 0;

    if (!name || !phone || total <= 0) {
        alert("Please fill name, phone, and ensure cart is not empty.");
        return;
    }

    this.disabled = true;
    document.getElementById('stripe-message').textContent = 'Creating secure payment session...';

    try {
        const response = await fetch('create-stripe-checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                customer_name: name,
                phone: phone,
                notes: notes,
                amount: total,              // in MYR
                cart: JSON.parse(localStorage.getItem('cart') || '[]')
            })
        });

        const data = await response.json();

        if (data.success && data.sessionId) {
            const stripe = Stripe('pk_test_51T4boFHWrfyRRRiKL7MLXoVgQRh15T7tTzc5LxW2KVoe34r5gf5CCtXSk7bfl6ppeyUIAt3iV5PGaaozjhC9N0wV00y6EcdaLs');  // ← REPLACE with your pk_test_...
            stripe.redirectToCheckout({ sessionId: data.sessionId });
        } else {
            alert("Error: " + (data.error || "Could not create payment session"));
        }
    } catch (err) {
        alert("Network error: " + err.message);
    } finally {
        this.disabled = false;
    }
});

// After getting session from create-stripe-checkout.php
if (data.success && data.sessionId) {
    // Now save order with this sessionId
    const orderResponse = await fetch('place-order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            customer_name: name,
            phone: phone,
            notes: notes,
            total_amount: total,
            items: cart,
            payment_method: 'stripe',
            stripe_session_id: data.sessionId   // ← key change
        })
    });

    const orderData = await orderResponse.json();

    if (orderData.success) {
        const stripe = Stripe('pk_test_...');
        stripe.redirectToCheckout({ sessionId: data.sessionId });
    } else {
        alert("Failed to save order: " + orderData.message);
    }
}
</script>

<script>
   document.getElementById('placeOrderBtn').addEventListener('click', async function() {
    const name   = document.getElementById('orderName').value.trim();
    const phone  = document.getElementById('orderPhone').value.trim();
    const notes  = document.getElementById('orderNotes').value.trim();
    const total  = parseFloat(document.getElementById('cartTotal').textContent) || 0;
    const cart   = JSON.parse(localStorage.getItem('cart') || '[]');

    console.log('Cart from localStorage:', cart);           // ← add this
    console.log('Cart length:', cart.length);               // ← add this
    console.log('Total from display:', total);              // ← add this

    if (!name || !phone) {
        alert("Please fill in your full name and phone number.");
        return;
    }
    if (cart.length === 0 || total <= 0) {
        alert("Your cart is empty! Add some items first.");
        return;
    }

    this.disabled = true;
    this.textContent = 'Processing...';

    try {
        // Step 1: Create Stripe Checkout Session
        const sessionResponse = await fetch('create-stripe-checkout.php', {
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

        const sessionData = await sessionResponse.json();

        if (!sessionData.success || !sessionData.sessionId) {
            throw new Error(sessionData.error || "Failed to create payment session. Please try again.");
        }

        // Step 2: Save pending order in your DB with session ID
        const orderResponse = await fetch('place-order.php', {
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

        const orderData = await orderResponse.json();

        if (!orderData.success) {
            throw new Error(orderData.message || "Failed to save your order. Please contact support.");
        }

        // Step 3: Redirect to Stripe Checkout
        const stripe = Stripe('pk_test_51T4boFHWrfyRRRiKL7MLXoVgQRh15T7tTzc5LxW2KVoe34r5gf5CCtXSk7bfl6ppeyUIAt3iV5PGaaozjhC9N0wV00y6EcdaLs');  // ← REPLACE with your REAL test/live publishable key
        const { error } = await stripe.redirectToCheckout({
            sessionId: sessionData.sessionId
        });

        if (error) {
            console.error('Stripe redirect error:', error);
            alert("Payment setup error: " + (error.message || "Unknown issue. Please try again."));
        }

        // If no error, user is redirected – no need for more code here

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
</script>

<script>
    // Fix aria-hidden focus conflict on modal hide
$('#cartModal').on('hide.bs.modal', function (event) {
    // Set inert before Bootstrap applies aria-hidden
    this.inert = true;
});

$('#cartModal').on('hidden.bs.modal', function (event) {
    // Clean up after fully hidden
    this.inert = false;
});

// Optional: When modal shows, ensure focus goes inside (Bootstrap usually handles this)
$('#cartModal').on('shown.bs.modal', function () {
    // e.g. focus first input
    document.getElementById('orderName')?.focus();
});
</script>
  </body>
</html>

<?php $conn->close(); // Close DB connection at the end ?>