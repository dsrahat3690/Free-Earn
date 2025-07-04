<?php
// db.php
$host = "localhost";       // তোমার হোস্ট নাম (সাধারণত localhost)
$user = "your_db_user";    // ডাটাবেস ইউজারনেম
$pass = "your_db_pass";    // ডাটাবেস পাসওয়ার্ড
$dbname = "bd_earn_db";    // ডাটাবেস নাম

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
