<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Store</title>
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- SIDEBAR (SAME AS DASHBOARD) -->
<section id="sidebar">
  <a href="#" class="brand">
    <i class='bx bxs-smile'></i>
    <span class="text">AdminHub</span>
  </a>
  <ul class="side-menu top">
    <li>
      <a href="index.php">
        <i class='bx bxs-dashboard'></i>
        <span class="text">Dashboard</span>
      </a>
    </li>
    <li class="active">
      <a href="#">
        <i class='bx bxs-shopping-bag-alt'></i>
        <span class="text">My Store</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class='bx bxs-doughnut-chart'></i>
        <span class="text">Analytics</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class='bx bxs-message-dots'></i>
        <span class="text">Message</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class='bx bxs-group'></i>
        <span class="text">Team</span>
      </a>
    </li>
  </ul>
  <ul class="side-menu">
    <li>
      <a href="#">
        <i class='bx bxs-cog'></i>
        <span class="text">Settings</span>
      </a>
    </li>
    <li>
      <a href="#" class="logout">
        <i class='bx bxs-log-out-circle'></i>
        <span class="text">Logout</span>
      </a>
    </li>
  </ul>
</section>

<!-- CONTENT -->
<section id="content">
  <!-- NAVBAR (SAME AS DASHBOARD) -->
  <nav>
    <i class='bx bx-menu'></i>
    <a href="#" class="nav-link">Categories</a>
    <form>
      <div class="form-input">
        <input type="search" placeholder="Search...">
        <button class="search-btn"><i class='bx bx-search'></i></button>
      </div>
    </form>
  </nav>

  <!-- MAIN (THIS IS NEW) -->
  <main>
    <div class="head-title">
      <div class="left">
        <h1>My Store</h1>
        <ul class="breadcrumb">
          <li><a href="#">My Store</a></li>
          <li><i class='bx bx-chevron-right'></i></li>
          <li><a class="active" href="#">Menu</a></li>
        </ul>
      </div>
      <a href="#" class="btn-download" onclick="openModal('add')">
        <i class='bx bx-plus'></i>
        <span class="text">Add Menu</span>
      </a>
    </div>

    <!-- MENU GRID -->
    <div id="menuGrid" class="menu-grid">
      <!-- Menus will be loaded here dynamically by JavaScript -->
    </div>

    <!-- Modal for Add/Edit/Delete -->
    <div id="menuModal" style="display:none">
      <div class="modal-content">  <!-- Added minimal wrapper for better modal structure -->
        <h3 id="modalTitle">Add Menu</h3>
        <input type="hidden" id="menuId">
        <input type="text" id="menuName" placeholder="Menu Name">
        <input type="number" id="menuPrice" placeholder="Price">
        <button onclick="saveMenu()">Save</button>
        <button onclick="closeModal()">Cancel</button>
        <button id="deleteBtn" onclick="deleteMenu()" style="display:none; background:red; color:white;">Delete</button>
      </div>
    </div>
  </main>
</section>

<!-- Added jQuery before script.js to ensure proper loading and AJAX support -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="./script.js"></script>

</body>
</html>