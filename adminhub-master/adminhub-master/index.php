<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="style.css">

	<title>AdminHubs</title>
</head>
<body>
	<script>
		if (localStorage.getItem('darkMode') === 'enabled') {
			document.body.classList.add('dark');
		}
	</script>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">Yobyong Admin</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="index.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="myStore.php">
					<i class='bx bxs-shopping-bag-alt'></i>
					<span class="text">My Store</span>
				</a>
			</li>
			<li>
				<a href="profile.php">
					<i class='bx bxs-user' ></i>
					<span class="text">Profile</span>
				</a>
			</li>
			<li>
				<a href="staffList.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Staff</span>
				</a>
			</li>
			<li>
				<a href="viewFeedback.php">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Feedback</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
			<a href="profile.php" class="profile">
				<img src="img/people.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<!-- Dashboard Header -->
			<div class="dashboard-header">
				<div class="dashboard-title">
					<h2>Sales Report</h2>
					<p class="dashboard-subtitle">Friday, December 16th 2023</p>
				</div>
			</div>

			<!-- Stats Grid - 4 Cards -->
			<div class="stats-grid">
				<!-- Total Sales Card (Purple) -->
				<div class="stat-card primary">
					<div class="stat-icon">
						<i class='bx bx-shopping-bag'></i>
					</div>
					<div class="stat-badge green">+22.00%</div>
					<div class="stat-label">Total Sales</div>
					<div class="stat-value">$612,917</div>
					<div class="stat-change">Products vs last month</div>
				</div>

				<!-- Total Orders Card -->
				<div class="stat-card">
					<div class="stat-icon">
						<i class='bx bx-cart'></i>
					</div>
					<div class="stat-badge green">+15.03%</div>
					<div class="stat-label">Total Orders</div>
					<div class="stat-value">34,760</div>
					<div class="stat-change">Orders vs last month</div>
				</div>

				<!-- Visitor Card -->
				<div class="stat-card">
					<div class="stat-icon">
						<i class='bx bx-user'></i>
					</div>
					<div class="stat-badge red">-2.00%</div>
					<div class="stat-label">Visitor</div>
					<div class="stat-value">14,987</div>
					<div class="stat-change">Users vs last month</div>
				</div>

				<!-- Total Sold Products Card -->
				<div class="stat-card">
					<div class="stat-icon">
						<i class='bx bx-package'></i>
					</div>
					<div class="stat-badge green">+5.06%</div>
					<div class="stat-label">Total Sold Products</div>
					<div class="stat-value">12,987</div>
					<div class="stat-change">Products vs last month</div>
				</div>
			</div>

			<!-- Dashboard Grid -->
			<div class="dashboard-grid">
				
				<!-- Left Column: Customer Habbits Chart -->
				<div class="dashboard-card">
					<div class="chart-header">
						<div>
							<h3 class="chart-title">Customer Habbits</h3>
							<p class="chart-subtitle">Track your customer habbits</p>
						</div>
						<select class="chart-filter">
							<option>This year</option>
							<option>This month</option>
							<option>This week</option>
						</select>
					</div>

					<div class="chart-legend">
						<div class="legend-item">
							<span class="legend-dot purple"></span>
							<span>Seen product</span>
						</div>
						<div class="legend-item">
							<span class="legend-dot gray"></span>
							<span>Sales</span>
						</div>
					</div>

					<div class="bar-chart">
						<!-- Floating Stats -->
						<div class="floating-stat top">
							<strong>43,787</strong>
							<span>Products</span>
						</div>
						<div class="floating-stat bottom">
							<strong>39,784</strong>
							<span>Products</span>
						</div>

						<!-- January -->
						<div class="bar-group">
							<div class="bars">
								<div class="bar purple" style="height: 75%;"></div>
								<div class="bar gray" style="height: 45%;"></div>
							</div>
							<span class="bar-label">Jan</span>
						</div>

						<!-- February -->
						<div class="bar-group">
							<div class="bars">
								<div class="bar purple" style="height: 55%;"></div>
								<div class="bar gray" style="height: 35%;"></div>
							</div>
							<span class="bar-label">Feb</span>
						</div>

						<!-- March -->
						<div class="bar-group">
							<div class="bars">
								<div class="bar purple" style="height: 45%;"></div>
								<div class="bar gray" style="height: 25%;"></div>
							</div>
							<span class="bar-label">Mar</span>
						</div>

						<!-- April -->
						<div class="bar-group">
							<div class="bars">
								<div class="bar purple" style="height: 85%;"></div>
								<div class="bar gray" style="height: 55%;"></div>
							</div>
							<span class="bar-label">Apr</span>
						</div>

						<!-- May -->
						<div class="bar-group">
							<div class="bars">
								<div class="bar purple" style="height: 50%;"></div>
								<div class="bar gray" style="height: 30%;"></div>
							</div>
							<span class="bar-label">May</span>
						</div>

						<!-- June -->
						<div class="bar-group">
							<div class="bars">
								<div class="bar purple" style="height: 65%;"></div>
								<div class="bar gray" style="height: 40%;"></div>
							</div>
							<span class="bar-label">Jun</span>
						</div>

						<!-- July -->
						<div class="bar-group">
							<div class="bars">
								<div class="bar purple" style="height: 70%;"></div>
								<div class="bar gray" style="height: 48%;"></div>
							</div>
							<span class="bar-label">Jul</span>
						</div>
					</div>
				</div>

				<!-- Right Column -->
				<div class="right-column">
					
					<!-- Product Statistics Card -->
					<div class="product-stats">
						<div class="product-header">
							<div>
								<h3 class="product-title">Product Statistic</h3>
								<p class="product-subtitle">Track your product sales</p>
							</div>
							<select class="chart-filter">
								<option>Today</option>
								<option>This week</option>
								<option>This month</option>
							</select>
						</div>

						<!-- Donut Chart -->
						<div class="donut-chart">
							<svg width="180" height="180" viewBox="0 0 180 180">
								<!-- Gray background circle -->
								<circle cx="90" cy="90" r="70" fill="none" stroke="#f0f0f0" stroke-width="20"/>
								<!-- Blue segment (Electronics) - 50% -->
								<circle cx="90" cy="90" r="70" fill="none" stroke="#667eea" stroke-width="20"
									stroke-dasharray="220 440" stroke-dashoffset="0" 
									transform="rotate(-90 90 90)"/>
								<!-- Red segment (Games) - 30% -->
								<circle cx="90" cy="90" r="70" fill="none" stroke="#f87171" stroke-width="20"
									stroke-dasharray="132 440" stroke-dashoffset="-220" 
									transform="rotate(-90 90 90)"/>
								<!-- Orange segment (Furniture) - 20% -->
								<circle cx="90" cy="90" r="70" fill="none" stroke="#fbbf24" stroke-width="20"
									stroke-dasharray="88 440" stroke-dashoffset="-352" 
									transform="rotate(-90 90 90)"/>
							</svg>
							<div class="donut-center">
								<div class="donut-value">9,829</div>
								<div class="donut-label">Products Sales</div>
								<div class="stat-badge green" style="margin-top: 8px;">+6.35%</div>
							</div>
						</div>

						<!-- Product List -->
						<div class="product-list">
							<div class="product-item">
								<div class="product-name">
									<span class="product-icon" style="background: #667eea;"></span>
									<span>Electronic</span>
								</div>
								<div class="product-value">2,487</div>
								<div class="stat-badge green" style="position: static; margin-left: 8px;">+13%</div>
							</div>
							<div class="product-item">
								<div class="product-name">
									<span class="product-icon" style="background: #f87171;"></span>
									<span>Games</span>
								</div>
								<div class="product-value">1,828</div>
								<div class="stat-badge green" style="position: static; margin-left: 8px;">+5.2%</div>
							</div>
							<div class="product-item">
								<div class="product-name">
									<span class="product-icon" style="background: #fbbf24;"></span>
									<span>Furniture</span>
								</div>
								<div class="product-value">1,463</div>
								<div class="stat-badge red" style="position: static; margin-left: 8px;">-1.08%</div>
							</div>
						</div>
					</div>

					<!-- Customer Growth Card -->
					<div class="product-stats">
						<div class="growth-header">
							<div>
								<h3 class="product-title">Customer Growth</h3>
								<p class="product-subtitle">Track customer by locations</p>
							</div>
							<select class="chart-filter">
								<option>Today</option>
								<option>This week</option>
								<option>This month</option>
							</select>
						</div>

						<!-- Bubble Chart -->
						<div class="bubble-chart">
							<div class="bubble large" style="left: 50%; top: 50%; transform: translate(-50%, -50%);">
								287
							</div>
							<div class="bubble medium" style="right: 10px; top: 20px;">
								2,417
							</div>
							<div class="bubble small" style="left: 10px; bottom: 20px;">
								2,261
							</div>
							<div class="bubble small" style="right: 30px; bottom: 30px;">
								812
							</div>
						</div>

						<!-- Country List -->
						<div class="country-list">
							<div class="country-item">
								<span class="country-flag">🇺🇸</span>
								<span class="country-name">United States</span>
							</div>
							<div class="country-item">
								<span class="country-flag">🇩🇪</span>
								<span class="country-name">Germany</span>
							</div>
							<div class="country-item">
								<span class="country-flag">🇦🇺</span>
								<span class="country-name">Australia</span>
							</div>
							<div class="country-item">
								<span class="country-flag">🇫🇷</span>
								<span class="country-name">France</span>
							</div>
						</div>
					</div>

				</div>

			</div>

		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="./script.js"></script>
</body>
</html>