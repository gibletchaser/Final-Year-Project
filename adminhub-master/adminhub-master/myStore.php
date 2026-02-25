<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

// Fetch all categories for the filter tabs
$catResult = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$categories = [];
while ($row = $catResult->fetch_assoc()) {
    $categories[] = $row;
}
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

<!-- SIDEBAR -->
<section id="sidebar">
  <a href="#" class="brand">
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
    <li class="active">
      <a href="#">
        <i class='bx bxs-shopping-bag-alt'></i>
        <span class="text">My Store</span>
      </a>
    </li>
    <li>
      <a href="profile.php">
        <i class='bx bxs-user'></i>
        <span class="text">Profile</span>
      </a>
    </li>
    <li>
      <a href="staffList.php">
        <i class='bx bxs-group'></i>
        <span class="text">Staff</span>
      </a>
    </li>
    <li>
      <a href="viewFeedback.php">
        <i class='bx bxs-message-dots'></i>
        <span class="text">Feedback</span>
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
  </ul>
</section>

<!-- CONTENT -->
<section id="content">
  <!-- NAVBAR -->
  <nav>
    <i class='bx bx-menu'></i>
    <a href="#" class="nav-link">Categories</a>
    <form>
      <div class="form-input">
        <input type="search" id="searchInput" placeholder="Search...">
        <button class="search-btn" type="button"><i class='bx bx-search'></i></button>
      </div>
    </form>
    <input type="checkbox" id="switch-mode" hidden>
    <label for="switch-mode" class="switch-mode"></label>
    <a href="#" class="notification">
      <i class='bx bxs-bell'></i>
      <span class="num">8</span>
    </a>
    <a href="profile.php" class="profile">
      <img src="img/people.png">
    </a>
  </nav>

  <!-- MAIN -->
  <main>
    <div class="head-title">
      <div class="left">
        <h1>My Store</h1>
        <ul class="breadcrumb">
          <li><a href="#">My Stores</a></li>
          <li><i class='bx bx-chevron-right'></i></li>
          <li><a class="active" href="#">Menu</a></li>
        </ul>
      </div>
      <a href="#" class="btn-download" onclick="openModal('add')">
        <i class='bx bx-plus'></i>
        <span class="text">Add Menu</span>
      </a>
    </div>

    <!-- ── CATEGORY FILTER TABS ── -->
    <div class="category-bar">
      <button class="cat-tab active" data-cat="all" onclick="filterCategory('all', this)">
        All
      </button>
      <?php foreach ($categories as $cat): ?>
      <button
        class="cat-tab"
        data-cat="<?= htmlspecialchars($cat['id']) ?>"
        onclick="filterCategory('<?= htmlspecialchars($cat['id']) ?>', this)">
        <?= htmlspecialchars($cat['name']) ?>
      </button>
      <?php endforeach; ?>
      <button class="cat-tab manage-btn" onclick="openCatModal()">
        <i class='bx bx-category'></i> Manage Categories
      </button>
    </div>

    <!-- MENU GRID -->
    <div id="menuGrid" class="menu-grid">
      <!-- Menus loaded dynamically by JavaScript -->
    </div>

    <!-- ═══════════════════════════════════════════
         ADD / EDIT MENU MODAL
    ════════════════════════════════════════════ -->
    <div id="menuModal" class="modal-overlay">
      <div class="modal-box">
        <h3 id="modalTitle">Edit Menu</h3>

        <input type="hidden" id="menuId">

        <label>Menu Name</label>
        <input type="text" id="menuName">

        <label>Category</label>
        <select id="menuCategory">
          <option value="">— No Category —</option>
          <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat['id']) ?>">
            <?= htmlspecialchars($cat['name']) ?>
          </option>
          <?php endforeach; ?>
        </select>

        <label>Price (RM)</label>
        <input type="number" id="menuPrice" step="0.01">

        <label>Menu Image</label>
        <input type="file" id="menuImage" accept="image/*">

        <div class="modal-actions">
          <button type="button" class="btn-save" onclick="saveMenu()">Save</button>
          <button class="btn-cancel" onclick="closeModal()">Cancel</button>
          <button id="deleteBtn"
                  onclick="deleteMenu()"
                  style="display:none; background:red; color:white;">
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════
         CATEGORY MANAGEMENT MODAL
    ════════════════════════════════════════════ -->
    <div id="catModal" class="modal-overlay">
      <div class="modal-box">
        <h3>Manage Categories</h3>

        <div id="catList">
          <!-- Category list rendered by JS -->
        </div>

        <div class="add-cat-row">
          <input type="text" id="newCatName" placeholder="New category name...">
          <button onclick="addCategory()">
            <i class='bx bx-plus'></i> Add
          </button>
        </div>

        <div class="modal-actions" style="margin-top:18px;">
          <button class="btn-cancel" onclick="closeCatModal()">Close</button>
        </div>
      </div>
    </div>

  </main>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="./script.js"></script>

</body>
</html>