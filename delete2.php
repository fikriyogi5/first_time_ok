// /delete.php
<?php
require 'includes/config.php';
require 'includes/header.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$accountDeleted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirmation = $_POST['confirmation'];
    $password = $_POST['password'];

    // Fetch the current user's data
    $query = "SELECT password FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the password matches or if the user typed "CONFIRM DELETE"
    if (password_verify($password, $userData['password']) || $confirmation === 'CONFIRM DELETE') {
        if ($user->deleteAccount($userId)) {
            $accountDeleted = true;
            session_destroy();
            echo "<script>
                    alert('Account deleted successfully!');
                    window.location.href = 'register.php';
                  </script>";
            exit;
        } else {
            echo "Deletion failed!";
        }
    } else {
        echo "<p style='color:red;'>Incorrect password or confirmation text!</p>";
    }
}
?>

<h2>Confirm Account Deletion</h2>
<p>To delete your account, please enter your password or type "CONFIRM DELETE" in the confirmation box:</p>
<form method="POST">
    <input type="text" name="confirmation" placeholder="Type 'CONFIRM DELETE'"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <button type="submit">Delete Account</button>
</form>

<?php require 'includes/footer.php'; ?>
