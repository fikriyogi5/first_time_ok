// /includes/config.php
<?php
require 'Database.php';
require 'User.php';

// Initialize Database
$database = new Database();
$db = $database->connect();

// Initialize User Object
$user = new User($db);
