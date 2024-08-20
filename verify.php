<?php
require 'includes/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $stmt = $db->prepare("SELECT * FROM users WHERE verification_token = ? AND is_active = 0");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Activate the account
        $stmt = $db->prepare("UPDATE users SET is_active = 1, verification_token = NULL WHERE verification_token = ?");
        if ($stmt->execute([$token])) {
            echo "<p>Your account has been verified successfully! You can now <a href='login.php'>log in</a>.</p>";
        } else {
            echo "<p>Failed to activate the account. Please try again later.</p>";
        }
    } else {
        echo "<p>Invalid or expired token.</p>";
    }
}
?>
