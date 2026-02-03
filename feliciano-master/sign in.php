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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

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
                <h2 class="mb-4">Sign In</h2>
              </div>

              <div id="login-error-msg" class="alert alert-danger" style="display: none; margin-bottom: 20px;"></div>

              <form action="#">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Email</label>
                        <input type="text" id="userEmail" class="form-control" placeholder="Your Email">
                    </div>
                  </div>
                  <div class="col-md-6 ">
                    <div class="form-group">
                      <label for="">Password</label>
                        <input type="password" id="userPassword" class="form-control" placeholder="Password">
                    </div>
                  </div>
                   <div class="col-md-12 mt-3">
                  <p class="mt-3">
        <a href="forgot-password.php" style="color: #c4a47c; text-decoration: underline;">Forgot Password?</a>
                   </p>
                    <div class="form-group">
                      <input type="button" onclick="loginUser()" value="Sign In" class="btn btn-primary py-3 px-5">
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
  <script src="js/main.js"></script>


<script>
function loginUser() {
    const email = document.getElementById('userEmail').value.trim();
    const password = document.getElementById('userPassword').value;
    const errorBox = document.getElementById('login-error-msg');

    errorBox.style.display = "none";

    if (!email || !password) {
        errorBox.innerText = "Please enter your Email and Password to sign in.";
        errorBox.style.display = "block";
        return;
    }

    const data = new FormData();
    data.append("email", email);
    data.append("password", password);

    fetch("login.php", {
        method: "POST",
        body: data
    })
    .then(res => {
        // If the server returns a 404 or 500, it will throw an error here
        if (!res.ok) throw new Error("Server error");
        return res.json();
    })
    .then(data => {
        if (data.status === "success") {
            localStorage.setItem('yobYongSession', JSON.stringify(data.user));
            window.location.href = "profile.php"; 
        } else {
            // This displays the EXACT message sent from login.php 
            // (e.g., "Invalid Email or Password")
            errorBox.innerText = data.message;
            errorBox.style.display = "block";

            // ONLY redirect to verify if the message specifically mentions verification
            if (data.message && data.message.toLowerCase().includes("verify")) {
                setTimeout(() => {
                    window.location.href = "verify-email.php";
                }, 3000);
            }
        }
    })
    .catch(err => {
        // This only runs if the internet dies or the PHP file crashes
        console.error(err);
        errorBox.innerText = "Connection failed. Please ensure login.php exists and try again.";
        errorBox.style.display = "block";
    });
}
</script>

<script>
function handleLogout() {
    if(confirm("Are you sure you want to sign out?")) {
        localStorage.removeItem('yobYongSession');
        window.location.href = 'index.php'; 
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const userSession = localStorage.getItem('yobYongSession');
    const authArea = document.getElementById('auth-area');

    if (userSession && authArea) {
        const user = JSON.parse(userSession);
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
</script>

</body>
</html>