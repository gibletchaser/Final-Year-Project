<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yob Yong - My Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .profile-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .hero-wrap-2 {
            height: 300px;
            position: relative;
            background-size: cover;
            background-position: center center;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
        }
        #profile-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #c4a47c;
        }
        .btn-edit-pic {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #c4a47c;
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            border: none;
            display: none;
        }
    </style>
</head>
<body>

    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_3.jpg');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center" style="height: 300px;">
          <div class="col-md-9 text-center mb-5">
            <h1 class="mb-2 bread text-white">My Profile</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card profile-card p-4 p-md-5 shadow">
                        
                        <div class="text-center mb-4">
                            <div style="position: relative; display: inline-block;">
                                <img id="profile-img" src="images/default-user.png" alt="Profile">
                                <input type="file" id="upload-pic" hidden accept="image/*" onchange="previewImage(event)">
                                <button id="edit-pic-btn" class="btn-edit-pic" onclick="document.getElementById('upload-pic').click()">✎</button>
                            </div>
                            <h2 id="profile-name-display" class="mt-3 font-weight-bold">Guest</h2>
                        </div>

                        <hr>

                        <div id="view-mode">
                            <div class="mb-3">
                                <label class="text-muted small mb-0">Email Address</label>
                                <div id="display-email" class="h6">-</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-0">Phone Number</label>
                                <div id="display-phone" class="h6">-</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-0">Password</label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span id="profile-password" class="h6 mb-0">********</span>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="togglePasswordDisplay()">Show</button>
                                </div>
                            </div>

                            <div class="text-center mt-5">
                                <button onclick="toggleEditMode(true)" class="btn btn-primary btn-block py-2" style="background: #c4a47c; border: none;">Edit Profile</button>
                                <button onclick="handleLogout()" class="btn btn-link text-danger mt-2">Sign Out</button>
                            </div>
                        </div>

                        <div id="edit-mode" style="display: none;">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" id="edit-name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" id="edit-email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" id="edit-phone" class="form-control">
                            </div>
                            
                            <div class="border p-3 rounded bg-light mb-3">
                                <p class="small text-muted mb-2">Change Password (Optional)</p>
                                <div class="form-group">
                                    <input type="password" id="edit-password" class="form-control mb-2" placeholder="New Password">
                                    <input type="password" id="confirm-password" class="form-control" placeholder="Confirm New Password">
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col-6 pr-1">
                                    <button onclick="saveProfile()" class="btn btn-success btn-block py-2">Save</button>
                                </div>
                                <div class="col-6 pl-1">
                                    <button onclick="toggleEditMode(false)" class="btn btn-secondary btn-block py-2">Cancel</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    // 1. Run this as soon as the page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadUserData();
    });

    function loadUserData() {
        const sessionData = localStorage.getItem('yobYongSession');
        if (!sessionData) {
            alert("Session expired. Please sign in again.");
            window.location.href = "sign in.php";
            return;
        }

        const user = JSON.parse(sessionData);
        
        // --- DISPLAY DATA (View Mode) ---
        document.getElementById('profile-name-display').innerText = user.name || 'Guest';
        document.getElementById('display-email').innerText = user.email || '-';
        document.getElementById('display-phone').innerText = user.phone || 'Not provided';
        
        // Store password globally for the 'Show' button
        window.userPass = user.password || '********'; 

        // Set Profile Picture if it exists
        if (user.profilePic) {
            document.getElementById('profile-img').src = user.profilePic;
        }

        // --- PRE-FILL EDIT FORM ---
        document.getElementById('edit-name').value = user.name || '';
        document.getElementById('edit-email').value = user.email || '';
        document.getElementById('edit-phone').value = user.phone || '';
        document.getElementById('edit-password').value = ""; 
        document.getElementById('confirm-password').value = "";
    }

    function toggleEditMode(isEditing) {
        document.getElementById('view-mode').style.display = isEditing ? 'none' : 'block';
        document.getElementById('edit-mode').style.display = isEditing ? 'block' : 'none';
        document.getElementById('edit-pic-btn').style.display = isEditing ? 'block' : 'none';
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('profile-img').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function saveProfile() {
        const sessionData = JSON.parse(localStorage.getItem('yobYongSession'));
        
        const newName = document.getElementById('edit-name').value.trim();
        const newEmail = document.getElementById('edit-email').value.trim();
        const newPhone = document.getElementById('edit-phone').value.trim();
        const newPass = document.getElementById('edit-password').value;
        const confirmPass = document.getElementById('confirm-password').value;

        if (!newName || !newEmail) {
            alert("Name and Email are required!");
            return;
        }

        if (newPass !== "") {
            if (newPass !== confirmPass) {
                alert("New passwords do not match!");
                return;
            }
            sessionData.password = newPass;
        }

        // Update local session object
        sessionData.name = newName;
        sessionData.email = newEmail;
        sessionData.phone = newPhone;
        sessionData.profilePic = document.getElementById('profile-img').src; 

        localStorage.setItem('yobYongSession', JSON.stringify(sessionData));
        
        alert("Profile updated successfully!");
        toggleEditMode(false);
        loadUserData();
    }

    function togglePasswordDisplay() {
        const passSpan = document.getElementById('profile-password');
        const btn = event.target;
        if (passSpan.innerText === "********") {
            passSpan.innerText = window.userPass;
            btn.innerText = "Hide";
        } else {
            passSpan.innerText = "********";
            btn.innerText = "Show";
        }
    }

    function handleLogout() {
        if(confirm("Are you sure you want to sign out?")) {
            localStorage.removeItem('yobYongSession');
            window.location.href = "index.php";
        }
    }
</script>
</body>
</html>