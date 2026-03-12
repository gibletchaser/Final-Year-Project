<!DOCTYPE html>
<html lang="en">
<head>
    <title>Yob Yong Ordering System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</head>
<body>

<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "yobyong";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories
$catResult  = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = [];
if ($catResult) {
    while ($cat = $catResult->fetch_assoc()) {
        $categories[] = $cat;
    }
}

// Resolve image path — matches same logic as Guestmenu.php
function resolveImage($rawImage) {
    if (strpos($rawImage, 'img/') === 0) {
        // stored as img/xxx → serve from images/xxx
        return 'images/' . substr($rawImage, 4);
    } elseif (strpos($rawImage, 'uploads/') === 0) {
        return $rawImage;
    } else {
        return 'images/' . $rawImage;
    }
}

// Build menu items for a category
function getCategoryItems($conn, $category_id) {
    $stmt = $conn->prepare("SELECT id, name, price, image FROM menu WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        return '<p class="p-3 text-muted">No items in this category.</p>';
    }

    $output = '';
    while ($row = $result->fetch_assoc()) {
        $imagePath = resolveImage($row['image']);

        $output .= '<div class="col-md-12 col-lg-6 d-flex align-self-stretch">';
        $output .= '<div class="menus d-sm-flex ftco-animate align-items-stretch">';
        $output .= '<div class="menu-img img" style="background-image: url(\'' . htmlspecialchars($imagePath) . '\'), url(\'images/tempMenu.jpg\');"></div>';
        $output .= '<div class="text d-flex align-items-center">';
        $output .= '<div style="width:100%;">';
        $output .= '<div class="d-flex justify-content-between align-items-center">';
        $output .= '<div class="one-half"><h3 data-name="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</h3></div>';
        $output .= '<div class="one-forth"><span class="price" data-price="' . htmlspecialchars($row['price']) . '">RM ' . number_format((float)$row['price'], 2) . '</span></div>';
        $output .= '</div>';
        $output .= '<p>Available on our menu</p>';
        // Quantity + cart button
        $output .= '<div class="quantity-selector d-flex align-items-center mt-2">';
        $output .= '<button class="qty-btn minus" type="button" style="width:30px;height:30px;font-size:18px;border:1px solid #ccc;background:#fff;border-radius:4px;">-</button>';
        $output .= '<input type="number" class="qty-input" value="1" min="1" readonly style="width:45px;text-align:center;margin:0 6px;border:1px solid #ccc;border-radius:4px;height:30px;">';
        $output .= '<button class="qty-btn plus" type="button" style="width:30px;height:30px;font-size:18px;border:1px solid #ccc;background:#fff;border-radius:4px;">+</button>';
        $output .= '</div>';
        $output .= '<button class="btn btn-primary add-to-cart mt-2" '
            . 'data-id="' . (int)$row['id'] . '" '
            . 'data-name="' . htmlspecialchars($row['name']) . '" '
            . 'data-price="' . htmlspecialchars($row['price']) . '" '
            . 'style="font-size:13px;">Add to Cart</button>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    return $output;
}
?>

<!-- TOP BAR -->
<div class="py-1 bg-black top">
    <div class="container">
        <div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
            <div class="col-lg-12 d-block">
                <div class="row d-flex">
                    <div class="col-md pr-4 d-flex topper align-items-center">
                        <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
                        <span class="text">+6012-26828864</span>
                    </div>
                    <div class="col-md pr-4 d-flex topper align-items-center">
                        <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
                        <span class="text">info@yobyong.com</span>
                    </div>
                    <div class="col-md-5 pr-4 d-flex topper align-items-center text-lg-right justify-content-end">
                        <p class="mb-0 register-link"><span>Open hours:</span> <span>Isnin - Sunday</span> <span>10:00AM - 9:00PM</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Yob Yong</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item active"><a href="menu.php" class="nav-link">Menu</a></li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#cartModal">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="badge badge-pill badge-warning ml-1" id="cart-count">0</span>
                    </a>
                </li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item d-flex align-items-center" id="auth-area">
                    <a href="sign in.php" class="nav-link btn btn-primary px-4 py-2" style="border-radius:5px;">Sign In</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate text-center mb-4">
                <h1 class="mb-2 bread">Specialties</h1>
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span>
                    <span>Menu <i class="ion-ios-arrow-forward"></i></span>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- MENU SECTION -->
<section class="ftco-section">
    <div class="container">
        <div class="ftco-search">
            <div class="row">

                <?php if (empty($categories)): ?>
                    <div class="col-md-12">
                        <p class="text-danger">No categories found. Showing all items:</p>
                    </div>
                    <div class="col-md-12">
                        <div class="row no-gutters d-flex align-items-stretch">
                            <?php
                            $all = $conn->query("SELECT id, name, price, image FROM menu");
                            while ($row = $all->fetch_assoc()):
                                $imgPath = resolveImage($row['image']);
                            ?>
                            <div class="col-md-12 col-lg-6 d-flex align-self-stretch">
                                <div class="menus d-sm-flex ftco-animate align-items-stretch">
                                    <div class="menu-img img" style="background-image: url('<?= htmlspecialchars($imgPath) ?>'), url('images/tempMenu.jpg');"></div>
                                    <div class="text d-flex align-items-center">
                                        <div style="width:100%;">
                                            <div class="d-flex justify-content-between">
                                                <div class="one-half"><h3><?= htmlspecialchars($row['name']) ?></h3></div>
                                                <div class="one-forth"><span class="price">RM <?= number_format((float)$row['price'], 2) ?></span></div>
                                            </div>
                                            <p>Available on our menu</p>
                                            <div class="quantity-selector d-flex align-items-center mt-2">
                                                <button class="qty-btn minus" type="button" style="width:30px;height:30px;font-size:18px;border:1px solid #ccc;background:#fff;border-radius:4px;">-</button>
                                                <input type="number" class="qty-input" value="1" min="1" readonly style="width:45px;text-align:center;margin:0 6px;border:1px solid #ccc;border-radius:4px;height:30px;">
                                                <button class="qty-btn plus" type="button" style="width:30px;height:30px;font-size:18px;border:1px solid #ccc;background:#fff;border-radius:4px;">+</button>
                                            </div>
                                            <button class="btn btn-primary add-to-cart mt-2"
                                                data-id="<?= (int)$row['id'] ?>"
                                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                                data-price="<?= htmlspecialchars($row['price']) ?>"
                                                style="font-size:13px;">Add to Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- CATEGORY TABS -->
                    <div class="col-md-12 nav-link-wrap">
                        <div class="nav nav-pills d-flex text-center" id="v-pills-tab" role="tablist">
                            <!-- ALL tab — always first and active by default -->
                            <a class="nav-link ftco-animate active"
                               id="cat-tab-all"
                               data-toggle="pill"
                               href="#cat-all"
                               role="tab"
                               aria-selected="true">All</a>
                            <?php foreach ($categories as $cat): ?>
                                <a class="nav-link ftco-animate"
                                   id="cat-tab-<?= $cat['id'] ?>"
                                   data-toggle="pill"
                                   href="#cat-<?= $cat['id'] ?>"
                                   role="tab"
                                   aria-selected="false">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- MENU ITEMS -->
                    <div class="col-md-12 tab-wrap">
                        <div class="tab-content" id="v-pills-tabContent">

                            <!-- ALL pane -->
                            <div class="tab-pane fade show active" id="cat-all" role="tabpanel">
                                <div class="row no-gutters d-flex align-items-stretch">
                                    <?php
                                    $allItems = $conn->query("SELECT id, name, price, image FROM menu ORDER BY name ASC");
                                    while ($row = $allItems->fetch_assoc()):
                                        $imgPath = resolveImage($row['image']);
                                    ?>
                                    <div class="col-md-12 col-lg-6 d-flex align-self-stretch">
                                        <div class="menus d-sm-flex ftco-animate align-items-stretch">
                                            <div class="menu-img img" style="background-image: url('<?= htmlspecialchars($imgPath) ?>'), url('images/tempMenu.jpg');"></div>
                                            <div class="text d-flex align-items-center">
                                                <div style="width:100%;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="one-half"><h3><?= htmlspecialchars($row['name']) ?></h3></div>
                                                        <div class="one-forth"><span class="price">RM <?= number_format((float)$row['price'], 2) ?></span></div>
                                                    </div>
                                                    <p>Available on our menu</p>
                                                    <div class="quantity-selector d-flex align-items-center mt-2">
                                                        <button class="qty-btn minus" type="button" style="width:30px;height:30px;font-size:18px;border:1px solid #ccc;background:#fff;border-radius:4px;">-</button>
                                                        <input type="number" class="qty-input" value="1" min="1" readonly style="width:45px;text-align:center;margin:0 6px;border:1px solid #ccc;border-radius:4px;height:30px;">
                                                        <button class="qty-btn plus" type="button" style="width:30px;height:30px;font-size:18px;border:1px solid #ccc;background:#fff;border-radius:4px;">+</button>
                                                    </div>
                                                    <button class="btn btn-primary add-to-cart mt-2"
                                                        data-id="<?= (int)$row['id'] ?>"
                                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                                        data-price="<?= htmlspecialchars($row['price']) ?>"
                                                        style="font-size:13px;">Add to Cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>

                            <!-- Per-category panes -->
                            <?php foreach ($categories as $cat): ?>
                                <div class="tab-pane fade"
                                     id="cat-<?= $cat['id'] ?>"
                                     role="tabpanel">
                                    <div class="row no-gutters d-flex align-items-stretch">
                                        <?= getCategoryItems($conn, $cat['id']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- CART MODAL -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Your Cart</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cartItems" class="cart-items"></div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <h5>Total:</h5>
                    <h5>RM <span id="cartTotal">0.00</span></h5>
                </div>
                <div id="placeOrderForm">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" id="orderName" placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" class="form-control" id="orderPhone" placeholder="012-3456789">
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select class="form-control" id="paymentMethod">
                            <option value="">-- Select Payment Method --</option>
                            <option value="stripe">Credit/Debit Card or FPX (Stripe)</option>
                        </select>
                    </div>
                    <div id="stripe-payment-container" style="margin-top:20px; display:none; text-align:center;">
                        <button id="stripe-pay-btn" class="btn btn-primary btn-lg">Pay with Stripe →</button>
                        <p id="stripe-message" style="margin-top:10px; color:#666;"></p>
                    </div>
                    <div class="form-group">
                        <label>Additional Notes</label>
                        <textarea class="form-control" id="orderNotes" rows="2" placeholder="e.g. Less spicy, No onion..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue Shopping</button>
                <button type="button" class="btn btn-success" id="placeOrderBtn">Place Order</button>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
        <div class="row mb-5">
<<<<<<< HEAD
          <div class="col-md-6 col-lg-3">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Yob Yong</h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                <li class="ftco-animate"><a href="https://www.facebook.com/YobnYongsSemarak/"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="https://www.instagram.com/yobyongs_utmkl/"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Open Hours</h2>
              <ul class="list-unstyled open-hours">
                <li class="d-flex"><span>Monday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Tuesday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Wednesday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Thursday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Friday</span><span>10:00 - 21:00</span></li>
                <li class="d-flex"><span>Saturday</span><span>10:00 - 21:00</span></li>
              </ul>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Instagram</h2>
              <div class="thumb d-sm-flex">
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-1.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-2.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-3.jpg);">
	            	</a>
	            </div>
	            <div class="thumb d-flex">
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-4.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-5.jpg);">
	            	</a>
	            	<a href="#" class="thumb-menu img" style="background-image: url(images/insta-6.jpg);">
	            	</a>
	            </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Newsletter</h2>
            	<p>Far far away, behind the word mountains, far from the countries.</p>
              <form action="#" class="subscribe-form">
                
              </form>
