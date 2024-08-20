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
        $_SESSION['user_id'] = $loginResult['id'];
        $_SESSION['role'] = $loginResult['role'];
        
        // Log the login activity
        $stmt = $db->prepare("INSERT INTO log_history (user_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], 'Login', 'User logged in']);

        $otp = rand(100000, 999999);
        $stmt = $db->prepare("UPDATE users SET otp = ? WHERE id = ?");
        $stmt->execute([$otp, $loginResult['id']]);
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.yourmailserver.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com';
            $mail->Password = 'your-email-password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your-email@example.com', 'YourAppName');
            $mail->addAddress($loginResult['email'], $username);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "<p>Your OTP code is <strong>$otp</strong>. Please use this code to log in.</p>";

            $mail->send();

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
