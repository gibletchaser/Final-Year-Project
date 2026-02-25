<?php
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51T4boFHWrfyRRRiKGHEc7DVdYEdqR9dBleew9M40E3veAJtqxREAcwBTQ1Cpxc4jSOdaT1yUa1erqQXSa9qUR23v00ypVrxVQd');

// DB connection (copy from place-order.php)
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "yobyong";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$session_id = $_GET['session_id'] ?? null;

if ($session_id) {
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        if ($session->payment_status === 'paid') {
            // Update order status
            $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid' WHERE stripe_session_id = ?");
            if ($stmt) {
                $stmt->bind_param("s", $session_id);
                $stmt->execute();
                $stmt->close();
            }

            $customer_name = $session->metadata->customer_name ?? 'Customer';
            $payment_intent = $session->payment_intent ?? 'N/A';

            echo "<h1>Thank You, " . htmlspecialchars($customer_name) . "!</h1>";
            echo "<p>Your order is confirmed and payment successful.</p>";
            echo "<p>Payment ID: " . htmlspecialchars($payment_intent) . "</p>";
            echo "<p>Session ID: " . htmlspecialchars($session_id) . "</p>";
            echo "<script>localStorage.removeItem('cart');</script>";
            // Add link back to menu or profile orders
            echo "<a href='menu.php'>Back to Menu</a>";
        } else {
            echo "<h1>Payment not completed.</h1>";
            echo "<p>Status: " . $session->payment_status . "</p>";
        }
    } catch (Exception $e) {
        echo "<h1>Error retrieving payment details</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<h1>No session found.</h1>";
}

$conn->close();
?>