// /search_results.php
<?php
require 'incl/config.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username LIKE :query OR email LIKE :query");
    $stmt->execute(['query' => '%' . $query . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $user) {
            $username = htmlspecialchars($user['username']);
            $email = htmlspecialchars($user['email']);
            $image = $user['image'] ? htmlspecialchars($user['image']) : 'default.png';
            
            echo "<div style='margin-bottom: 15px;'>
                    <img src='$image' width='50' style='border-radius: 50%; vertical-align: middle;'>
                    <span style='margin-left: 10px; font-size: 18px;'>
                        $username <br> <small>$email</small>
                    </span>
                  </div>";
        }
    } else {
        echo "<p>No users found.</p>";
    }
}
?>
