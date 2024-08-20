<?php
require 'incl/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = $_POST['otp'];
    $userId = $_SESSION['user_id'];

    // Retrieve OTP from database
    $stmt = $db->prepare("SELECT otp FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $enteredOtp == $user['otp']) {
        // OTP matches, clear OTP and proceed
        $stmt = $db->prepare("UPDATE users SET otp = NULL WHERE id = ?");
        if ($stmt->execute([$userId])) {
            echo "<p>You are now logged in. Welcome!</p>";
            // Redirect to the dashboard based on the user's role
            $stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            switch ($user['role']) {
                case 'siswa':
                    header("Location: siswa_dashboard.php");
                    break;
                case 'guru':
                    header("Location: guru_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'kepala sekolah':
                    header("Location: kepala_sekolah_dashboard.php");
                    break;
                default:
                    header("Location: login.php");
                    break;
            }
            exit;
        } else {
            echo "<p>Failed to log in. Please try again later.</p>";
        }
    } else {
        echo "<p>Invalid OTP. Please try again.</p>";
    }
}
?>

<h2>Enter OTP</h2>
<form method="POST">
    <input type="text" name="otp" placeholder="Enter OTP" required><br>
    <button type="submit">Verify OTP</button>
</form>
