// /login.php
<?php
require 'includes/config.php';
require 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loggedInUser = $user->login($username, $password);
    if ($loggedInUser) {
        session_start();
        $_SESSION['user_id'] = $loggedInUser['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Login failed!";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>

<?php require 'includes/footer.php'; ?>
