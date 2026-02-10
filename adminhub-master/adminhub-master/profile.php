<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
$username = $_SESSION['username'] ?? '';

// HANDLE PROFILE UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'];

    // Default: no image update
    $newImageName = $user['profile_image'];

    if (!empty($_FILES['profile_pic']['name'])) {

        $file = $_FILES['profile_pic'];
        $allowedTypes = ['jpg', 'jpeg', 'png'];

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowedTypes)) {

            $newImageName = uniqid('profile_', true) . '.' . $ext;
            $destination = 'uploads/' . $newImageName;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                die("Failed to upload image.");
            }

        } else {
            die("Only JPG, JPEG, and PNG files are allowed.");
        }
    }

    $stmt = $conn->prepare(
        "UPDATE users SET username = ?, profile_image = ? WHERE username = ?"
    );
    $stmt->bind_param("sss", $newUsername, $newImageName, $username);
    $stmt->execute();

    $_SESSION['username'] = $newUsername;

    header("Location: profile.php");
    exit;
}


// FETCH USER DATA
$stmt = $conn->prepare(
    "SELECT username, full_name, email, role, created_at, profile_image 
     FROM users WHERE username = ?"
);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Profile | AdminHub</title>
    <style>
        /* CRITICAL INLINE STYLES FOR PROFILE PAGE */
        
        /* Main profile wrapper */
        #content main .profile-wrapper {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* Two column grid */
        .profile-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-top: 24px;
        }
        
        /* Card base styles */
        .profile-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .profile-card h3 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        
        /* Profile header with photo */
        .profile-main {
            display: flex;
            gap: 25px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 25px;
        }
        
        /* Profile photo */
        .profile-photo-box {
            position: relative;
            flex-shrink: 0;
        }
        
        .profile-photo-box img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3C91E6;
        }
        
        .photo-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 36px;
            height: 36px;
            background: #3C91E6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid #fff;
            color: #fff;
            font-size: 18px;
        }
        
        .photo-upload-btn:hover {
            background: #2b7bc4;
        }
        
        .photo-upload-btn input {
            display: none;
        }
        
        /* Profile info */
        .profile-info {
            flex: 1;
        }
        
        .profile-info h2 {
            font-size: 28px;
            color: #333;
            margin: 0 0 5px 0;
        }
        
        .profile-info .username {
            color: #999;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .info-row strong {
            min-width: 130px;
            color: #555;
            font-weight: 600;
        }
        
        .info-row span {
            color: #666;
        }
        
        /* Social icons */
        .social-section {
            margin-top: 25px;
        }
        
        .social-section h4 {
            font-size: 16px;
            color: #333;
            margin-bottom: 12px;
        }
        
        .social-icons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .social-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            transition: transform 0.2s;
            border: none;
        }
        
        .social-icon:hover {
            transform: translateY(-2px);
        }
        
        .social-icon.email { background: #7C5DA8; }
        .social-icon.facebook { background: #3B5998; }
        .social-icon.telegram { background: #0088cc; }
        .social-icon.whatsapp { background: #25D366; }
        .social-icon.instagram { background: #E4405F; }
        .social-icon.twitter { background: #1DA1F2; }
        
        /* Edit form */
        .edit-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }
        
        .edit-section h4 {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
        }
        
        .form-field {
            margin-bottom: 15px;
        }
        
        .form-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }
        
        .form-field input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .form-field input:focus {
            outline: none;
            border-color: #3C91E6;
        }
        
        .btn-save {
            background: #3C91E6;
            color: #fff;
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-save:hover {
            background: #2b7bc4;
        }
        
        /* Stats card */
        .stats-grid {
            display: grid;
            gap: 15px;
        }
        
        .stat-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .stat-box:last-child {
            border-bottom: none;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #3C91E6;
        }
        
        /* Activity card */
        .activity-card {
            background: linear-gradient(135deg, #3C91E6 0%, #2b7bc4 100%);
            color: #fff;
        }
        
        .activity-card h3 {
            color: #fff;
        }
        
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .activity-list li {
            padding-left: 22px;
            margin-bottom: 10px;
            position: relative;
            font-size: 14px;
        }
        
        .activity-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #90caf9;
            font-weight: bold;
        }
        
        /* Quick actions */
        .action-btn {
            width: 100%;
            padding: 12px 16px;
            background: #f5f5f5;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background: #3C91E6;
            color: #fff;
            transform: translateX(3px);
        }
        
        .action-btn i {
            font-size: 18px;
        }
        
        /* Edit toggle button */
        .btn-edit {
            background: #fff;
            border: 2px solid #e0e0e0;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #3C91E6;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-edit:hover {
            background: #f5f5f5;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .profile-main {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">Yobyong Admin</span>
    </a>
    <ul class="side-menu top">
        <li>
            <a href="index.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="myStore.php">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">My Store</span>
            </a>
        </li>
        <li class="active">
            <a href="profile.php">
                <i class='bx bxs-user'></i>
                <span class="text">Profile</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
    </ul>
</section>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <form>
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <a href="#" class="notification">
            <i class='bx bxs-bell'></i>
            <span class="num">8</span>
        </a>
        <a href="profile.php" class="profile">
            <img src="uploads/<?= htmlspecialchars($user['profile_image'] ?? 'people.png') ?>">
        </a>
    </nav>
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>My Profile</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="profile-wrapper">
            <div class="profile-grid">
                
                <!-- LEFT COLUMN: MAIN PROFILE -->
                <div>
                    <div class="profile-card">
                        <h3>Profile Information</h3>
                        
                        <form method="POST" enctype="multipart/form-data" id="profileForm">
                            <!-- Profile Header -->
                            <div class="profile-main">
                                <!-- Photo -->
                                <div class="profile-photo-box">
                                    <img src="uploads/<?= htmlspecialchars($user['profile_image'] ?? 'people.png') ?>" 
                                         alt="Profile" 
                                         id="photoPreview">
                                    
                                    <label class="photo-upload-btn">
                                        <i class='bx bx-camera'></i>
                                        <input type="file" 
                                               name="profile_pic" 
                                               accept="image/*"
                                               onchange="previewAndSubmit(event)">
                                    </label>
                                </div>
                                
                                <!-- Info -->
                                <div class="profile-info">
                                    <h2><?= htmlspecialchars($user['full_name']) ?></h2>
                                    <div class="username">@<?= htmlspecialchars($user['username']) ?></div>
                                    
                                    <div class="info-row">
                                        <strong>Email:</strong>
                                        <span><?= htmlspecialchars($user['email']) ?></span>
                                    </div>
                                    
                                    <div class="info-row">
                                        <strong>Role:</strong>
                                        <span><?= htmlspecialchars(ucfirst($user['role'])) ?></span>
                                    </div>
                                    
                                    <div class="info-row">
                                        <strong>Member Since:</strong>
                                        <span><?= date('F d, Y', strtotime($user['created_at'])) ?></span>
                                    </div>
                                    
                                    <div style="margin-top: 15px;">
                                        <button type="button" class="btn-edit" onclick="toggleEdit()">
                                            <i class='bx bx-edit'></i>
                                            <span>Edit Profile</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Social Icons -->
                            <div class="social-section">
                                <h4>Connect With Me</h4>
                                <div class="social-icons">
                                    <button type="button" class="social-icon email">
                                        <i class='bx bx-envelope'></i>
                                    </button>
                                    <button type="button" class="social-icon facebook">
                                        <i class='bx bxl-facebook'></i>
                                    </button>
                                    <button type="button" class="social-icon telegram">
                                        <i class='bx bxl-telegram'></i>
                                    </button>
                                    <button type="button" class="social-icon whatsapp">
                                        <i class='bx bxl-whatsapp'></i>
                                    </button>
                                    <button type="button" class="social-icon instagram">
                                        <i class='bx bxl-instagram'></i>
                                    </button>
                                    <button type="button" class="social-icon twitter">
                                        <i class='bx bxl-twitter'></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Edit Form (Hidden by default) -->
                            <div class="edit-section" id="editSection" style="display: none;">
                                <h4>Edit Information</h4>
                                
                                <div class="form-field">
                                    <label>Username</label>
                                    <input type="text" 
                                           name="username" 
                                           value="<?= htmlspecialchars($user['username']) ?>" 
                                           required>
                                </div>
                                
                                <button type="submit" class="btn-save">
                                    <i class='bx bx-save'></i>
                                    <span>Save Changes</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- RIGHT COLUMN: STATS & ACTIONS -->
                <div>
                    
                    <!-- Account Stats -->
                    <div class="profile-card">
                        <h3>Account Stats</h3>
                        <div class="stats-grid">
                            <div class="stat-box">
                                <span class="stat-label">Total Orders</span>
                                <span class="stat-value">42</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-label">Products Listed</span>
                                <span class="stat-value">18</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-label">Revenue</span>
                                <span class="stat-value">$2,845</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="profile-card activity-card" style="margin-top: 20px;">
                        <h3>Recent Activity</h3>
                        <ul class="activity-list">
                            <li>Updated profile picture</li>
                            <li>Added new product to store</li>
                            <li>Completed 5 orders</li>
                            <li>Changed account settings</li>
                        </ul>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="profile-card" style="margin-top: 20px;">
                        <h3>Quick Actions</h3>
                        
                        <button class="action-btn">
                            <i class='bx bx-lock'></i>
                            <span>Change Password</span>
                        </button>
                        
                        <button class="action-btn">
                            <i class='bx bx-bell'></i>
                            <span>Notification Settings</span>
                        </button>
                        
                        <button class="action-btn">
                            <i class='bx bx-download'></i>
                            <span>Download Data</span>
                        </button>
                        
                        <button class="action-btn" onclick="if(confirm('Are you sure?')) window.location.href='logout.php'">
                            <i class='bx bx-log-out'></i>
                            <span>Logout</span>
                        </button>
                    </div>
                    
                </div>
                
            </div>
        </div>

    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->

<script src="script.js"></script>
<script>
    // Toggle edit section
    function toggleEdit() {
        const editSection = document.getElementById('editSection');
        if (editSection.style.display === 'none') {
            editSection.style.display = 'block';
        } else {
            editSection.style.display = 'none';
        }
    }
    
    // Preview and auto-submit photo
    function previewAndSubmit(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
            
            // Auto submit
            document.getElementById('profileForm').submit();
        }
    }
</script>
</body>
</html>