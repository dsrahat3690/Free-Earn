
<?php
session_start();
session_unset();  // সব সেশন ভ্যারিয়েবল ক্লিয়ার করে
session_destroy(); // সেশন ধ্বংস করে

header("Location: login.php"); // লগইন পেইজে পাঠিয়ে দেয়
exit();
