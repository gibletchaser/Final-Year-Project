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
	<style>
		/* MODERN DASHBOARD STYLES */
		
		/* Main Dashboard Grid */
		.dashboard-grid {
			display: grid;
			grid-template-columns: 2fr 1fr;
			gap: 24px;
			margin-top: 24px;
		}
		
		/* Card Base Style */
		.dashboard-card {
			background: #fff;
			border-radius: 20px;
			padding: 24px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.06);
		}
		
		/* Header Section */
		.dashboard-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 24px;
		}
		
		.dashboard-title h2 {
			font-size: 24px;
			font-weight: 700;
			color: #1a1a1a;
			margin: 0 0 4px 0;
		}
		
		.dashboard-subtitle {
			font-size: 14px;
			color: #999;
		}
		
		/* Stats Grid - Top 4 Cards */
		.stats-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 20px;
			margin-bottom: 24px;
		}
		
		.stat-card {
			background: #fff;
			border-radius: 16px;
			padding: 20px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.06);
			position: relative;
		}
		
		.stat-card.primary {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: #fff;
		}
		
		.stat-icon {
			width: 48px;
			height: 48px;
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 24px;
			margin-bottom: 16px;
		}
		
		.stat-card.primary .stat-icon {
			background: rgba(255,255,255,0.2);
			color: #fff;
		}
		
		.stat-card:not(.primary) .stat-icon {
			background: #f0f0f0;
			color: #667eea;
		}
		
		.stat-label {
			font-size: 13px;
			color: #666;
			margin-bottom: 8px;
		}
		
		.stat-card.primary .stat-label {
			color: rgba(255,255,255,0.8);
		}
		
		.stat-value {
			font-size: 28px;
			font-weight: 700;
			color: #1a1a1a;
			margin-bottom: 4px;
		}
		
		.stat-card.primary .stat-value {
			color: #fff;
		}
		
		.stat-change {
			font-size: 12px;
			color: #666;
		}
		
		.stat-card.primary .stat-change {
			color: rgba(255,255,255,0.9);
		}
		
		.stat-badge {
			display: inline-flex;
			align-items: center;
			gap: 4px;
			padding: 4px 8px;
			border-radius: 12px;
			font-size: 11px;
			font-weight: 600;
			position: absolute;
			top: 20px;
			right: 20px;
		}
		
		.stat-badge.green {
			background: #d4f4dd;
			color: #0ca750;
		}
		
		.stat-badge.red {
			background: #ffd4d4;
			color: #ff4444;
		}
		
		/* Chart Section */
		.chart-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}
		
		.chart-title {
			font-size: 18px;
			font-weight: 600;
			color: #1a1a1a;
		}
		
		.chart-subtitle {
			font-size: 13px;
			color: #999;
		}
		
		.chart-filter {
			padding: 8px 16px;
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			background: #fff;
			font-size: 13px;
			cursor: pointer;
			color: #666;
		}
		
		.chart-legend {
			display: flex;
			gap: 20px;
			margin-bottom: 16px;
		}
		
		.legend-item {
			display: flex;
			align-items: center;
			gap: 6px;
			font-size: 13px;
			color: #666;
		}
		
		.legend-dot {
			width: 10px;
			height: 10px;
			border-radius: 50%;
		}
		
		.legend-dot.purple {
			background: #667eea;
		}
		
		.legend-dot.gray {
			background: #d0d0d0;
		}
		
		/* Bar Chart */
		.bar-chart {
			display: flex;
			align-items: flex-end;
			justify-content: space-between;
			height: 280px;
			gap: 12px;
			position: relative;
		}
		
		.bar-group {
			flex: 1;
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 8px;
		}
		
		.bars {
			width: 100%;
			display: flex;
			gap: 4px;
			align-items: flex-end;
			height: 240px;
		}
		
		.bar {
			flex: 1;
			border-radius: 8px 8px 0 0;
			transition: all 0.3s;
			cursor: pointer;
		}
		
		.bar.purple {
			background: #667eea;
		}
		
		.bar.gray {
			background: #e8e8e8;
		}
		
		.bar:hover {
			opacity: 0.8;
		}
		
		.bar-label {
			font-size: 12px;
			color: #999;
			font-weight: 500;
		}
		
		/* Floating Stats */
		.floating-stat {
			position: absolute;
			background: #1a1a1a;
			color: #fff;
			padding: 12px 16px;
			border-radius: 12px;
			font-size: 13px;
			display: flex;
			flex-direction: column;
			gap: 4px;
		}
		
		.floating-stat.top {
			top: 40px;
			right: 20px;
		}
		
		.floating-stat.bottom {
			bottom: 60px;
			left: 20px;
		}
		
		.floating-stat strong {
			font-size: 18px;
			font-weight: 700;
		}
		
		/* Right Column */
		.right-column {
			display: flex;
			flex-direction: column;
			gap: 24px;
		}
		
		/* Product Statistics Card */
		.product-stats {
			background: #fff;
			border-radius: 20px;
			padding: 24px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.06);
		}
		
		.product-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}
		
		.product-title {
			font-size: 18px;
			font-weight: 600;
			color: #1a1a1a;
		}
		
		.product-subtitle {
			font-size: 13px;
			color: #999;
		}
		
		/* Donut Chart */
		.donut-chart {
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 24px;
			position: relative;
		}
		
		.donut-center {
			position: absolute;
			text-align: center;
		}
		
		.donut-value {
			font-size: 32px;
			font-weight: 700;
			color: #1a1a1a;
		}
		
		.donut-label {
			font-size: 12px;
			color: #999;
		}
		
		/* Product List */
		.product-list {
			display: flex;
			flex-direction: column;
			gap: 12px;
		}
		
		.product-item {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 8px 0;
		}
		
		.product-name {
			display: flex;
			align-items: center;
			gap: 8px;
			font-size: 14px;
			color: #1a1a1a;
		}
		
		.product-icon {
			width: 8px;
			height: 8px;
			border-radius: 50%;
		}
		
		.product-value {
			font-size: 15px;
			font-weight: 600;
			color: #1a1a1a;
		}
		
		/* Customer Growth Card */
		.growth-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}
		
		/* Bubble Chart */
		.bubble-chart {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 200px;
			position: relative;
			margin-bottom: 20px;
		}
		
		.bubble {
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 18px;
			font-weight: 700;
			color: #fff;
			position: absolute;
		}
		
		.bubble.large {
			width: 140px;
			height: 140px;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			font-size: 24px;
		}
		
		.bubble.medium {
			width: 100px;
			height: 100px;
			background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
		}
		
		.bubble.small {
			width: 80px;
			height: 80px;
			background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
			font-size: 16px;
		}
		
		/* Country List */
		.country-list {
			display: flex;
			flex-direction: column;
			gap: 12px;
		}
		
		.country-item {
			display: flex;
			align-items: center;
			gap: 10px;
		}
		
		.country-flag {
			width: 24px;
			height: 24px;
			border-radius: 50%;
			font-size: 14px;
		}
		
		.country-name {
			flex: 1;
			font-size: 14px;
			color: #1a1a1a;
		}
		
		/* Responsive */
		@media (max-width: 1200px) {
			.dashboard-grid {
				grid-template-columns: 1fr;
			}
			
			.stats-grid {
				grid-template-columns: repeat(2, 1fr);
			}
		}
		
		@media (max-width: 768px) {
			.stats-grid {
				grid-template-columns: 1fr;
			}
		}
	</style>
</head>
<body>


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