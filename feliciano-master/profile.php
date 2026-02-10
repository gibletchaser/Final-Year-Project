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
            /* Increased size from 130px to 200px */
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #c4a47c;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
            background-color: #fff;
        }
        
        .btn-edit-pic {
            position: absolute;
            bottom: 10px; /* Adjusted position for the larger circle */
            right: 15px;
            background: #c4a47c;
            color: white;
            border-radius: 50%;
            width: 45px; /* Made the edit button a bit larger too */
            height: 45px;
            border: 3px solid #fff;
            display: none;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.3);
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
    if (!sessionData || !sessionData.email) {
        alert("Session error. Please sign in again.");
        return;
    }

    const originalName  = sessionData.name  || '';
    const originalPhone = sessionData.phone || '';

    const newName  = document.getElementById('edit-name').value.trim();
    const newEmail = document.getElementById('edit-email').value.trim(); // currently not changeable
    const newPhone = document.getElementById('edit-phone').value.trim();
    const newPass  = document.getElementById('edit-password').value;
    const confirmPass = document.getElementById('confirm-password').value;

    // Password match check
    if (newPass !== "" && newPass !== confirmPass) {
        alert("New passwords do not match!");
        return;
    }

    // Optional: you can add min length check etc. here
    // if (newPass !== "" && newPass.length < 6) { alert("Password too short"); return; }

    const formData = new FormData();
    formData.append('email', sessionData.email);
    formData.append('name', newName);
    formData.append('phone', newPhone);

    const fileInput = document.getElementById('upload-pic');
    if (fileInput.files.length > 0) {
        formData.append('profile_pic', fileInput.files[0]);
    }

    if (newPass !== "") {
        formData.append('password', newPass);
    }

    fetch('update-profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        data = data.trim();

        if (data.startsWith("success|")) {
             const parts = data.split("|");
             const changedFields = parts[1].split(",");
             
             // If PHP returned a new image path, save it to the session
             if (parts[2]) {
                 sessionData.profilePic = parts[2];
                }
}
    
        if (data.startsWith("success|")) {
            const changedFields = data.split("|")[1].split(",");

            // Build friendly message
            let messages = [];
            if (changedFields.includes("name")) {
                messages.push("Username");
            }
            if (changedFields.includes("phone")) {
                messages.push("Phone number");
            }
            if (changedFields.includes("password")) {
                messages.push("Password");
            }

            let msg = messages.join(", ") + " updated successfully!";
            if (messages.length === 0) {
                msg = "No changes were made.";
            } else if (messages.length === 1) {
                msg = messages[0] + " updated successfully!";
            } else if (messages.length === 2) {
                msg = messages.join(" and ") + " updated successfully!";
            } else {
                // 3+ items → last one with "and"
                let last = messages.pop();
                msg = messages.join(", ") + " and " + last + " updated successfully!";
            }

            alert(msg);

            // Update localStorage only with changed values
            if (changedFields.includes("name"))  sessionData.name  = newName;
            if (changedFields.includes("phone")) sessionData.phone = newPhone;
            if (changedFields.includes("password")) sessionData.password = newPass;

            localStorage.setItem('yobYongSession', JSON.stringify(sessionData));

            // Clear password fields
            document.getElementById('edit-password').value = '';
            document.getElementById('confirm-password').value = '';

            toggleEditMode(false);
            loadUserData();
        }
        else if (data === "no_changes") {
            alert("No changes detected.");
        }
        else if (data.startsWith("error|")) {
            alert("Update failed: " + data.substring(6));
        }
        else {
            alert("Unexpected response: " + data);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert("System error occurred. Please try again.");
    });
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