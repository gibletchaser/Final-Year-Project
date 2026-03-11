<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// ── STATS ─────────────────────────────────────────────────────

// Total revenue (paid orders only)
$r = $conn->query("SELECT COALESCE(SUM(total_amount),0) AS total FROM orders WHERE payment_status='paid'");
$totalRevenue = $r->fetch_assoc()['total'];

// Total orders count
$r = $conn->query("SELECT COUNT(*) AS cnt FROM orders");
$totalOrders = $r->fetch_assoc()['cnt'];

// Total customers
$r = $conn->query("SELECT COUNT(*) AS cnt FROM customer");
$totalCustomers = $r->fetch_assoc()['cnt'];

// Total menu items
$r = $conn->query("SELECT COUNT(*) AS cnt FROM menu");
$totalItems = $r->fetch_assoc()['cnt'];

// ── MONTHLY REVENUE (last 7 months) ──────────────────────────
$monthlyData = [];
for ($i = 6; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $label = date('M',   strtotime("-$i months"));
    $r = $conn->query("
        SELECT COALESCE(SUM(total_amount),0) AS rev
        FROM orders
        WHERE payment_status='paid'
          AND DATE_FORMAT(created_at,'%Y-%m') = '$month'
    ");
    $monthlyData[] = [
        'label' => $label,
        'rev'   => (float)$r->fetch_assoc()['rev']
    ];
}
$maxRev = max(array_column($monthlyData, 'rev')) ?: 1;

// ── CATEGORY STATS (donut) ────────────────────────────────────
$catStats = [];
$r = $conn->query("
    SELECT c.name, COUNT(oi.id) AS qty
    FROM order_items oi
    JOIN menu m ON m.id = oi.menu_id
    JOIN categories c ON c.id = m.category_id
    GROUP BY c.id, c.name
    ORDER BY qty DESC
    LIMIT 5
");
if ($r) {
    while ($row = $r->fetch_assoc()) $catStats[] = $row;
}
$totalQty = array_sum(array_column($catStats, 'qty')) ?: 1;

// Donut colours
$donutColors = ['#3C91E6','#f87171','#fbbf24','#34d399','#a78bfa'];

// ── ORDERS BY STATUS ──────────────────────────────────────────
$statusList   = ['Pending','Processing','Ready','Completed','Cancelled'];
$statusCounts = [];
$statusColors = [
    'Pending'    => '#f59e0b',
    'Processing' => '#3b82f6',
    'Ready'      => '#22c55e',
    'Completed'  => '#10b981',
    'Cancelled'  => '#ef4444',
];
foreach ($statusList as $s) {
    $r = $conn->query("SELECT COUNT(*) AS cnt FROM orders WHERE order_status='$s'");
    $statusCounts[$s] = (int)$r->fetch_assoc()['cnt'];
}
$totalStatusCount = array_sum($statusCounts) ?: 1;

// ── RECENT ORDERS (last 5) ────────────────────────────────────
$recentOrders = [];
$r = $conn->query("
    SELECT order_code, customer_name, total_amount, order_status, created_at
    FROM orders
    ORDER BY created_at DESC
    LIMIT 5
");
if ($r) {
    while ($row = $r->fetch_assoc()) $recentOrders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Dashboard | Yobyong Admin</title>
</head>
<body>
<script>
    if (localStorage.getItem('darkMode') === 'enabled') document.body.classList.add('dark');
</script>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">Yobyong Admin</span>
    </a>
    <ul class="side-menu top">
        <li class="active"><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
        <li><a href="myStore.php"><i class='bx bxs-shopping-bag-alt'></i><span class="text">My Store</span></a></li>
        <li><a href="orders.php"><i class='bx bxs-receipt'></i><span class="text">Orders</span></a></li>
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
        <a href="#" class="nav-link">Dashboard</a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="notification"><i class='bx bxs-bell'></i>
            <span class="num"><?= array_sum(array_intersect_key($statusCounts, array_flip(['Pending','Processing']))) ?></span>
        </a>
        <a href="profile.php" class="profile"><img src="img/people.png"></a>
    </nav>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php" class="active">Dashboard</a></li>
                </ul>
            </div>
        </div>

        <!-- ── STAT CARDS ── -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon"><i class='bx bx-money'></i></div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">RM <?= number_format($totalRevenue, 2) ?></div>
                <div class="stat-change">From paid orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class='bx bx-cart'></i></div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?= number_format($totalOrders) ?></div>
                <div class="stat-change">All time orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class='bx bx-user'></i></div>
                <div class="stat-label">Customers</div>
                <div class="stat-value"><?= number_format($totalCustomers) ?></div>
                <div class="stat-change">Registered accounts</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class='bx bx-package'></i></div>
                <div class="stat-label">Menu Items</div>
                <div class="stat-value"><?= number_format($totalItems) ?></div>
                <div class="stat-change">Active on menu</div>
            </div>
        </div>

        <!-- ── DASHBOARD GRID ── -->
        <div class="dashboard-grid">

            <!-- LEFT: Monthly Revenue Bar Chart -->
            <div class="dashboard-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Monthly Revenue</h3>
                        <p class="chart-subtitle">Last 7 months (paid orders)</p>
                    </div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot purple"></span>
                        <span>Revenue (RM)</span>
                    </div>
                </div>
                <div class="bar-chart">
                    <?php foreach ($monthlyData as $m):
                        $heightPct = $maxRev > 0 ? round(($m['rev'] / $maxRev) * 90) : 5;
                        $heightPct = max($heightPct, 4);
                    ?>
                    <div class="bar-group">
                        <div class="bars">
                            <div class="bar purple" style="height:<?= $heightPct ?>%;"
                                 title="RM <?= number_format($m['rev'],2) ?>"></div>
                        </div>
                        <span class="bar-label"><?= $m['label'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Recent Orders Table -->
                <div style="margin-top:28px;">
                    <h4 style="font-size:14px;font-weight:700;margin-bottom:12px;color:var(--dark);">Recent Orders</h4>
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr style="background:#f5f6fa;">
                                <th style="padding:10px 12px;text-align:left;font-size:11px;text-transform:uppercase;color:#aaa;letter-spacing:.6px;">Order</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;text-transform:uppercase;color:#aaa;letter-spacing:.6px;">Customer</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;text-transform:uppercase;color:#aaa;letter-spacing:.6px;">Total</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;text-transform:uppercase;color:#aaa;letter-spacing:.6px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($recentOrders)): ?>
                            <tr><td colspan="4" style="padding:20px;text-align:center;color:#aaa;">No orders yet</td></tr>
                        <?php else: foreach ($recentOrders as $o):
                            $badgeColors = [
                                'Pending'    => '#fff8e1;color:#f59e0b',
                                'Processing' => '#eff6ff;color:#3b82f6',
                                'Ready'      => '#f0fdf4;color:#22c55e',
                                'Completed'  => '#ecfdf5;color:#10b981',
                                'Cancelled'  => '#fff1f2;color:#ef4444',
                            ];
                            $bc = $badgeColors[$o['order_status']] ?? '#eee;color:#888';
                        ?>
                            <tr style="border-top:1px solid #f0f0f0;">
                                <td style="padding:12px;font-weight:700;color:#3C91E6;"><?= htmlspecialchars($o['order_code']) ?></td>
                                <td style="padding:12px;"><?= htmlspecialchars($o['customer_name']) ?></td>
                                <td style="padding:12px;font-weight:700;">RM <?= number_format($o['total_amount'],2) ?></td>
                                <td style="padding:12px;">
                                    <span style="background:<?= $bc ?>;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                        <?= htmlspecialchars($o['order_status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                    <div style="text-align:right;margin-top:10px;">
                        <a href="orders.php" style="font-size:13px;color:#3C91E6;font-weight:600;">View all orders →</a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="right-column">

                <!-- Category Statistics (Donut) -->
                <div class="product-stats">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Category Orders</h3>
                            <p class="product-subtitle">Most ordered categories</p>
                        </div>
                    </div>

                    <?php if (empty($catStats)): ?>
                        <p style="text-align:center;color:#aaa;padding:20px;font-size:13px;">No order data yet</p>
                    <?php else:
                        // Build SVG donut dynamically
                        $circumference = 2 * M_PI * 70; // r=70
                        $offset = 0;
                    ?>
                    <div class="donut-chart">
                        <svg width="180" height="180" viewBox="0 0 180 180">
                            <circle cx="90" cy="90" r="70" fill="none" stroke="#f0f0f0" stroke-width="20"/>
                            <?php foreach ($catStats as $i => $cat):
                                $pct   = $cat['qty'] / $totalQty;
                                $dash  = $pct * $circumference;
                                $gap   = $circumference - $dash;
                                $color = $donutColors[$i % count($donutColors)];
                            ?>
                            <circle cx="90" cy="90" r="70" fill="none"
                                stroke="<?= $color ?>" stroke-width="20"
                                stroke-dasharray="<?= round($dash,2) ?> <?= round($gap,2) ?>"
                                stroke-dashoffset="-<?= round($offset,2) ?>"
                                transform="rotate(-90 90 90)"/>
                            <?php $offset += $dash; endforeach; ?>
                        </svg>
                        <div class="donut-center">
                            <div class="donut-value"><?= number_format($totalQty) ?></div>
                            <div class="donut-label">Items Ordered</div>
                        </div>
                    </div>

                    <div class="product-list">
                        <?php foreach ($catStats as $i => $cat):
                            $pct = round($cat['qty'] / $totalQty * 100);
                            $color = $donutColors[$i % count($donutColors)];
                        ?>
                        <div class="product-item">
                            <div class="product-name">
                                <span class="product-icon" style="background:<?= $color ?>;"></span>
                                <span><?= htmlspecialchars($cat['name']) ?></span>
                            </div>
                            <div class="product-value"><?= number_format($cat['qty']) ?></div>
                            <div class="stat-badge <?= $pct >= 30 ? 'green' : '' ?>" style="position:static;margin-left:8px;">
                                <?= $pct ?>%
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Orders by Status -->
                <div class="product-stats">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Orders Overview</h3>
                            <p class="product-subtitle">Current order pipeline</p>
                        </div>
                        <a href="orders.php" style="font-size:12px;color:#3C91E6;font-weight:600;white-space:nowrap;">Manage →</a>
                    </div>

                    <!-- Status bar -->
                    <div style="display:flex;height:12px;border-radius:8px;overflow:hidden;margin:16px 0 20px;gap:2px;">
                        <?php foreach ($statusCounts as $s => $cnt):
                            $w = round($cnt / $totalStatusCount * 100);
                            if ($w < 1) continue;
                        ?>
                        <div style="flex:<?= $w ?>;background:<?= $statusColors[$s] ?>;height:100%;"
                             title="<?= $s ?>: <?= $cnt ?>"></div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Status list -->
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <?php foreach ($statusCounts as $s => $cnt):
                            $pct = round($cnt / $totalStatusCount * 100);
                            $color = $statusColors[$s];
                        ?>
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;border-radius:50%;background:<?= $color ?>;flex-shrink:0;display:inline-block;"></span>
                                <span style="font-size:13px;font-weight:500;"><?= $s ?></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="font-size:15px;font-weight:700;"><?= $cnt ?></span>
                                <span style="font-size:11px;color:#aaa;width:34px;text-align:right;"><?= $pct ?>%</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Active orders callout -->
                    <?php
                        $active = ($statusCounts['Pending'] ?? 0) + ($statusCounts['Processing'] ?? 0);
                    ?>
                    <?php if ($active > 0): ?>
                    <div style="margin-top:18px;background:#eff6ff;border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px;">
                        <i class='bx bxs-bell-ring' style="font-size:22px;color:#3b82f6;"></i>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#1d4ed8;"><?= $active ?> order<?= $active > 1 ? 's' : '' ?> need attention</div>
                            <div style="font-size:12px;color:#3b82f6;">Pending + Processing</div>
                        </div>
                        <a href="orders.php" style="margin-left:auto;font-size:12px;font-weight:700;color:#3b82f6;">View →</a>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="./script.js"></script>
</body>
</html>