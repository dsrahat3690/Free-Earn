<?php
// এখানে লগইন চেক করার কোড পরে যোগ করা হবে
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>bd earn - লগইন</title>
</head>
<body>
  <h2>লগইন করুন</h2>
  <form method="post" action="login.php">
    <input type="email" name="email" placeholder="ইমেইল" required><br>
    <input type="password" name="password" placeholder="পাসওয়ার্ড" required><br>
    <button type="submit">লগইন</button>
  </form>
  <p>নতুন একাউন্ট তৈরি করতে <a href="register.php">রেজিস্টার করুন</a></p>
</body>
</html>