=======
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Yob Yong</h2>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                        <li class="ftco-animate"><a href="https://www.facebook.com/YobnYongsSemarak/"><span class="icon-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="https://www.instagram.com/yobyongs_utmkl/"><span class="icon-instagram"></span></a></li>
                    </ul>
                </div>
>>>>>>> 81d369cebe9c61e48ef92f9c1c07470077754cf7
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Open Hours</h2>
                    <ul class="list-unstyled open-hours">
                        <li class="d-flex"><span>Monday</span><span>10:00 - 21:00</span></li>
                        <li class="d-flex"><span>Tuesday</span><span>10:00 - 21:00</span></li>
                        <li class="d-flex"><span>Wednesday</span><span>10:00 - 21:00</span></li>
                        <li class="d-flex"><span>Thursday</span><span>10:00 - 21:00</span></li>
                        <li class="d-flex"><span>Friday</span><span>10:00 - 21:00</span></li>
                        <li class="d-flex"><span>Saturday</span><span>10:00 - 21:00</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Instagram</h2>
                    <div class="thumb d-sm-flex">
                        <a href="#" class="thumb-menu img" style="background-image: url(images/insta-1.jpg);"></a>
                        <a href="#" class="thumb-menu img" style="background-image: url(images/insta-2.jpg);"></a>
                        <a href="#" class="thumb-menu img" style="background-image: url(images/insta-3.jpg);"></a>
                    </div>
                    <div class="thumb d-flex">
                        <a href="#" class="thumb-menu img" style="background-image: url(images/insta-4.jpg);"></a>
                        <a href="#" class="thumb-menu img" style="background-image: url(images/insta-5.jpg);"></a>
                        <a href="#" class="thumb-menu img" style="background-image: url(images/insta-6.jpg);"></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Newsletter</h2>
                    <p>Far far away, behind the word mountains.</p>
                    <form action="#" class="subscribe-form">
                        <div class="form-group">
                            <input type="text" class="form-control mb-2 text-center" placeholder="Enter email address">
                            <input type="submit" value="Subscribe" class="form-control submit px-3">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="ftco-loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
        <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
    </svg>
