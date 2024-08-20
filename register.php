// /register.php
<?php
require 'includes/config.php';
require 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    
    // Handle image upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    if ($user->register($username, $password, $email, $image)) {
        // Store user data in session to display on success page
        session_start();
        $_SESSION['register_username'] = $username;
        $_SESSION['register_email'] = $email;
        $_SESSION['register_image'] = $image;
        header("Location: register_success.php");
        exit;
    } else {
        echo "Registration failed!";
    }
}
?>

<h2>Register</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="file" name="image"><br>
    <button type="submit">Register</button>
</form>

<?php require 'includes/footer.php'; ?>
