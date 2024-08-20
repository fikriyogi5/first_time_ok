// /dashboard.php
<?php
require 'incl/config.php';
require 'incl/header.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

echo "<h2>Welcome to your Dashboard</h2>";
echo "<p><a href='profile.php'>Go to Profile</a></p>";

require 'incl/footer.php';
