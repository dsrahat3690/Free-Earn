<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $referral = trim($_POST['referral']);

    // রেফার কোড আছে কি না চেক করা যাবে পরবর্তী আপডেটে

    // চেক করো ইমেইল আগে আছে কিনা
    $sql_check = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $error = "এই ইমেইল ইতিমধ্যে রেজিস্টার করা আছে।";
    } else {
        // নতুন ইউজার ইন্সার্ট
        $sql = "INSERT INTO users (name, email, mobile, password, referral_code, referred_by) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // নিজের রেফার কোড তুমি তৈরি করে দিতে পারো, এখন সরাসরি ইমেইল দিয়ে রাখলাম
        $my_referral_code = $email;  // পরবর্তীতে নিজস্ব কোড বানাবে

        // যদি কেউ রেফার করে নাই, null দিবে
        $referred_by = $referral != "" ? $referral : null;

        $stmt->bind_param("ssssss", $name, $email, $mobile, $password, $my_referral_code, $referred_by);

        if ($stmt->execute()) {
            $success = "রেজিস্ট্রেশন সফল হয়েছে। এখন লগইন করুন।";
        } else {
            $error = "দুঃখিত, কিছু ভুল হয়েছে। আবার চেষ্টা করুন।";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <title>bd earn - রেজিস্ট্রেশন</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>রেজিস্টার করুন</h2>

    <?php if (isset($error)) { ?>
      <p style="color: #ff4d4d; font-weight: bold;"><?php echo $error; ?></p>
    <?php } ?>

    <?php if (isset($success)) { ?>
      <p style="color: #4CAF50; font-weight: bold;"><?php echo $success; ?></p>
    <?php } ?>

    <form method="post" action="register.php">
      <input type="text" name="name" placeholder="আপনার নাম" required /><br />
      <input type="email" name="email" placeholder="ইমেইল" required /><br />
      <input type="text" name="mobile" placeholder="মোবাইল নম্বর" required /><br />
      <input type="password" name="password" placeholder="পাসওয়ার্ড" required /><br />
      <input type="text" name="referral" placeholder="রেফার কোড (যদি থাকে)" /><br />
      <button type="submit">রেজিস্টার</button>
    </form>
    <p>আগেই একাউন্ট আছে? <a href="login.php">লগইন করুন</a></p>
  </div>
</body>
</html>
