// /edit.php
<?php
require 'includes/config.php';
require 'includes/header.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$profileUpdated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];
    $newEmail = $_POST['newEmail'];
    
    // Handle image upload
    $newImage = null;
    if (!empty($_FILES['newImage']['name'])) {
        $newImage = 'uploads/' . basename($_FILES['newImage']['name']);
        move_uploaded_file($_FILES['newImage']['tmp_name'], $newImage);
    }

    if ($user->editAccount($userId, $newUsername, $newPassword, $newEmail, $newImage)) {
        $profileUpdated = true;
    } else {
        echo "Update failed!";
    }
}

// Fetch current user data
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $userId);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Edit Profile</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="newUsername" value="<?php echo $userData['username']; ?>" required><br>
    <input type="password" name="newPassword" placeholder="New Password" required><br>
    <input type="email" name="newEmail" value="<?php echo $userData['email']; ?>" required><br>
    <input type="file" name="newImage"><br>
    <button type="submit">Update Profile</button>
</form>

<?php if ($profileUpdated): ?>
<div id="successModal" style="display: block;">
    <div style="background: #fff; padding: 20px; border: 1px solid #ccc; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
        <p>Profile updated successfully!</p>
        <button onclick="closeModal()">Close</button>
    </div>
    <div style="background: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999;" onclick="closeModal()"></div>
</div>

<script>
function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}
</script>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
