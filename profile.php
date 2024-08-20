// /profile.php
<?php
require 'incl/config.php';
require 'incl/header.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $userId);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($userData) {
    echo "<h2>Profile</h2>";
    echo "<p>Username: {$userData['username']}</p>";
    echo "<p>Email: {$userData['email']}</p>";
    if ($userData['image']) {
        echo "<p><img src='{$userData['image']}' width='100'></p>";
    }
    echo "<p><a href='edit.php'>Edit Profile</a></p>";
    echo "<p><a href='delete.php'>Delete Account</a></p>";
} else {
    echo "User not found.";
}

require 'incl/footer.php';
