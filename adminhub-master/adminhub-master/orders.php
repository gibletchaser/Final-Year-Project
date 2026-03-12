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
  <title>Orders | Yobyong Admin</title>
  <style>

    /* ── TABLE PAGE ─────────────────────────────── */
    .orders-wrap { margin-top: 24px; }

    .orders-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
    }
    body.dark .orders-card { background: #0C0C1E; }

    .orders-table { width: 100%; border-collapse: collapse; font-family: 'Poppins', sans-serif; }
    .orders-table thead tr { background: #f5f6fa; }
    body.dark .orders-table thead tr { background: #060714; }

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
    .orders-table thead th:last-child { text-align: right; padding-right: 28px; }

    .orders-table tbody tr { border-top: 1px solid #f0f0f0; transition: background 0.15s; }
    body.dark .orders-table tbody tr { border-top: 1px solid #1a1a2e; }
    .orders-table tbody tr:hover { background: #f9f9f9; }
    body.dark .orders-table tbody tr:hover { background: #111128; }

    .orders-table tbody td {
      padding: 16px 20px;
      font-size: 14px;
      color: #342E37;
      vertical-align: middle;
    }
    body.dark .orders-table tbody td { color: #fbfbfb; }
    .orders-table tbody td:last-child { text-align: right; padding-right: 28px; }

    .order-code     { font-weight: 700; color: #3C91E6; font-size: 13px; letter-spacing: 0.3px; }
    .order-customer { font-weight: 600; }
    .order-total    { font-weight: 700; font-size: 15px; }
    .order-date     { color: #aaa; font-size: 13px; }
    .orders-empty   { text-align: center; padding: 60px; color: #aaa; font-size: 14px; }

    /* ── STATUS BADGES ──────────────────────────── */
    .badge {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 12px; border-radius: 20px;
      font-size: 12px; font-weight: 700; white-space: nowrap;
    }
    .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .badge-pending    { background:#fff8e1; color:#f59e0b; }
    .badge-pending::before    { background:#f59e0b; }
    .badge-processing { background:#eff6ff; color:#3b82f6; }
    .badge-processing::before { background:#3b82f6; }
    .badge-ready      { background:#f0fdf4; color:#22c55e; }
    .badge-ready::before      { background:#22c55e; }
    .badge-completed  { background:#ecfdf5; color:#10b981; }
    .badge-completed::before  { background:#10b981; }
    .badge-cancelled  { background:#fff1f2; color:#ef4444; }
    .badge-cancelled::before  { background:#ef4444; }

    /* ── ACTION BUTTONS ─────────────────────────── */
    .btn-view {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 7px 14px; background: #eff6ff; color: #3C91E6;
      border: none; border-radius: 10px; font-size: 12px; font-weight: 600;
      cursor: pointer; transition: all 0.2s; font-family: 'Poppins', sans-serif;
    }
    .btn-view:hover {
      background: #3C91E6; color: #fff;
      transform: translateY(-1px); box-shadow: 0 6px 16px rgba(60,145,230,0.3);
    }
    .btn-receipt {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 7px 14px; margin-left: 6px;
      background: #f0fdf4; color: #10b981;
      border: none; border-radius: 10px; font-size: 12px; font-weight: 600;
      cursor: pointer; transition: all 0.2s; font-family: 'Poppins', sans-serif;
    }
    .btn-receipt:hover {
      background: #10b981; color: #fff;
      transform: translateY(-1px); box-shadow: 0 6px 16px rgba(16,185,129,0.3);
    }

    /* ── VIEW MODAL ─────────────────────────────── */
    #viewModal {
      position: fixed; inset: 0; background: rgba(0,0,0,0.55);
      backdrop-filter: blur(3px); display: none;
      align-items: center; justify-content: center;
      z-index: 9999; padding: 20px;
    }
    #viewModal.open { display: flex; }

    .modal-box {
      background: #fff; border-radius: 24px; width: 560px;
      max-width: 100%; max-height: 88vh; overflow-y: auto;
      display: flex; flex-direction: column;
      box-shadow: 0 24px 60px rgba(0,0,0,0.2); animation: modalIn 0.2s ease;
    }
    body.dark .modal-box { background: #0C0C1E; color: #fbfbfb; }

    @keyframes modalIn {
      from { transform: scale(0.95) translateY(10px); opacity: 0; }
      to   { transform: scale(1) translateY(0); opacity: 1; }
    }

    .modal-header {
      display: flex; justify-content: space-between; align-items: center;
      padding: 24px 28px 20px; border-bottom: 1px solid #f0f0f0;
    }
    body.dark .modal-header { border-bottom-color: #1a1a2e; }

    .modal-header-left h3 {
      font-size: 20px; font-weight: 700; color: #1a1a1a;
      margin: 0 0 4px; font-family: 'Poppins', sans-serif;
    }
    body.dark .modal-header-left h3 { color: #fbfbfb; }
    .modal-header-left p { font-size: 13px; color: #aaa; margin: 0; }

    .modal-close {
      width: 36px; height: 36px; background: #f5f5f5; border: none;
      border-radius: 50%; font-size: 20px; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      color: #666; transition: all 0.2s; flex-shrink: 0;
    }
    .modal-close:hover { background: #fee2e2; color: #ef4444; }

    .modal-body { padding: 24px 28px; display: flex; flex-direction: column; gap: 16px; flex: 1; }

    .info-section { background: #f8fafc; border-radius: 14px; padding: 16px 20px; }
    body.dark .info-section { background: #111128; }

    .info-section-title {
      display: flex; align-items: center; gap: 7px;
      font-size: 11px; font-weight: 700; color: #aaa;
      text-transform: uppercase; letter-spacing: 0.8px;
      margin-bottom: 14px; font-family: 'Poppins', sans-serif;
    }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .info-item label {
      display: block; font-size: 11px; font-weight: 600; color: #aaa;
      text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 3px;
    }
    .info-item span { font-size: 14px; font-weight: 600; color: #1a1a1a; font-family: 'Poppins', sans-serif; }
    body.dark .info-item span { color: #fbfbfb; }
    .info-item.full { grid-column: span 2; }

    .item-row {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 14px; background: #fff;
      border: 1px solid #f0f0f0; border-radius: 10px; margin-bottom: 6px;
    }
    body.dark .item-row { background: #0C0C1E; border-color: #1a1a2e; }
    .item-qty  { font-weight: 800; color: #3C91E6; font-size: 14px; min-width: 28px; }
    .item-name { flex: 1; font-size: 14px; font-weight: 500; color: #1a1a1a; }
    body.dark .item-name { color: #fbfbfb; }
    .item-unit { font-size: 12px; color: #aaa; }
    .item-sub  { font-size: 14px; font-weight: 700; color: #1a1a1a; min-width: 64px; text-align: right; }
    body.dark .item-sub { color: #fbfbfb; }

    .total-row {
      display: flex; justify-content: space-between; align-items: center;
      padding: 14px 16px; background: #eff6ff; border-radius: 12px;
      margin-top: 8px; font-family: 'Poppins', sans-serif;
    }
    .total-row span:first-child { font-size: 14px; font-weight: 600; color: #555; }
    .total-amount { font-size: 22px; font-weight: 800; color: #3C91E6; }

    .status-btn-group { display: flex; flex-wrap: wrap; gap: 8px; }
    .status-btn {
      padding: 8px 18px; border-radius: 20px; border: 2px solid #e0e0e0;
      background: #fff; font-size: 13px; font-weight: 600; cursor: pointer;
      transition: all 0.2s; font-family: 'Poppins', sans-serif; color: #888;
    }
    body.dark .status-btn { background: #0C0C1E; border-color: #2a2a3e; color: #888; }
    .status-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .status-btn.s-pending    { border-color:#f59e0b; color:#f59e0b; }
    .status-btn.s-processing { border-color:#3b82f6; color:#3b82f6; }
    .status-btn.s-ready      { border-color:#22c55e; color:#22c55e; }
    .status-btn.s-completed  { border-color:#10b981; color:#10b981; }
    .status-btn.s-cancelled  { border-color:#ef4444; color:#ef4444; }
    .status-btn.active.s-pending    { background:#fff8e1; }
    .status-btn.active.s-processing { background:#eff6ff; }
    .status-btn.active.s-ready      { background:#f0fdf4; }
    .status-btn.active.s-completed  { background:#ecfdf5; }
    .status-btn.active.s-cancelled  { background:#fff1f2; }

    .modal-footer {
      display: flex; justify-content: space-between; align-items: center;
      padding: 16px 28px 20px; border-top: 1px solid #f0f0f0; gap: 12px;
    }
    body.dark .modal-footer { border-top-color: #1a1a2e; }

    .btn-close-modal {
      padding: 10px 24px; background: #f5f5f5; border: none; border-radius: 10px;
      font-size: 14px; font-weight: 600; cursor: pointer; color: #555;
      font-family: 'Poppins', sans-serif; transition: all 0.2s;
    }
    .btn-close-modal:hover { background: #e5e5e5; }

    .btn-cancel-order {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 10px 20px; background: #fff1f2; color: #ef4444;
      border: 2px solid #fca5a5; border-radius: 10px;
      font-size: 13px; font-weight: 700; cursor: pointer;
      transition: all 0.2s; font-family: 'Poppins', sans-serif;
    }
    .btn-cancel-order:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    /* ── RECEIPT MODAL ──────────────────────────── */
    #receiptModal {
      position: fixed; inset: 0; background: rgba(0,0,0,0.6);
      backdrop-filter: blur(4px); display: none;
      align-items: center; justify-content: center;
      z-index: 10000; padding: 20px;
    }
    #receiptModal.open { display: flex; }

    .receipt-box {
      background: #fff; border-radius: 20px; width: 420px;
      max-width: 100%; max-height: 90vh; overflow-y: auto;
      box-shadow: 0 30px 80px rgba(0,0,0,0.25);
      animation: modalIn 0.2s ease; font-family: 'Poppins', sans-serif;
    }

    #receiptContent { padding: 32px 28px 20px; }

    .receipt-logo { text-align: center; margin-bottom: 8px; }
    .receipt-logo-icon {
      width: 52px; height: 52px;
      background: linear-gradient(135deg,#3C91E6,#6366f1);
      border-radius: 16px; display: inline-flex;
      align-items: center; justify-content: center;
      font-size: 26px; color: #fff; margin-bottom: 8px;
    }
    .receipt-brand   { font-size: 18px; font-weight: 800; color: #1a1a1a; letter-spacing: -0.3px; }
    .receipt-tagline { font-size: 11px; color: #aaa; margin-top: 2px; }

    .receipt-divider { border: none; border-top: 2px dashed #e5e7eb; margin: 16px 0; }

    .receipt-meta { display: flex; flex-direction: column; gap: 6px; }
    .receipt-meta-row { display: flex; justify-content: space-between; font-size: 13px; }
    .receipt-meta-row .r-label { color: #aaa; font-weight: 500; }
    .receipt-meta-row .r-val   { font-weight: 700; color: #1a1a1a; text-align: right; }

    .receipt-status-pill {
      display: inline-block; padding: 2px 10px;
      border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .receipt-status-pill.pending    { background:#fff8e1; color:#f59e0b; }
    .receipt-status-pill.processing { background:#eff6ff; color:#3b82f6; }
    .receipt-status-pill.ready      { background:#f0fdf4; color:#22c55e; }
    .receipt-status-pill.completed  { background:#ecfdf5; color:#10b981; }
    .receipt-status-pill.cancelled  { background:#fff1f2; color:#ef4444; }

    .receipt-items-header {
      display: flex; font-size: 10px; font-weight: 700; color: #aaa;
      text-transform: uppercase; letter-spacing: .6px;
      padding: 0 4px 8px; border-bottom: 1px solid #e5e7eb;
    }
    .receipt-item-row {
      display: flex; align-items: center;
      padding: 9px 4px; border-bottom: 1px solid #f3f4f6; font-size: 13px;
    }
    .receipt-item-row .ri-name  { flex: 1; font-weight: 600; color: #1a1a1a; }
    .receipt-item-row .ri-qty   { width: 36px; text-align: center; color: #3C91E6; font-weight: 800; }
    .receipt-item-row .ri-price { width: 72px; text-align: right; color: #aaa; font-size: 12px; }
    .receipt-item-row .ri-sub   { width: 76px; text-align: right; font-weight: 700; color: #1a1a1a; }

    .receipt-totals { margin-top: 14px; display: flex; flex-direction: column; gap: 6px; }
    .receipt-total-row { display: flex; justify-content: space-between; font-size: 13px; }
    .receipt-total-row .t-label { color: #888; }
    .receipt-total-row .t-val   { font-weight: 600; color: #1a1a1a; }
    .receipt-total-row.grand { margin-top: 10px; padding-top: 10px; border-top: 2px solid #1a1a1a; }
    .receipt-total-row.grand .t-label { font-size: 15px; font-weight: 800; color: #1a1a1a; }
    .receipt-total-row.grand .t-val   { font-size: 20px; font-weight: 800; color: #3C91E6; }

    .receipt-footer-note {
      text-align: center; margin-top: 22px;
      font-size: 11px; color: #bbb; line-height: 1.7;
    }

    .receipt-actions {
      display: flex; gap: 10px;
      padding: 16px 28px 24px; border-top: 1px solid #f0f0f0;
    }
    .receipt-actions .btn-close-modal { flex: 1; }

    .btn-print-receipt {
      flex: 2; display: inline-flex; align-items: center;
      justify-content: center; gap: 8px;
      padding: 11px 20px; background: #3C91E6; color: #fff;
      border: none; border-radius: 10px; font-size: 13px; font-weight: 700;
      cursor: pointer; font-family: 'Poppins', sans-serif; transition: background .2s;
    }
    .btn-print-receipt:hover { background: #2563eb; }

    /* ── PRINT STYLES ───────────────────────────── */
    @media print {
      #sidebar, nav, .head-title, .category-bar, .orders-wrap,
      #viewModal, .receipt-actions { display: none !important; }

      #content { margin: 0 !important; padding: 0 !important; }
      main      { margin: 0 !important; padding: 0 !important; }

      #receiptModal {
        display: block !important; position: static !important;
        background: none !important; padding: 0 !important;
        z-index: auto !important;
      }
      .receipt-box {
        box-shadow: none !important; border-radius: 0 !important;
        max-height: none !important; overflow: visible !important;
        width: 100% !important;
      }
      #receiptContent { padding: 16px !important; }
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
        <button class="cat-tab" onclick="filterStatus('pending',this)">Pending</button>
        <button class="cat-tab" onclick="filterStatus('processing',this)">Processing</button>
        <button class="cat-tab" onclick="filterStatus('ready',this)">Ready</button>
        <button class="cat-tab" onclick="filterStatus('completed',this)">Completed</button>
        <button class="cat-tab" onclick="filterStatus('cancelled',this)">Cancelled</button>
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
                <th style="text-align:right;padding-right:28px;">Actions</th>
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

          <div class="modal-header">
            <div class="modal-header-left">
              <h3 id="mOrderCode">Order #</h3>
              <p id="mOrderDate"></p>
            </div>
            <button class="modal-close" onclick="closeModal()"><i class='bx bx-x'></i></button>
          </div>

          <div class="modal-body">

            <div style="display:flex;align-items:center;gap:10px;">
              <span style="font-size:13px;color:#aaa;font-family:'Poppins',sans-serif;">Current status:</span>
              <span id="mStatusBadge" class="badge"></span>
            </div>

            <div class="info-section">
              <div class="info-section-title"><i class='bx bx-user'></i> Customer</div>
              <div class="info-grid">
                <div class="info-item">
                  <label>Name</label>
                  <span id="mName">-</span>
                </div>
                <div class="info-item">
                  <label>Phone</label>
                  <span id="mPhone">-</span>
                </div>
                <div class="info-item full">
                  <label>Note</label>
                  <span id="mNote">-</span>
                </div>
              </div>
            </div>

            <div class="info-section">
              <div class="info-section-title"><i class='bx bx-food-menu'></i> Order Items</div>
              <div id="mItems"></div>
              <div class="total-row">
                <span>Total</span>
                <span id="mTotal" class="total-amount">RM 0.00</span>
              </div>
            </div>

            <div class="info-section">
              <div class="info-section-title"><i class='bx bx-refresh'></i> Update Status</div>
              <div class="status-btn-group" id="mStatusBtns"></div>
            </div>

          </div>

          <div class="modal-footer">
            <button class="btn-close-modal" onclick="closeModal()">Close</button>
            <button class="btn-cancel-order" id="mCancelBtn" onclick="cancelOrder()">
              <i class='bx bx-x-circle'></i> Cancel Order
            </button>
          </div>

        </div>
      </div>

      <!-- ── RECEIPT MODAL ── -->
      <div id="receiptModal" onclick="receiptOverlayClose(event)">
        <div class="receipt-box">

          <div id="receiptContent">

            <div class="receipt-logo">
              <div class="receipt-logo-icon"><i class='bx bxs-smile'></i></div>
              <div class="receipt-brand">Yobyong</div>
              <div class="receipt-tagline">Thank you for your order!</div>
            </div>

            <hr class="receipt-divider">

            <div class="receipt-meta" id="rMeta"></div>

            <hr class="receipt-divider">

            <div class="receipt-items-header">
              <span style="flex:1;">Item</span>
              <span style="width:36px;text-align:center;">Qty</span>
              <span style="width:72px;text-align:right;">Price</span>
              <span style="width:76px;text-align:right;">Subtotal</span>
            </div>
            <div id="rItems"></div>

            <div class="receipt-totals" id="rTotals"></div>

            <hr class="receipt-divider">

            <div class="receipt-footer-note">
              This is your official receipt.<br>
              Please retain for your records.<br>
              <strong>Yobyong</strong>
            </div>

          </div>

          <div class="receipt-actions">
            <button class="btn-close-modal" onclick="closeReceipt()">Close</button>
            <button class="btn-print-receipt" onclick="printReceipt()">
              <i class='bx bx-printer'></i> Print / Save PDF
            </button>
          </div>

        </div>
      </div>

    </main>
  </section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="./script.js"></script>
<script>

var currentStatus  = 'all';
var currentOrderId = null;

$(document).ready(function () {
  loadOrders();
  var t;
  $('#searchInput').on('input', function () {
    clearTimeout(t);
    t = setTimeout(loadOrders, 300);
  });
});

/* ── HELPERS ────────────────────────────────── */
function esc(s) {
  var d = document.createElement('div');
  d.textContent = (s == null) ? '' : String(s);
  return d.innerHTML;
}

function fmtDate(str) {
  var d = new Date(str);
  return d.toLocaleDateString('en-MY', { day:'2-digit', month:'short', year:'numeric' })
       + ' ' + d.toLocaleTimeString('en-MY', { hour:'2-digit', minute:'2-digit' });
}

function badge(status) {
  return '<span class="badge badge-' + status.toLowerCase() + '">' + esc(status) + '</span>';
}

/* ── LOAD ORDERS ────────────────────────────── */
function loadOrders() {
  $.get('orders_ajax.php', {
    action: 'fetch',
    status: currentStatus,
    search: $('#searchInput').val()
  }, function (rows) {
    if (!rows || !rows.length) {
      $('#ordersBody').html('<tr><td colspan="7" class="orders-empty">No orders found.</td></tr>');
      return;
    }
    var html = '';
    rows.forEach(function (o) {
      var items = o.item_count + ' item' + (o.item_count != 1 ? 's' : '');
      var total = 'RM ' + parseFloat(o.total_amount).toFixed(2);
      html += '<tr>'
        + '<td class="order-code">'     + esc(o.order_code)    + '</td>'
        + '<td class="order-customer">' + esc(o.customer_name) + '</td>'
        + '<td style="color:#aaa;">'    + esc(items)           + '</td>'
        + '<td class="order-total">'    + total                + '</td>'
        + '<td>'                        + badge(o.order_status) + '</td>'
        + '<td class="order-date">'     + fmtDate(o.created_at) + '</td>'
        + '<td>'
          + '<button class="btn-view"    onclick="viewOrder('   + o.id + ')"><i class=\'bx bx-show\'></i> View</button>'
          + '<button class="btn-receipt" onclick="viewReceipt(' + o.id + ')"><i class=\'bx bx-receipt\'></i> Receipt</button>'
        + '</td>'
        + '</tr>';
    });
    $('#ordersBody').html(html);
  }, 'json');
}

function filterStatus(status, btn) {
  currentStatus = status;
  document.querySelectorAll('.cat-tab').forEach(function (b) { b.classList.remove('active'); });
  btn.classList.add('active');
  loadOrders();
}

/* ── VIEW ORDER MODAL ───────────────────────── */
function viewOrder(id) {
  currentOrderId = id;
  $.get('orders_ajax.php', { action: 'detail', id: id }, function (o) {
    if (!o || o.error) { alert('Could not load order.'); return; }

    $('#mOrderCode').text('Order ' + o.order_code);
    $('#mOrderDate').text(fmtDate(o.created_at));
    $('#mStatusBadge').attr('class', 'badge badge-' + o.order_status.toLowerCase()).text(o.order_status);
    $('#mName').text(o.customer_name  || '-');
    $('#mPhone').text(o.phone         || '-');
    $('#mNote').text(o.notes          || '-');
    $('#mTotal').text('RM ' + parseFloat(o.total_amount).toFixed(2));

    var html = '';
    (o.items || []).forEach(function (item) {
      var sub = (parseFloat(item.price) * parseInt(item.qty)).toFixed(2);
      html += '<div class="item-row">'
        + '<span class="item-qty">'  + esc(item.qty)  + 'x</span>'
        + '<span class="item-name">' + esc(item.name) + '</span>'
        + '<span class="item-unit">RM ' + parseFloat(item.price).toFixed(2) + '</span>'
        + '<span class="item-sub">RM '  + sub + '</span>'
        + '</div>';
    });
    $('#mItems').html(html || '<p style="color:#aaa;font-size:13px;">No items recorded.</p>');

    var statuses = ['pending','processing','ready','completed','cancelled'];
    var btns = '';
    statuses.forEach(function (s) {
      var label  = s.charAt(0).toUpperCase() + s.slice(1);
      var active = (s === o.order_status.toLowerCase()) ? ' active' : '';
      btns += '<button class="status-btn s-' + s + active + '" onclick="updateStatus(\'' + s + '\')">' + label + '</button>';
    });
    $('#mStatusBtns').html(btns);

    var st = o.order_status.toLowerCase();
    $('#mCancelBtn').toggle(st !== 'cancelled' && st !== 'completed');

    document.getElementById('viewModal').classList.add('open');
  }, 'json');
}

function updateStatus(status) {
  if (!currentOrderId) return;
  $.post('orders_ajax.php', { action: 'update_status', id: currentOrderId, status: status }, function (res) {
    if (res.success) {
      var label = status.charAt(0).toUpperCase() + status.slice(1);
      $('#mStatusBadge').attr('class', 'badge badge-' + status).text(label);
      $('.status-btn').removeClass('active');
      $('.status-btn.s-' + status).addClass('active');
      $('#mCancelBtn').toggle(status !== 'cancelled' && status !== 'completed');
      loadOrders();
    }
  }, 'json');
}

function cancelOrder() {
  if (!currentOrderId || !confirm('Cancel this order?')) return;
  $.post('orders_ajax.php', { action: 'cancel', id: currentOrderId }, function (res) {
    if (res.success) updateStatus('cancelled');
  }, 'json');
}

function closeModal() {
  document.getElementById('viewModal').classList.remove('open');
  currentOrderId = null;
}

function overlayClose(e) {
  if (e.target === document.getElementById('viewModal')) closeModal();
}

/* ── RECEIPT MODAL ──────────────────────────── */
function viewReceipt(id) {
  $.get('orders_ajax.php', { action: 'detail', id: id }, function (o) {
    if (!o || o.error) { alert('Could not load receipt.'); return; }

    var status      = (o.order_status || '').toLowerCase();
    var statusLabel = status.charAt(0).toUpperCase() + status.slice(1);

    var metaHtml = ''
      + '<div class="receipt-meta-row"><span class="r-label">Order Code</span><span class="r-val">' + esc(o.order_code) + '</span></div>'
      + '<div class="receipt-meta-row"><span class="r-label">Date &amp; Time</span><span class="r-val">' + fmtDate(o.created_at) + '</span></div>'
      + '<div class="receipt-meta-row"><span class="r-label">Customer</span><span class="r-val">' + esc(o.customer_name) + '</span></div>';

    if (o.phone) {
      metaHtml += '<div class="receipt-meta-row"><span class="r-label">Phone</span><span class="r-val">' + esc(o.phone) + '</span></div>';
    }

    metaHtml += '<div class="receipt-meta-row"><span class="r-label">Status</span>'
      + '<span class="r-val"><span class="receipt-status-pill ' + status + '">' + statusLabel + '</span></span></div>';

    $('#rMeta').html(metaHtml);

    var subtotal  = 0;
    var itemsHtml = '';
    (o.items || []).forEach(function (item) {
      var sub = parseFloat(item.price) * parseInt(item.qty);
      subtotal += sub;
      itemsHtml += '<div class="receipt-item-row">'
        + '<span class="ri-name">'  + esc(item.name) + '</span>'
        + '<span class="ri-qty">'   + esc(item.qty)  + '</span>'
        + '<span class="ri-price">RM ' + parseFloat(item.price).toFixed(2) + '</span>'
        + '<span class="ri-sub">RM '   + sub.toFixed(2) + '</span>'
        + '</div>';
    });
    $('#rItems').html(itemsHtml || '<p style="color:#aaa;font-size:13px;padding:8px 0;">No items found.</p>');

    var total      = parseFloat(o.total_amount);
    var totalsHtml = ''
      + '<div class="receipt-total-row"><span class="t-label">Subtotal</span><span class="t-val">RM ' + subtotal.toFixed(2) + '</span></div>'
      + '<div class="receipt-total-row grand"><span class="t-label">Total</span><span class="t-val">RM ' + total.toFixed(2) + '</span></div>';
    $('#rTotals').html(totalsHtml);

    document.getElementById('receiptModal').classList.add('open');
  }, 'json');
}

function closeReceipt() {
  document.getElementById('receiptModal').classList.remove('open');
}

function receiptOverlayClose(e) {
  if (e.target === document.getElementById('receiptModal')) closeReceipt();
}

function printReceipt() {
  window.print();
}

</script>
</body>
</html>