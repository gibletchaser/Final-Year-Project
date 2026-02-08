<?php
include 'db.php';
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
	        	<li class="nav-item active"><a href="about.php" class="nav-link">Home</a></li>
	        	<li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
<li class="nav-item d-flex align-items-center" id="auth-area">
        <a href="sign in.php" class="nav-link btn btn-primary px-4 py-2" style="border-radius: 5px;">Sign In</a>
    </li>	    
  	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->
    
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate text-center mb-4">
            <h1 class="mb-2 bread">About</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>About <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>


		<section class="ftco-section ftco-wrap-about">
			<div class="container">
				<div class="row">
					<div class="col-md-7 d-flex">
					<div class="img img-1 mr-md-2" style="background-image: url(images/about.jpg);"></div>
						<div class="img img-2 ml-md-2" style="background-image: url(images/about-1.jpg);"></div>
          </div>
					<div class="col-md-5 wrap-about pt-5 pt-md-5 pb-md-3 ftco-animate">
	          <div class="heading-section mb-4 my-5 my-md-0">
	          	<span class="subheading">About</span>
	            <h2 class="mb-4">Yob Yong Restaurant</h2>
	          </div>
	          <p>A gentle stream of aroma flows through Yob Yong Restaurant, supplying the kitchen with daily freshness and care, creating a welcoming place where comforting flavors seem to drift effortlessly to your table.</p>
						<pc class="time">
							<span>Mon - Sat <strong>10 AM - 9 PM</strong></span>
							<span><a href="#">+6012-26828864</a></span>
						</p>
					</div>
				</div>
			</div>
		</section>

		
		<section class="ftco-section ftco-counter img ftco-no-pt" id="section-counter">
    	<div class="container">
    		<div class="row d-md-flex">
    			<div class="col-md-9">
    				<div class="row d-md-flex align-items-center">
		          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18">
		              <div class="text">
		                <strong class="number" data-number="6">0</strong>
		                <span>Years of Experienced</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18">
		              <div class="text">
		                <strong class="number" data-number="28">0</strong>
		                <span>Menus/Dish</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18">
		              <div class="text">
		                <strong class="number" data-number="10">0</strong>
		                <span>Staffs</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18">
		              <div class="text">
		                <strong class="number" data-number="16000">0</strong>
		                <span>Happy Customers</span>
		              </div>
		            </div>
		          </div>
	          </div>
          </div>
          <div class="col-md-3 text-center text-md-left">
          	<p>Brings authentic flavors that awaken the senses and create lasting, memorable dining experiences.</p>
          </div>
        </div>
    	</div>
    </section>
    
		<section class="ftco-section">
			<div class="container">
				<div class="row justify-content-center mb-5 pb-2">
          <div class="col-md-12 text-center heading-section ftco-animate">
          	<span class="subheading">Chef</span>
            <h2 class="mb-4">Our Master Chef</h2>
          </div>
        </div>	
				<div class="row">
					<div class="col-md-6 col-lg-3 ftco-animate">
						<div class="staff">
							<div class="img" style="background-image: url(images/1634696384594512.jpg);"></div>
							<div class="text pt-4">
								<h3>Ahmad Faizal</h3>
								<span class="position mb-2">Restaurant Owner</span>
								<!-- <p>A small river named Duden flows by their place and supplies</p> -->
								<div class="faded">
									<!-- <p>I am an ambitious workaholic, but apart from that, pretty simple person.</p> -->
									<ul class="ftco-social d-flex">
		                <li class="ftco-animate"><a href="https://www.facebook.com/YobnYongsSemarak/"><span class="icon-facebook"></span></a></li>
		                <li class="ftco-animate"><a href="https://www.instagram.com/yobyongs_utmkl/"><span class="icon-instagram"></span></a></li>
		              </ul>
	              </div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 ftco-animate">
						<div class="staff">
							<div class="img" style="background-image: url(images/OIP.webp);"></div>
							<div class="text pt-4">
								<h3>Muhammad Haris bin Abdullah</h3>
								<span class="position mb-2">Head Chef</span>
								<!-- <p>A small river named Duden flows by their place and supplies</p> -->
								<div class="faded">
									<!-- <p>I am an ambitious workaholic, but apart from that, pretty simple person.</p> -->
									<ul class="ftco-social d-flex">
		                <li class="ftco-animate"><a href="https://www.facebook.com/YobnYongsSemarak/"><span class="icon-facebook"></span></a></li>
		                <li class="ftco-animate"><a href="https://www.instagram.com/yobyongs_utmkl/"><span class="icon-instagram"></span></a></li>
		              </ul>
	              </div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 ftco-animate">
						<div class="staff">
							<div class="img" style="background-image: url('images/chef-9.jpg');"></div>
							<div class="text pt-4">
								<h3>Khairul Azman bin Ismail</h3>
								<span class="position mb-2">Chef</span>
								<!-- <p>A small river named Duden flows by their place and supplies</p> -->
								<div class="faded">
									<!-- <p>I am an ambitious workaholic, but apart from that, pretty simple person.</p> -->
									<ul class="ftco-social d-flex">
                    <li class="ftco-animate"><a href="https://www.facebook.com/YobnYongsSemarak/"><span class="icon-facebook"></span></a></li>
		                <li class="ftco-animate"><a href="https://www.instagram.com/yobyongs_utmkl/"><span class="icon-instagram"></span></a></li>
		              </ul>
	              </div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 ftco-animate">
						<div class="staff">
							<div class="img" style="background-image: url(images/Eric-Neo-top-malaysian-chef.jpg);"></div>
							<div class="text pt-4">
								<h3>Ahmad Faiz bin Zulkifli</h3>
								<span class="position mb-2">Chef</span>
								<!-- <p>A small river named Duden flows by their place and supplies</p> -->
								<div class="faded">
									<!-- <p>I am an ambitious workaholic, but apart from that, pretty simple person.</p> -->
									<ul class="ftco-social d-flex">
		                <li class="ftco-animate"><a href="https://www.facebook.com/YobnYongsSemarak/"><span class="icon-facebook"></span></a></li>
		                <li class="ftco-animate"><a href="https://www.instagram.com/yobyongs_utmkl/"><span class="icon-instagram"></span></a></li>
		              </ul>
	              </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

			<style>
    .review-container {
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        max-width: 700px;
        margin: 50px auto;
    }
    .review-input {
        border: 2px solid #f1f1f1;
        border-radius: 10px;
        padding: 15px;
        transition: 0.3s;
    }
    .review-input:focus {
        border-color: #c4a47c;
        box-shadow: none;
    }
    .btn-gold {
        background: #c4a47c;
        color: white;
        border-radius: 30px;
        padding: 12px 40px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-gold:hover {
        background: #a38965;
        transform: translateY(-2px);
    }

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
}
.star-rating input { display: none; }
.star-rating label {
    font-size: 35px;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s;
    margin: 0 2px;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #c4a47c;
}
</style>

