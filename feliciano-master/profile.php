<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yob Yong - My Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    

    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_3.jpg');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 text-center mb-4">
            <h1 class="mb-2 bread">My Profile</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card p-5 shadow-sm">
                        <div class="text-center mb-4">
                            <div style="width: 100px; height: 100px; background: #c4a47c; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white;">
                                <span class="icon-user" style="font-size: 50px;"></span>
                            </div>
                            <h3 id="profile-name" class="mt-3">Guest</h3>
                        </div>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Email:</strong> <span id="profile-email"></span></li>
                            <li class="list-group-item"><strong>Phone:</strong> <span id="profile-phone"></span></li>
                            <li class="list-group-item"><strong>Password:</strong> <span id="profile-password">********</span> 
                                <button class="btn btn-sm btn-link" onclick="togglePassword()">Show</button>
                            </li>
                        </ul>

                        <div class="text-center mt-4">
                            <a href="about.php" class="btn btn-primary px-4">Back to Home</a>
                            <button onclick="handleLogout()" class="btn btn-danger px-4">Sign Out</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sessionData = localStorage.getItem('yobYongSession');
            if (!sessionData) {
                alert("Please sign in first!");
                window.location.href = "sign in.php";
                return;
            }

            const user = JSON.parse(sessionData);
            document.getElementById('profile-name').innerText = user.name;
            document.getElementById('profile-email').innerText = user.email;
            document.getElementById('profile-phone').innerText = user.phone || 'Not provided';
            // Store password in a hidden variable for the toggle function
            window.userPass = user.password;
        });

        function togglePassword() {
            const passSpan = document.getElementById('profile-password');
            if (passSpan.innerText === "********") {
                passSpan.innerText = window.userPass;
            } else {
                passSpan.innerText = "********";
            }
        }

        function handleLogout() {
            localStorage.removeItem('yobYongSession');
            window.location.href = "index.php";
        }
    </script>
</body>
</html>