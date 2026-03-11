<?php
session_start();
require_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yob Yong - My Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        .profile-card { border-radius: 15px; overflow: hidden; }
        .hero-wrap-2 { height: 300px; position: relative; background-size: cover; background-position: center center; }
        .overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); }
        
        .profile-pic-preview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #c4a47c;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
            background-color: #fff;
        }
        
        .upload-btn {
            position: absolute;
            bottom: 10px;
            right: 15px;
            background: #c4a47c;
            color: white;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            border: 3px solid #fff;
            cursor: pointer;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.3);
            display: none; 
            align-items: center;
            justify-content: center;
            padding: 0;
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
                        <div class="mb-4">
                            <a href="about.php" class="d-flex align-items-center" style="text-decoration: none; color: #c4a47c; font-weight: bold;">
                                <span style="font-size: 22px; margin-right: 8px;">&#8592;</span> 
                                <small>Back</small>
                            </a>
                        </div>
                        
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img id="profile-img" src="images/default-user.png" class="profile-pic-preview" alt="Profile">
                                <input type="file" id="upload-pic" accept="image/*" style="display: none;" onchange="uploadImageInstantly(event)">
                                <button type="button" id="change-pic-btn" class="upload-btn" onclick="document.getElementById('upload-pic').click();">
                                    <i class="bi bi-camera-fill text-white" style="font-size: 20px;"></i>
                                </button>
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
                                <input type="email" id="edit-email" class="form-control" readonly>
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
                                    <button onclick="saveProfile()" class="btn btn-success btn-block py-2">Save Changes</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        loadUserData();
    });

   function loadUserData() {
    const sessionData = localStorage.getItem('yobYongSession');
    if (!sessionData) {
        window.location.href = "login.php"; 
        return;
    }

    const user = JSON.parse(sessionData);
    
    document.getElementById('profile-name-display').innerText = user.name || 'Guest';
    document.getElementById('display-email').innerText = user.email || '-';
    document.getElementById('display-phone').innerText = user.phone || 'Not provided';
    window.userPass = user.password || '********';

    // Set default image first
    document.getElementById('profile-img').src = 'images/default-user.png';
    
    // Fetch picture from database
    fetch('get-profile-pic.php?email=' + encodeURIComponent(user.email))
        .then(res => res.json())
        .then(data => {
            if (data.profile_picture && data.profile_picture !== "") {
                // Check if file exists before setting
                document.getElementById('profile-img').src = data.profile_picture + "?t=" + new Date().getTime();
                // Update localStorage
                user.profilePic = data.profile_picture;
                localStorage.setItem('yobYongSession', JSON.stringify(user));
            }
        })
        .catch(err => {
            console.error("Error loading profile picture:", err);
        });

    // Populate edit form
    document.getElementById('edit-name').value = user.name || '';
    document.getElementById('edit-email').value = user.email || '';
    document.getElementById('edit-phone').value = user.phone || '';
}


    function toggleEditMode(isEditing) {
        document.getElementById('view-mode').style.display = isEditing ? 'none' : 'block';
        document.getElementById('edit-mode').style.display = isEditing ? 'block' : 'none';
        document.getElementById('change-pic-btn').style.display = isEditing ? 'flex' : 'none';
    }

    function uploadImageInstantly(event) {
        const file = event.target.files[0];
        if (!file) return;

        const sessionData = JSON.parse(localStorage.getItem('yobYongSession'));
        const formData = new FormData();
        formData.append('email', sessionData.email);
        formData.append('profile_pic', file);

        // Instant Preview
        const reader = new FileReader();
        reader.onload = e => document.getElementById('profile-img').src = e.target.result;
        reader.readAsDataURL(file);

        fetch('update-profile.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if (data.startsWith("success|")) {
                const parts = data.split("|");
                if (parts[2]) {
                    sessionData.profilePic = parts[2];
                    localStorage.setItem('yobYongSession', JSON.stringify(sessionData));
                    // Reload data to ensure everything is synced
                    loadUserData();
                }
            }
        });
    }

    function saveProfile() {
    const sessionData = JSON.parse(localStorage.getItem('yobYongSession'));
    const formData = new FormData();
    formData.append('email', sessionData.email);
    formData.append('name', document.getElementById('edit-name').value);
    formData.append('phone', document.getElementById('edit-phone').value);
    
    const pass = document.getElementById('edit-password').value;
    const confirmPass = document.getElementById('confirm-password').value;
    
    if (pass !== "") {
        if (pass !== confirmPass) {
            alert("Passwords don't match!");
            return;
        }
        formData.append('password', pass);
    }

    fetch('update-profile.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if (data.startsWith("success|")) {
                // Update localStorage with new data
                sessionData.name = document.getElementById('edit-name').value;
                sessionData.phone = document.getElementById('edit-phone').value;
                if (pass !== "") sessionData.password = pass;
                
                localStorage.setItem('yobYongSession', JSON.stringify(sessionData));
                
                alert("Profile updated successfully!");
                toggleEditMode(false);
                loadUserData(); // Reload to show updated data
            } else {
                alert("Error updating profile");
            }
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Failed to update profile");
        });
}


    function togglePasswordDisplay() {
        const passSpan = document.getElementById('profile-password');
        passSpan.innerText = passSpan.innerText === "********" ? window.userPass : "********";
        event.target.innerText = event.target.innerText === "Show" ? "Hide" : "Show";
    }

    function handleLogout() {
        if(confirm("Sign out?")) {
            localStorage.removeItem('yobYongSession');
            window.location.href = "login.php"; 
        }
    }
</script>
</body>
</html>