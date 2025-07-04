<?php
// এখানে ডাটাবেজ সংযোগ ও ইউজার সেভ করার কোড পরে যোগ করা হবে
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>bd earn - রেজিস্ট্রেশন</title>
</head>
<body>
  <h2>রেজিস্টার করুন</h2>
  <form method="post" action="register.php">
    <input type="text" name="name" placeholder="আপনার নাম" required><br>
    <input type="email" name="email" placeholder="ইমেইল" required><br>
    <input type="text" name="mobile" placeholder="মোবাইল নম্বর" required><br>
    <input type="password" name="password" placeholder="পাসওয়ার্ড" required><br>
    <input type="text" name="referral" placeholder="রেফার কোড (যদি থাকে)"><br>
    <button type="submit">রেজিস্টার</button>
  </form>
  <p>আগেই একাউন্ট আছে? <a href="login.php">লগইন করুন</a></p>
</body>
</html>
