<?php
$servername = "localhost";      // তোমার ডাটাবেজ হোস্ট (প্রায় সব ক্ষেত্রে localhost)
$username = "your_db_user";     // ডাটাবেজ ইউজারনেম
$password = "your_db_pass";     // ডাটাবেজ পাসওয়ার্ড
$dbname = "your_db_name";       // ডাটাবেজ নাম

// কানেকশন তৈরি
$conn = new mysqli($servername, $username, $password, $dbname);

// কানেকশন চেক
if ($conn->connect_error) {
    die("ডাটাবেজ কানেকশন ব্যর্থ: " . $conn->connect_error);
}
?>
