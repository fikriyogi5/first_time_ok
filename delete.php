// /delete.php
<?php
require 'incl/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$accountDeleted = false;

if ($user->deleteAccount($userId)) {
    $accountDeleted = true;
    session_destroy();
} else {
    echo "Deletion failed!";
}
?>

<?php if ($accountDeleted): ?>
<div id="successModal" style="display: block;">
    <div style="background: #fff; padding: 20px; border: 1px solid #ccc; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
        <p>Account deleted successfully!</p>
        <button onclick="closeModalAndRedirect()">Close</button>
    </div>
    <div style="background: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999;" onclick="closeModalAndRedirect()"></div>
</div>

<script>
function closeModalAndRedirect() {
    document.getElementById('successModal').style.display = 'none';
    window.location.href = 'register.php';
}
</script>
<?php endif; ?>
