// /register_success.php
<?php
require 'incl/header.php';
session_start();

if (!isset($_SESSION['register_username'])) {
    header("Location: register.php");
    exit;
}

$username = $_SESSION['register_username'];
$email = $_SESSION['register_email'];
$image = $_SESSION['register_image'];

// Clear the session data
session_unset();
?>

<h2>Account Successfully Created!</h2>
<p>Here are the details of your registration:</p>
<ul>
    <li>Username: <?php echo htmlspecialchars($username); ?></li>
    <li>Email: <?php echo htmlspecialchars($email); ?></li>
    <li>Profile Image: <?php echo $image ? "<img src='$image' width='100'>" : "No image uploaded"; ?></li>
</ul>
<p>If everything looks correct, you can now <a href="login.php">log in</a>.</p>

<?php require 'incl/footer.php'; ?>