<section class="ftco-section img" style="background-image: url(images/bg_4.jpg); background-size: cover; background-attachment: fixed;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 makereservation p-4 px-md-5 pb-md-5" style="background: rgba(255,255,255,0.95); border-radius: 15px; box-shadow: 0px 10px 30px rgba(0,0,0,0.1);">
                <div class="heading-section text-center mb-5">
                    <h2 class="mb-4" style="font-family: 'Great Vibes', cursive; color: #c4a47c; font-size: 50px; text-transform: none;">Share Your Experience</h2>
                    <p style="color: #666;">We'd love to hear from you!</p>
                      </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" style="color: #000; font-weight: bold;">Name</label>
                                <input type="text" id="revName" class="form-control" placeholder="Enter your name" 
                                       value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" style="color: #000; font-weight: bold;">Message</label>
                                <textarea id="revComment" class="form-control" rows="4" placeholder="How was the food?"></textarea>
                            </div>
                        </div>
                         <form action="#">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-group text-center">
                                <label style="color: #000; font-weight: bold; display: block; margin-bottom: 10px;">Your Rating</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group text-center">
                                <button type="button" onclick="submitReview()" class="btn btn-primary py-3 px-5" style="background: #c4a47c !important; border: none;">Post Review</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 text-center heading-section ftco-animate">
            <span class="subheading">Reviews</span>
            <h2 class="mb-4">What Our Guests Say</h2>
          </div>
        </div>
        <div class="row">
            <?php
// Fetch reviews
$result = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ratingCount = (int)$row['rating'];
        $starsHtml = "";
        for($i = 1; $i <= 5; $i++) {
            $starsHtml .= ($i <= $ratingCount) ? "<span style='color: #c4a47c;'>★</span>" : "<span style='color: #ccc;'>★</span>";
        }

        echo "
        <div class='col-md-4 mb-4 ftco-animate'>
            <div class='card border-0 shadow-sm p-4' style='border-radius: 15px;'>
                <div class='d-flex justify-content-between mb-2'>
                    <h6 class='font-weight-bold' style='color: #c4a47c; margin-bottom: 0;'>".htmlspecialchars($row['reviewer_name'])."</h6>
                    <small class='text-muted'>".date('M d', strtotime($row['created_at']))."</small>
                </div>
                
                <div class='mb-2' style='font-size: 18px;'>$starsHtml</div>
                
                <p class='text-secondary mt-2' style='font-style: italic;'>\"".htmlspecialchars($row['comment'])."\"</p>";

              
                // Inside the while loop, after the existing if (logged in && email match) block

// Always show delete-by-code link if code exists (for guests or if they lost session)
if (!empty($row['delete_code'])) {
    echo "
    <div class='text-right mt-3'>
        
        <a href='delete-review.php?code=" . htmlspecialchars($row['delete_code']) . "'
           onclick='return confirm(\"Delete this review?\");'
           style='color:#dc3545; font-size:13px;'>
           <i class='fas fa-trash-alt'></i> Delete
        </a>
    </div>";
}

        echo "
            </div>
        </div>";
    }
} else {
    echo "<div class='col-12 text-center'><p>No reviews yet. Be the first to share!</p></div>";
}
?>
        </div>
    </div>
</section>

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
        <div class="row">
          <div class="col-md-12 text-center">
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
function submitReview() {
    const name = document.getElementById('revName').value;
    const comment = document.getElementById('revComment').value;
    // This finds the checked star radio button
    const ratingInput = document.querySelector('input[name="rating"]:checked');
    const rating = ratingInput ? ratingInput.value : 5;

    const data = new FormData();
    data.append('name', name);
    data.append('comment', comment);
    data.append('rating', rating); // Send the stars!

    fetch('save-review.php', {
        method: 'POST',
        body: data
    })
    .then(res => res.text())
    .then(response => {
        if (response.startsWith("success")) {
            let msg = "Thank you for your review! It has been posted.";
            
            if (response.includes("|")) {
                const code = response.split("|")[1];
                msg += `\n\nYour delete code is: ${code}\nSave this code! You can use it later to delete your review.`;
            }
            
            alert(msg);
            location.reload();
        } else {
            alert("Oops: " + (response.startsWith("error:") ? response.substring(6).trim() : "Something went wrong"));
        }
    })
}
</script>
  </body>
</html>