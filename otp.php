<?php
require 'incl/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Concatenate the six input fields into one OTP string
    $enteredOtp = $_POST['otp1'] . $_POST['otp2'] . $_POST['otp3'] . $_POST['otp4'] . $_POST['otp5'] . $_POST['otp6'];
    $userId = $_SESSION['user_id'];

    // Retrieve OTP from database
    $stmt = $db->prepare("SELECT otp FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $enteredOtp == $user['otp']) {
        // OTP matches, clear OTP and proceed
        $stmt = $db->prepare("UPDATE users SET otp = NULL WHERE id = ?");
        if ($stmt->execute([$userId])) {
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
<form method="POST" id="otpForm">
    <input type="text" name="otp1" id="otp1" maxlength="1" oninput="moveToNext(this, 'otp2')" required>
    <input type="text" name="otp2" id="otp2" maxlength="1" oninput="moveToNext(this, 'otp3')" required>
    <input type="text" name="otp3" id="otp3" maxlength="1" oninput="moveToNext(this, 'otp4')" required>
    <input type="text" name="otp4" id="otp4" maxlength="1" oninput="moveToNext(this, 'otp5')" required>
    <input type="text" name="otp5" id="otp5" maxlength="1" oninput="moveToNext(this, 'otp6')" required>
    <input type="text" name="otp6" id="otp6" maxlength="1" oninput="submitForm()" required><br>
    <button type="submit">Verify OTP</button>
</form>

<script>
function moveToNext(current, nextFieldId) {
    if (current.value.length === current.maxLength) {
        document.getElementById(nextFieldId).focus();
    }
}

function submitForm() {
    // Check if all inputs are filled
    const inputs = document.querySelectorAll('#otpForm input[type="text"]');
    let otp = '';
    inputs.forEach(input => {
        otp += input.value;
    });

    if (otp.length === 6) {
        document.getElementById('otpForm').submit();
    }
}
</script>