</div>

<!-- SCRIPTS — jQuery must come first, only once -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/jquery.animateNumber.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/jquery.timepicker.min.js"></script>
<script src="js/scrollax.min.js"></script>
<script src="js/google-map.js"></script>
<script src="js/main.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="script.js"></script>

<!-- AUTH: show user dropdown if logged in -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const userSession = localStorage.getItem('yobYongSession');
    const authArea    = document.getElementById('auth-area');
    if (userSession && authArea) {
        const user = JSON.parse(userSession);
        authArea.innerHTML = `
        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center" id="userMenu"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration:none;">
                <div style="width:40px;height:40px;border-radius:50%;border:2px solid #c4a47c;display:flex;align-items:center;justify-content:center;color:#c4a47c;">
                    <span class="icon-user" style="font-size:20px;"></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow border-0"
                 aria-labelledby="userMenu"
                 style="background:#fff;margin-top:10px;border-radius:10px;min-width:180px;">
                <div class="dropdown-header" style="color:#333;font-weight:600;border-bottom:1px solid #eee;padding-bottom:10px;">
                    Hi, ${user.name}
                </div>
                <a class="dropdown-item py-2" href="profile.php" style="color:#444;font-size:14px;">
                    <span class="icon-person mr-2"></span> My Profile
                </a>
                <a class="dropdown-item py-2" href="order_history.php" style="color:#444;font-size:14px;">
                    <span class="icon-clock mr-2"></span> My Orders
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item py-2" href="#" onclick="handleLogout()"
                   style="color:#e74c3c;font-size:14px;font-weight:600;">
                    <span class="icon-log-out mr-2"></span> Sign Out
                </a>
            </div>
        </div>`;

        // Pre-fill name/phone in cart form from session
        const nameInput  = document.getElementById('orderName');
        const phoneInput = document.getElementById('orderPhone');
        if (nameInput  && user.name)  nameInput.value  = user.name;
        if (phoneInput && user.phone) phoneInput.value = user.phone;
    }
});

