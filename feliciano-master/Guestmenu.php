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
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
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

// Fetch all categories dynamically from 'categories' table
$catResult = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = [];
if ($catResult) {
    while ($cat = $catResult->fetch_assoc()) {
        $categories[] = $cat;
    }
}

// Get menu items for a given category_id
function getCategoryItems($conn, $category_id) {
    $stmt = $conn->prepare("SELECT id, name, price, image FROM menu WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        return '<p>No items in this category.</p>';
    }

    $output = '';
    while ($row = $result->fetch_assoc()) {
        $rawImage = $row["image"];

        // Fix path: 'img/...' in DB -> 'images/' on disk
        // 'uploads/...' in DB -> 'uploads/' on disk (already correct)
        if (strpos($rawImage, 'img/') === 0) {
            $imagePath = 'images/' . substr($rawImage, 4);
        } elseif (strpos($rawImage, 'uploads/') === 0) {
            $imagePath = $rawImage;
        } else {
            $imagePath = 'images/' . $rawImage;
        }

        $output .= '<div class="col-md-12 col-lg-6 d-flex align-self-stretch">';
        $output .= '<div class="menus d-sm-flex ftco-animate align-items-stretch">';
        $output .= '<div class="menu-img img" style="background-image: url(\'' . htmlspecialchars($imagePath) . '\'), url(\'images/tempMenu.jpg\');"></div>';
        $output .= '<div class="text d-flex align-items-center">';
        $output .= '<div>';
        $output .= '<div class="d-flex">';
        $output .= '<div class="one-half">';
        $output .= '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
        $output .= '</div>';
        $output .= '<div class="one-forth">';
        $output .= '<span class="price">RM ' . number_format((float)$row["price"], 2) . '</span>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<p>Available on our menu</p>';
        $output .= '<a href="sign in.php" class="btn btn-outline-warning btn-sm mt-2">Sign in to Order</a>';
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
                            <span class="text">yobyong24@gmail.com</span>
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
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item active"><a href="Guestmenu.php" class="nav-link">Menu</a></li>
                    <li class="nav-item cta"><a href="sign in.php" class="nav-link">Sign In</a></li>
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
                        <!-- Fallback: show all menu items if no categories found -->
                        <div class="col-md-12">
                            <p class="text-danger">No categories found in database. Showing all items:</p>
                        </div>
                        <div class="col-md-12">
                            <div class="row no-gutters d-flex align-items-stretch">
                                <?php
                                $allItems = $conn->query("SELECT id, name, price, image FROM menu");
                                while ($row = $allItems->fetch_assoc()) {
                                    $rawImage = $row["image"];
                                    if (strpos($rawImage, 'img/') === 0) {
                                        $imagePath = 'images/' . substr($rawImage, 4);
                                    } elseif (strpos($rawImage, 'uploads/') === 0) {
                                        $imagePath = $rawImage;
                                    } else {
                                        $imagePath = 'images/' . $rawImage;
                                    }
                                    echo '<div class="col-md-12 col-lg-6 d-flex align-self-stretch">';
                                    echo '<div class="menus d-sm-flex ftco-animate align-items-stretch">';
                                    echo '<div class="menu-img img" style="background-image: url(\'' . htmlspecialchars($imagePath) . '\'), url(\'images/tempMenu.jpg\');"></div>';
                                    echo '<div class="text d-flex align-items-center"><div>';
                                    echo '<div class="d-flex">';
                                    echo '<div class="one-half"><h3>' . htmlspecialchars($row["name"]) . '</h3></div>';
                                    echo '<div class="one-forth"><span class="price">RM ' . number_format((float)$row["price"], 2) . '</span></div>';
                                    echo '</div>';
                                    echo '<p>Available on our menu</p>';
                                    echo '<a href="sign in.php" class="btn btn-outline-warning btn-sm mt-2">Sign in to Order</a>';
                                    echo '</div></div></div></div>';
                                }
                                ?>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- CATEGORY TABS -->
                        <div class="col-md-12 nav-link-wrap">
                            <div class="nav nav-pills d-flex text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php foreach ($categories as $index => $cat): ?>
                                    <a class="nav-link ftco-animate <?php echo $index === 0 ? 'active' : ''; ?>"
                                       id="cat-tab-<?php echo $cat['id']; ?>"
                                       data-toggle="pill"
                                       href="#cat-<?php echo $cat['id']; ?>"
                                       role="tab"
                                       aria-controls="cat-<?php echo $cat['id']; ?>"
                                       aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- MENU ITEMS PER CATEGORY TAB -->
                        <div class="col-md-12 tab-wrap">
                            <div class="tab-content" id="v-pills-tabContent">
                                <?php foreach ($categories as $index => $cat): ?>
                                    <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
                                         id="cat-<?php echo $cat['id']; ?>"
                                         role="tabpanel"
                                         aria-labelledby="cat-tab-<?php echo $cat['id']; ?>">
                                        <div class="row no-gutters d-flex align-items-stretch">
                                            <?php echo getCategoryItems($conn, $cat['id']); ?>
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

    <!-- FOOTER -->
    <footer class="ftco-footer ftco-bg-dark ftco-section">
        <div class="container">
            <div class="row mb-5">
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
                        <p>Far far away, behind the word mountains, far from the countries.</p>
                        <form action="#" class="subscribe-form">
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- LOADER -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
        </svg>
    </div>

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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="js/google-map.js"></script>
    <script src="js/main.js"></script>

</body>
</html>

<?php $conn->close(); ?>