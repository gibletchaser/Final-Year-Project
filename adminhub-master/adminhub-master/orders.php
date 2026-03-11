<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <title>Orders | AdminHub</title>
  <style>

    /* ── TABLE PAGE ─────────────────────────────── */
    .orders-wrap {
      margin-top: 24px;
    }

    .orders-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    body.dark .orders-card {
      background: #0C0C1E;
    }

    .orders-table {
      width: 100%;
      border-collapse: collapse;
      font-family: 'Poppins', sans-serif;
    }

    .orders-table thead tr {
      background: #f5f6fa;
    }

    body.dark .orders-table thead tr {
      background: #060714;
    }

    .orders-table thead th {
      padding: 14px 20px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #aaa;
      text-align: left;
      white-space: nowrap;
    }

    .orders-table thead th:last-child {
      text-align: right;
      padding-right: 28px;
    }

    .orders-table tbody tr {
      border-top: 1px solid #f0f0f0;
      transition: background 0.15s;
    }

    body.dark .orders-table tbody tr {
      border-top: 1px solid #1a1a2e;
    }

    .orders-table tbody tr:hover {
      background: #f9f9f9;
    }

    body.dark .orders-table tbody tr:hover {
      background: #111128;
    }

    .orders-table tbody td {
      padding: 16px 20px;
      font-size: 14px;
      color: #342E37;
      vertical-align: middle;
    }

    body.dark .orders-table tbody td {
      color: #fbfbfb;
    }

    .orders-table tbody td:last-child {
      text-align: right;
      padding-right: 28px;
    }

    .order-code {
      font-weight: 700;
      color: #3C91E6;
      font-size: 13px;
      letter-spacing: 0.3px;
    }

    .order-customer {
      font-weight: 600;
    }

    .order-total {
      font-weight: 700;
      font-size: 15px;
    }

    .order-date {
      color: #aaa;
      font-size: 13px;
    }

    .orders-empty {
      text-align: center;
      padding: 60px;
      color: #aaa;
      font-size: 14px;
    }

    /* ── STATUS BADGES ──────────────────────────── */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
      white-space: nowrap;
    }

    .badge::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .badge-pending    { background: #fff8e1; color: #f59e0b; }
    .badge-pending::before    { background: #f59e0b; }
    .badge-processing { background: #eff6ff; color: #3b82f6; }
    .badge-processing::before { background: #3b82f6; }
    .badge-ready      { background: #f0fdf4; color: #22c55e; }
    .badge-ready::before      { background: #22c55e; }
    .badge-completed  { background: #ecfdf5; color: #10b981; }
    .badge-completed::before  { background: #10b981; }
    .badge-cancelled  { background: #fff1f2; color: #ef4444; }
    .badge-cancelled::before  { background: #ef4444; }

    /* ── VIEW BUTTON ────────────────────────────── */
    .btn-view {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 18px;
      background: #eff6ff;
      color: #3C91E6;
      border: none;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      font-family: 'Poppins', sans-serif;
    }

    .btn-view:hover {
      background: #3C91E6;
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(60,145,230,0.3);
    }

    /* ── MODAL OVERLAY ──────────────────────────── */
    #viewModal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.55);
      backdrop-filter: blur(3px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      padding: 20px;
    }

    #viewModal.open {
      display: flex;
    }

    /* ── MODAL BOX ──────────────────────────────── */
    .modal-box {
      background: #fff;
      border-radius: 24px;
      width: 560px;
      max-width: 100%;
      max-height: 88vh;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      box-shadow: 0 24px 60px rgba(0,0,0,0.2);
      animation: modalIn 0.2s ease;
    }

    body.dark .modal-box {
      background: #0C0C1E;
      color: #fbfbfb;
    }

    @keyframes modalIn {
      from { transform: scale(0.95) translateY(10px); opacity: 0; }
      to   { transform: scale(1)    translateY(0);    opacity: 1; }
    }

    /* Modal header */
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 24px 28px 20px;
      border-bottom: 1px solid #f0f0f0;
    }

    body.dark .modal-header {
      border-bottom-color: #1a1a2e;
    }

    .modal-header-left h3 {
      font-size: 20px;
      font-weight: 700;
      color: #1a1a1a;
      margin: 0 0 4px;
      font-family: 'Poppins', sans-serif;
    }

    body.dark .modal-header-left h3 {
      color: #fbfbfb;
    }

    .modal-header-left p {
      font-size: 13px;
      color: #aaa;
      margin: 0;
    }

    .modal-close {
      width: 36px;
      height: 36px;
      background: #f5f5f5;
      border: none;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #666;
      transition: all 0.2s;
      flex-shrink: 0;
    }

    .modal-close:hover {
      background: #fee2e2;
      color: #ef4444;
    }

    /* Modal body */
    .modal-body {
      padding: 24px 28px;
      display: flex;
      flex-direction: column;
      gap: 16px;
      flex: 1;
    }

    /* Info sections */
    .info-section {
      background: #f8fafc;
      border-radius: 14px;
      padding: 16px 20px;
    }

    body.dark .info-section {
      background: #111128;
    }

    .info-section-title {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 11px;
      font-weight: 700;
      color: #aaa;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin-bottom: 14px;
      font-family: 'Poppins', sans-serif;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .info-item label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      color: #aaa;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      margin-bottom: 3px;
    }

    .info-item span {
      font-size: 14px;
      font-weight: 600;
      color: #1a1a1a;
      font-family: 'Poppins', sans-serif;
    }

    body.dark .info-item span {
      color: #fbfbfb;
    }

    .info-item.full { grid-column: span 2; }

    /* Item rows */
    .item-row {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      background: #fff;
      border: 1px solid #f0f0f0;
      border-radius: 10px;
      margin-bottom: 6px;
    }

    body.dark .item-row {
      background: #0C0C1E;
      border-color: #1a1a2e;
    }

    .item-qty {
      font-weight: 800;
      color: #3C91E6;
      font-size: 14px;
      min-width: 28px;
    }

    .item-name {
      flex: 1;
      font-size: 14px;
      font-weight: 500;
      color: #1a1a1a;
    }

    body.dark .item-name {
      color: #fbfbfb;
    }

    .item-unit {
      font-size: 12px;
      color: #aaa;
    }

    .item-sub {
      font-size: 14px;
      font-weight: 700;
      color: #1a1a1a;
      min-width: 64px;
      text-align: right;
    }

    body.dark .item-sub {
      color: #fbfbfb;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 16px;
      background: #eff6ff;
      border-radius: 12px;
      margin-top: 8px;
      font-family: 'Poppins', sans-serif;
    }

    .total-row span:first-child {
      font-size: 14px;
      font-weight: 600;
      color: #555;
    }

    .total-amount {
      font-size: 22px;
      font-weight: 800;
      color: #3C91E6;
    }

    /* Status buttons */
    .status-btn-group {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .status-btn {
      padding: 8px 18px;
      border-radius: 20px;
      border: 2px solid #e0e0e0;
      background: #fff;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      font-family: 'Poppins', sans-serif;
      color: #888;
    }

    body.dark .status-btn {
      background: #0C0C1E;
      border-color: #2a2a3e;
      color: #888;
    }

    .status-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    .status-btn.s-pending    { border-color: #f59e0b; color: #f59e0b; }
    .status-btn.s-processing { border-color: #3b82f6; color: #3b82f6; }
    .status-btn.s-ready      { border-color: #22c55e; color: #22c55e; }
    .status-btn.s-completed  { border-color: #10b981; color: #10b981; }
    .status-btn.s-cancelled  { border-color: #ef4444; color: #ef4444; }

    .status-btn.active.s-pending    { background: #fff8e1; }
    .status-btn.active.s-processing { background: #eff6ff; }
    .status-btn.active.s-ready      { background: #f0fdf4; }
    .status-btn.active.s-completed  { background: #ecfdf5; }
    .status-btn.active.s-cancelled  { background: #fff1f2; }

    /* Modal footer */
    .modal-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 28px 20px;
      border-top: 1px solid #f0f0f0;
      gap: 12px;
    }

    body.dark .modal-footer {
      border-top-color: #1a1a2e;
    }

    .btn-close-modal {
      padding: 10px 24px;
      background: #f5f5f5;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      color: #555;
      font-family: 'Poppins', sans-serif;
      transition: all 0.2s;
    }

    .btn-close-modal:hover { background: #e5e5e5; }

    .btn-cancel-order {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 20px;
      background: #fff1f2;
      color: #ef4444;
      border: 2px solid #fca5a5;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
      font-family: 'Poppins', sans-serif;
    }

    .btn-cancel-order:hover {
      background: #ef4444;
      color: #fff;
      border-color: #ef4444;
    }

  </style>
</head>
<body>
  <script>
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark');
    }
  </script>

  <!-- SIDEBAR -->
  <section id="sidebar">
    <a href="index.php" class="brand">
      <i class='bx bxs-smile'></i>
      <span class="text">Yobyong Admin</span>
    </a>
    <ul class="side-menu top">
      <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
      <li><a href="myStore.php"><i class='bx bxs-shopping-bag-alt'></i><span class="text">My Store</span></a></li>
      <li class="active"><a href="orders.php"><i class='bx bxs-receipt'></i><span class="text">Orders</span></a></li>
      <li><a href="profile.php"><i class='bx bxs-user'></i><span class="text">Profile</span></a></li>
      <li><a href="staffList.php"><i class='bx bxs-group'></i><span class="text">Staff</span></a></li>
      <li><a href="viewFeedback.php"><i class='bx bxs-message-dots'></i><span class="text">Feedback</span></a></li>
    </ul>
    <ul class="side-menu">
      <li><a href="#"><i class='bx bxs-cog'></i><span class="text">Settings</span></a></li>
    </ul>
  </section>

  <!-- CONTENT -->
  <section id="content">
    <nav>
      <i class='bx bx-menu'></i>
      <a href="#" class="nav-link">Orders</a>
      <form>
        <div class="form-input">
          <input type="search" id="searchInput" placeholder="Search order / customer...">
          <button class="search-btn" type="button"><i class='bx bx-search'></i></button>
        </div>
      </form>
      <input type="checkbox" id="switch-mode" hidden>
      <label for="switch-mode" class="switch-mode"></label>
      <a href="#" class="notification"><i class='bx bxs-bell'></i><span class="num">8</span></a>
      <a href="profile.php" class="profile"><img src="img/people.png"></a>
    </nav>

    <main>
      <div class="head-title">
        <div class="left">
          <h1>Orders</h1>
          <ul class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="orders.php">Orders</a></li>
          </ul>
        </div>
      </div>

      <!-- Status filter tabs -->
      <div class="category-bar" style="margin-top:24px;">
        <button class="cat-tab active" onclick="filterStatus('all',this)">All</button>
        <button class="cat-tab" onclick="filterStatus('Pending',this)">Pending</button>
        <button class="cat-tab" onclick="filterStatus('Processing',this)">Processing</button>
        <button class="cat-tab" onclick="filterStatus('Ready',this)">Ready</button>
        <button class="cat-tab" onclick="filterStatus('Completed',this)">Completed</button>
        <button class="cat-tab" onclick="filterStatus('Cancelled',this)">Cancelled</button>
      </div>

      <!-- Orders table -->
      <div class="orders-wrap">
        <div class="orders-card">
          <table class="orders-table" id="ordersTable">
            <thead>
              <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="ordersBody">
              <tr><td colspan="7" class="orders-empty">Loading orders...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ── VIEW / EDIT MODAL ── -->
      <div id="viewModal" onclick="overlayClose(event)">
        <div class="modal-box">

          <!-- Header -->
          <div class="modal-header">
            <div class="modal-header-left">
              <h3 id="mOrderCode">Order #</h3>
              <p id="mOrderDate"></p>
            </div>
            <button class="modal-close" onclick="closeModal()"><i class='bx bx-x'></i></button>
          </div>

          <!-- Body -->
          <div class="modal-body">

            <!-- Status badge row -->
            <div style="display:flex; align-items:center; gap:10px;">
              <span style="font-size:13px; color:#aaa; font-family:'Poppins',sans-serif;">Current status:</span>
              <span id="mStatusBadge" class="badge"></span>
            </div>

            <!-- Customer -->
            <div class="info-section">
              <div class="info-section-title"><i class='bx bx-user'></i> Customer</div>
              <div class="info-grid">
                <div class="info-item">
                  <label>Name</label>
                  <span id="mName">—</span>
                </div>
                <div class="info-item">
                  <label>Phone</label>
                  <span id="mPhone">—</span>
                </div>
                <div class="info-item full">
                  <label>Note</label>
                  <span id="mNote">—</span>
                </div>
              </div>
            </div>

            <!-- Items -->
            <div class="info-section">
              <div class="info-section-title"><i class='bx bx-food-menu'></i> Order Items</div>
              <div id="mItems"></div>
              <div class="total-row">
                <span>Total</span>
                <span id="mTotal" class="total-amount">RM 0.00</span>
              </div>
            </div>

            <!-- Update status -->
            <div class="info-section">
              <div class="info-section-title"><i class='bx bx-refresh'></i> Update Status</div>
              <div class="status-btn-group" id="mStatusBtns"></div>
            </div>

          </div>

          <!-- Footer -->
          <div class="modal-footer">
            <button class="btn-close-modal" onclick="closeModal()">Close</button>
            <button class="btn-cancel-order" id="mCancelBtn" onclick="cancelOrder()">
              <i class='bx bx-x-circle'></i> Cancel Order
            </button>
          </div>

        </div>
      </div>

    </main>
  </section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="./script.js"></script>
<script>

let currentStatus = 'all';
let currentOrderId = null;

$(document).ready(function () {
  loadOrders();
  let t;
  $('#searchInput').on('input', function () {
    clearTimeout(t);
    t = setTimeout(loadOrders, 300);
  });
});

function loadOrders() {
  $.get('orders_ajax.php', {
    action: 'fetch',
    status: currentStatus,
    search: $('#searchInput').val()
  }, function (rows) {
    if (!rows.length) {
      $('#ordersBody').html('<tr><td colspan="7" class="orders-empty">No orders found.</td></tr>');
      return;
    }
    let html = '';
    rows.forEach(o => {
      const items = o.item_count + ' item' + (o.item_count != 1 ? 's' : '');
      const total = 'RM ' + parseFloat(o.total_amount).toFixed(2);
      html += `
        <tr>
          <td class="order-code">${esc(o.order_code)}</td>
          <td class="order-customer">${esc(o.customer_name)}</td>
          <td style="color:#aaa;">${esc(items)}</td>
          <td class="order-total">${total}</td>
          <td>${badge(o.order_status)}</td>
          <td class="order-date">${fmtDate(o.created_at)}</td>
          <td><button class="btn-view" onclick="viewOrder(${o.id})"><i class='bx bx-show'></i> View</button></td>
        </tr>`;
    });
    $('#ordersBody').html(html);
  }, 'json');
}

function filterStatus(status, btn) {
  currentStatus = status;
  document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  loadOrders();
}

function viewOrder(id) {
  currentOrderId = id;
  $.get('orders_ajax.php', { action: 'detail', id }, function (o) {

    $('#mOrderCode').text('Order ' + o.order_code);
    $('#mOrderDate').text(fmtDate(o.created_at));
    $('#mStatusBadge').attr('class', 'badge badge-' + o.order_status.toLowerCase()).text(o.order_status);
    $('#mName').text(o.customer_name);
    $('#mPhone').text(o.phone || '—');
    $('#mNote').text(o.notes || '—');
    $('#mTotal').text('RM ' + parseFloat(o.total_amount).toFixed(2));

    // Items
    let html = '';
    (o.items || []).forEach(item => {
      const sub = (parseFloat(item.price) * parseInt(item.qty)).toFixed(2);
      html += `
        <div class="item-row">
          <span class="item-qty">${item.qty}×</span>
          <span class="item-name">${esc(item.name)}</span>
          <span class="item-unit">RM ${parseFloat(item.price).toFixed(2)}</span>
          <span class="item-sub">RM ${sub}</span>
        </div>`;
    });
    $('#mItems').html(html || '<p style="color:#aaa;font-size:13px;">No items recorded.</p>');

    // Status buttons
    const statuses = ['Pending','Processing','Ready','Completed','Cancelled'];
    let btns = '';
    statuses.forEach(s => {
      const active = s === o.order_status ? 'active' : '';
      btns += `<button class="status-btn s-${s.toLowerCase()} ${active}" onclick="updateStatus('${s}')">${s}</button>`;
    });
    $('#mStatusBtns').html(btns);

    // Cancel button — hide if already done
    $('#mCancelBtn').toggle(o.order_status !== 'Cancelled' && o.order_status !== 'Completed');

    document.getElementById('viewModal').classList.add('open');
  }, 'json');
}

function updateStatus(status) {
  if (!currentOrderId) return;
  $.post('orders_ajax.php', { action: 'update_status', id: currentOrderId, status }, function (res) {
    if (res.success) {
      $('#mStatusBadge').attr('class', 'badge badge-' + status.toLowerCase()).text(status);
      $('.status-btn').removeClass('active');
      $(`.status-btn.s-${status.toLowerCase()}`).addClass('active');
      $('#mCancelBtn').toggle(status !== 'Cancelled' && status !== 'Completed');
      loadOrders();
    }
  }, 'json');
}

function cancelOrder() {
  if (!currentOrderId || !confirm('Cancel this order?')) return;
  $.post('orders_ajax.php', { action: 'cancel', id: currentOrderId }, function (res) {
    if (res.success) updateStatus('Cancelled');
  }, 'json');
}

function closeModal() {
  document.getElementById('viewModal').classList.remove('open');
  currentOrderId = null;
}

function overlayClose(e) {
  if (e.target === document.getElementById('viewModal')) closeModal();
}

function badge(status) {
  return `<span class="badge badge-${status.toLowerCase()}">${esc(status)}</span>`;
}

function fmtDate(str) {
  const d = new Date(str);
  return d.toLocaleDateString('en-MY', { day:'2-digit', month:'short', year:'numeric' })
    + ' ' + d.toLocaleTimeString('en-MY', { hour:'2-digit', minute:'2-digit' });
}

function esc(s) {
  const d = document.createElement('div');
  d.textContent = String(s);
  return d.innerHTML;
}

</script>
</body>
</html>