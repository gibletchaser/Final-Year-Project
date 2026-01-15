<?php
session_start();

// 🚫 If user already logged in, kick them out
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>


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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb4J+T9qOhect/aeX7n3k2c7r3f5g=="
          crossorigin=""/>

  </head>
  <body>
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
						    <p class="mb-0 register-link"><span>Open hours:</span> <span>Monday - Saturday</span> <span>10:00AM - 9:00PM</span></p>
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
	        	<li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
	        	<li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	        	<li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
	          	<li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>                
               <li class="nav-item d-flex align-items-center" id="auth-area">
        <a href="sign in.php" class="nav-link btn btn-primary px-4 py-2" style="border-radius: 5px;">Sign In</a>
    </li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate text-center mb-4">
            <h1 class="mb-2 bread">Login To Account</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Sign Up <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>
		
		<section class="ftco-section ftco-no-pt ftco-no-pb">
			<div class="container-fluid px-0">
				<div class="row d-flex no-gutters">
          <div class="col-md-6 order-md-last ftco-animate makereservation p-4 p-md-5 pt-5">
          	<div class="py-md-5">
	          	<div class="heading-section ftco-animate mb-5">
		          	<span class="subheading">Login Account</span>
		            <h2 class="mb-4">Sign Up</h2>
		          </div>
	            <form action="#">
	              <div class="row">
	                <div class="col-md-6">
	                  <div class="form-group">
	                    <label for="">Name</label>
                        <input type="text" id="userName" class="form-control" placeholder="Your Name">
	                  </div>
	                </div>
	                <div class="col-md-6">
	                  <div class="form-group">
	                    <label for="">Email</label>
                        <input type="text" id="userEmail" class="form-control" placeholder="Your Email">
	                  </div>
	                </div>
	                <div class="col-md-6">
	                  <div class="form-group">
	                    <label for="">Phone</label>
                        <input type="text" id="userPhone" class="form-control" placeholder="Phone">
	                  </div>
	                </div>
                  <div class="col-md-6">
	                  <div class="form-group">
	                    <label for="">Password</label>
                        <input type="password" id="userPassword" class="form-control" placeholder="Password">
	                  </div>
	                </div>

	                <div class="col-md-12 mt-3">
	                  <div class="form-group">
	                    <input type="button" onclick="saveUserData()" value="Register" class="btn btn-primary py-3 px-5">
	                  </div>
	                </div>
	              </div>
	            </form>
	          </div>
          </div>
        <div class="col-md-6 d-flex align-items-stretch">
          <div class="menus d-sm-flex ftco-animate align-items-stretch">
					   <img src="images/Register.png" alt="Logo" style="display: block; margin: auto; width: 400px;">
             </div>
          </div>
        </div>
      </div>
		</section>
    		
    <footer class="ftco-footer ftco-bg-dark ftco-section">
      </footer>

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
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha512-n7o7r2iW6z4qA6lC+4sR7b3l9rH9fJ+gI7r9s5p5f5uW4z5T9f5t8f5f5uW4z5T9f5t8f5uW4z5T9f5t8f5u=="
        crossorigin=""></script>

<script>
function saveUserData() {
    const name = document.getElementById('userName').value.trim();
    const email = document.getElementById('userEmail').value.trim();
    const phone = document.getElementById('userPhone').value.trim();
    const password = document.getElementById('userPassword').value;

    if (!name || !password || !email) {
        alert("Please fill in Name, Email, and Password");
        return;
    }

    // --- NEW LOGIC: Save to LocalStorage so Profile can read it ---
    const user = { 
        name: name, 
        email: email, 
        phone: phone, 
        password: password 
    };
    localStorage.setItem('yobYongSession', JSON.stringify(user));
    // -----------------------------------------------------------

    const data = new FormData();
    data.append("name", name);
    data.append("email", email);
    data.append("phone", phone);
    data.append("password", password);

    fetch("register.php", {
        method: "POST",
        body: data
    })
    .then(res => res.text())
    .then(res => {
        if (res === "success") {
            alert("Registration successful!");
            // Jump straight to profile to view details
            window.location.href = "profile.php"; 
        } else {
            // Even if DB fails, for now, we let them proceed locally
            alert("Welcome! (Local Session Started)");
            window.location.href = "profile.php";
        }
    });
}</script>

<script>
function loginUser() {
    const email = loginEmail.value.trim();
    const password = loginPassword.value;

    if (!email || !password) {
        alert("Please fill in email and password");
        return;
    }

    const data = new FormData();
    data.append("email", email);
    data.append("password", password);

    fetch("login.php", {
        method: "POST",
        body: data
    })
    .then(res => res.text())
    .then(res => {
        if (res === "success") {
            window.location.href = "about.php"; // redirect after login
        } else if (res === "missing") {
            alert("Please fill all fields");
        } else {
            alert("Invalid email or password");
        }
    });
}
</script>

</body>
</html>