function handleLogout() {
    if (confirm("Are you sure you want to sign out?")) {
        localStorage.removeItem('yobYongSession');
        window.location.href = 'index.php';
    }
}
</script>

<!-- PAYMENT METHOD: show Stripe button when selected -->
<script>
document.getElementById('paymentMethod')?.addEventListener('change', function () {
    const container = document.getElementById('stripe-payment-container');
    if (container) {
        container.style.display = this.value === 'stripe' ? 'block' : 'none';
    }
});
</script>

<!-- PLACE ORDER — single clean handler -->
<script>
document.getElementById('placeOrderBtn')?.addEventListener('click', async function (e) {
    e.preventDefault();

    const name   = document.getElementById('orderName').value.trim();
    const phone  = document.getElementById('orderPhone').value.trim();
    const notes  = document.getElementById('orderNotes').value.trim();
    const total  = parseFloat(document.getElementById('cartTotal').textContent) || 0;
    const cart   = JSON.parse(localStorage.getItem('cart') || '[]');

    if (!name || !phone) {
        alert("Please fill in your full name and phone number.");
        return;
    }
    if (cart.length === 0 || total <= 0) {
        alert("Your cart is empty! Add some items first.");
        return;
    }

    this.disabled    = true;
    this.textContent = 'Processing...';

    try {
        // Step 1 — Create Stripe checkout session
        const sessionRes  = await fetch('create-stripe-checkout.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ customer_name: name, phone, notes, amount: total, cart })
        });

        const sessionText = await sessionRes.text();
        let sessionData;
        try {
            sessionData = JSON.parse(sessionText);
        } catch (_) {
            throw new Error("create-stripe-checkout.php returned non-JSON:\n" + sessionText.substring(0, 200));
        }

        if (!sessionData.success || !sessionData.sessionId) {
            throw new Error(sessionData.error || "Failed to create payment session.");
        }

        // Step 2 — Save pending order in DB
        const orderRes  = await fetch('place-order.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                customer_name:      name,
                phone,
                notes,
                total_amount:       total,
                items:              cart,
                payment_method:     'stripe',
                stripe_session_id:  sessionData.sessionId
            })
        });

        const orderText = await orderRes.text();
        let orderData;
        try {
            orderData = JSON.parse(orderText);
        } catch (_) {
            throw new Error("place-order.php returned non-JSON:\n" + orderText.substring(0, 200));
        }

        if (!orderData.success) {
            throw new Error(orderData.message || "Failed to save your order.");
        }

        // Step 3 — Redirect to Stripe
        const stripe = Stripe('pk_test_51T4boFHWrfyRRRiKL7MLXoVgQRh15T7tTzc5LxW2KVoe34r5gf5CCtXSk7bfl6ppeyUIAt3iV5PGaaozjhC9N0wV00y6EcdaLs');
        const { error } = await stripe.redirectToCheckout({ sessionId: sessionData.sessionId });
        if (error) throw new Error(error.message);

    } catch (err) {
        console.error("Order error:", err);
        alert("Something went wrong:\n\n" + err.message);
    } finally {
        this.disabled    = false;
        this.textContent = 'Place Order';
    }
});
</script>

<!-- MODAL focus fix -->
<script>
$('#cartModal').on('hide.bs.modal',   function () { this.inert = true;  });
$('#cartModal').on('hidden.bs.modal', function () { this.inert = false; });
</script>

</body>
</html>