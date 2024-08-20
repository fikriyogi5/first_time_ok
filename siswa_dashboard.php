<?php
require 'includes/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: login.php");
    exit;
}

// Your dashboard content for siswa
?>

<h2>Welcome to Siswa Dashboard</h2>
<p>Content specific to siswa role.</p>
