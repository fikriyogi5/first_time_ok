<?php
require 'includes/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role']; // Assume role can be updated

    // Handle image upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Update user data
    $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, image = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $email, $image, $role, $userId]);

    // Log profile update
    $stmt = $db->prepare("INSERT INTO log_history (user_id, action, details) VALUES (?, ?, ?)");
    $details = "Profile updated. Username: $username, Email: $email";
    $stmt->execute([$userId, 'Profile Update', $details]);

    echo "<p>Profile updated successfully!</p>";
}
?>

<h2>Edit Profile</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="file" name="image"><br>
    <select name="role" required>
        <option value="siswa">Siswa</option>
        <option value="guru">Guru</option>
        <option value="admin">Admin</option>
        <option value="kepala sekolah">Kepala Sekolah</option>
    </select><br>
    <button type="submit">Update Profile</button>
</form>
