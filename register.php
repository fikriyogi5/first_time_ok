<?php
require 'includes/config.php';
require 'includes/header.php';
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role']; // Get the role from the form

    // Handle image upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Generate OTP and verification token
    $otp = rand(100000, 999999);
    $verificationToken = bin2hex(random_bytes(16)); // Generate a random token
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Save user data and verification token in the database
    $stmt = $db->prepare("INSERT INTO users (username, email, password, image, otp, verification_token, is_active, role) VALUES (?, ?, ?, ?, ?, ?, 0, ?)");
    if ($stmt->execute([$username, $email, $hashedPassword, $image, $otp, $verificationToken, $role])) {

        // Send verification email
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
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Account Verification';
            $mail->Body = "<p>Thank you for registering. Please click <a href='http://yourdomain.com/verify.php?token=$verificationToken'>here</a> to verify your email address.</p>";

            $mail->send();

            echo "<p>Registration successful! Please check your email to verify your account.</p>";

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
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
    <select name="role" required>
        <option value="siswa">Siswa</option>
        <option value="guru">Guru</option>
        <option value="admin">Admin</option>
        <option value="kepala sekolah">Kepala Sekolah</option>
    </select><br>
    <button type="submit">Register</button>
</form>

<?php require 'includes/footer.php'; ?>
