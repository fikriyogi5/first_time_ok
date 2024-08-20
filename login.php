<?php
require 'includes/config.php';
require 'User.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($db);
    $loginResult = $user->login($username, $password);

    if (is_array($loginResult)) {
        // Login successful
        $_SESSION['user_id'] = $loginResult['id'];
        $_SESSION['role'] = $loginResult['role']; // Store user role in session
        
        // Generate OTP and send email
        $otp = rand(100000, 999999);
        $stmt = $db->prepare("UPDATE users SET otp = ? WHERE id = ?");
        $stmt->execute([$otp, $loginResult['id']]);
        
        // Send OTP email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.yourmailserver.com'; // Update with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; // Update with your email
            $mail->Password = 'your-email-password'; // Update with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your-email@example.com', 'YourAppName');
            $mail->addAddress($loginResult['email'], $username);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "<p>Your OTP code is <strong>$otp</strong>. Please use this code to log in.</p>";

            $mail->send();

            echo "<p>OTP has been sent to your email. Please check your email to log in.</p>";

            // Redirect to OTP verification page
            header("Location: otp.php");
            exit;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } elseif ($loginResult === "Account not activated") {
        echo "<p>Your account is not activated. Please check your email for the verification link.</p>";
    } else {
        echo "<p>Invalid username or password!</p>";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
