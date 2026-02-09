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
    // Database connection
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

	              <a class="nav-link ftco-animate" id="v-pills-6-tab" data-toggle="pill" href="#v-pills-6" role="tab" aria-controls="v-pills-6" aria-selected="false">Wine</a>

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

		<!-- Cart Modal -->
<!-- Cart Modal -->
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
                        <select class="form-control" id="paymentMethod" required>
                         <option value="cash_on_delivery">Cash on Delivery</option>
                         <option value="paypal">PayPal</option>
                 </select>

    <div id="paypal-button-container" style="margin-top: 15px; display: none;"></div>
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
<script src="https://www.paypal.com/sdk/js?client-id=ATYkEEnovNtPctjWpE5ViGlEfEi8WhAplmEhklTwEFN6CAPNpZdDS-B0ZFJiCfxx60cRm508GOPC9sOa&currency=MYR&intent=capture"></script>

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
// Make PayPal container appear/disappear based on selection
document.getElementById('paymentMethod').addEventListener('change', function() {
    const paypalContainer = document.getElementById('paypal-button-container');
    
    if (this.value === 'paypal') {
        paypalContainer.style.display = 'block';
        
        // Render PayPal buttons (only once, or re-render if needed)
        if (!paypalContainer.hasChildNodes()) {  // prevent duplicate renders
            paypal.Buttons({
                // Your PayPal button config here (minimal version to start)
                style: {
                    layout: 'vertical',
                    color:  'gold',
                    shape:  'rect',
                    label:  'paypal'
                },
                createOrder: function(data, actions) {
                    // For testing: create a simple order with your cart total
                    const total = parseFloat(document.getElementById('cartTotal').textContent) || 0;
                    
                    if (total <= 0) {
                        alert("Cart is empty!");
                        return;
                    }
                    
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: total.toFixed(2),
                                currency_code: 'MYR'
                            },
                            description: 'Yob Yong Order'
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert('Payment successful! Transaction ID: ' + details.id);
                        // Here: trigger your normal order submission
                        // You can call your placeOrderBtn logic or submit form
                        document.getElementById('placeOrderBtn').click(); // or your fetch
                    });
                },
                onCancel: function() {
                    alert('Payment cancelled.');
                },
                onError: function(err) {
                    console.error('PayPal error:', err);
                    alert('An error occurred with PayPal. Please try again.');
                }
            }).render('#paypal-button-container');
        }
    } else {
        paypalContainer.style.display = 'none';
        paypalContainer.innerHTML = ''; // optional: clear buttons when switching away
    }
});
</script>

<script>
// Helper: Submit order to place_order.php
function submitOrder(orderData) {
    console.log("Submitting order:", orderData);

    fetch('place_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    })
    .then(res => {
        console.log("place-order.php status:", res.status);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    })
    .then(data => {
        console.log("place-order.php response:", data);
        if (data.success && data.order_id) {
            alert("Order placed! Order ID: " + data.order_id);
            localStorage.removeItem('cart');
            if (typeof updateCartDisplay === 'function') updateCartDisplay();
            $('#cartModal').modal('hide');
            window.location.href = `receipt.php?order_id=${data.order_id}`;
        } else {
            alert("Order failed: " + (data.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Order save error:", err);
        alert("Failed to save order: " + err.message);
    });
}

// Place Order button (only for COD)
document.getElementById('placeOrderBtn')?.addEventListener('click', function() {
    const name   = document.getElementById('orderName').value.trim();
    const phone  = document.getElementById('orderPhone').value.trim();
    const method = document.getElementById('paymentMethod').value;
    const notes  = document.getElementById('orderNotes').value.trim();
    const cart   = JSON.parse(localStorage.getItem('cart') || '[]');

    if (!name || !phone) {
        alert("Please fill name and phone.");
        return;
    }
    if (cart.length === 0) {
        alert("Cart is empty!");
        return;
    }

    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    if (method === 'paypal') {
        alert("Please complete PayPal payment first.");
        return;
    }

    const orderData = {
        customer_name: name,
        phone: phone,
        payment_method: method,
        notes: notes,
        items: cart,
        total_amount: total
    };

    submitOrder(orderData);
});

// Show PayPal buttons when selected
document.getElementById('paymentMethod').addEventListener('change', function() {
    const container = document.getElementById('paypal-button-container');
    container.style.display = (this.value === 'paypal') ? 'block' : 'none';

    if (this.value === 'paypal' && !container.hasChildNodes()) {
        console.log("Rendering PayPal buttons...");

        paypal.Buttons({
            style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'paypal' },

            createOrder: function(data, actions) {
                const total = parseFloat(document.getElementById('cartTotal').textContent) || 0;
                if (total <= 0) {
                    alert("Cart is empty!");
                    return;
                }
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: total.toFixed(2), currency_code: 'MYR' },
                        description: 'Yob Yong Order'
                    }]
                });
            },

            onApprove: function(data, actions) {
    return actions.order.capture().then(function(details) {
        // Collect everything needed for the DB
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

        const orderData = {
            customer_name: document.getElementById('orderName').value.trim(),
            phone: document.getElementById('orderPhone').value.trim(),
            payment_method: 'paypal',
            notes: document.getElementById('orderNotes').value.trim(),
            items: cart,
            total_amount: total,
            paypal_transaction_id: details.id // This is details.id from PayPal
        };

        // Call your submission function
        submitOrder(orderData);
    });
}

            onCancel: () => alert('Payment cancelled.'),
            onError: (err) => {
                console.error('PayPal error:', err);
                alert('PayPal error. Please try again.');
            }
        }).render('#paypal-button-container');
    }
});
</script>
  </body>
</html>

<?php $conn->close(); // Close DB connection at the end ?>