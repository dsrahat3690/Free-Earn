<?php
session_start();
include 'db.php';

// যদি ইউজার লগইন না করে থাকে
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user_email'];

// ইউজার ইনফো বের করি
$stmt = $conn->prepare("SELECT id, name, balance, referral_code FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id, $name, $balance, $ref_code);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>BD Earn - ড্যাশবোর্ড</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>👋 স্বাগতম, <?php echo htmlspecialchars($name); ?></h2>
    <p class="balance">💰 আপনার ব্যালেন্স: <strong>৳<?php echo number_format($balance, 2); ?></strong></p>

    <div class="card-grid">

      <div class="card">
        <h3>📺 এড দেখুন</h3>
        <p>প্রতিদিন এড দেখে ইনকাম করুন</p>
        <a href="watchads.php"><button>এড দেখুন</button></a>
      </div>

      <div class="card">
        <h3>💸 টাকা উত্তোলন</h3>
        <form method="POST" action="withdraw.php">
          <input type="number" name="amount" placeholder="৳ পরিমাণ (Min ৫০)" required>
          <select name="method" required>
            <option value="">পেমেন্ট মেথড</option>
            <option value="bkash">বিকাশ</option>
            <option value="nagad">নগদ</option>
            <option value="rocket">রকেট</option>
          </select>
          <input type="text" name="account" placeholder="একাউন্ট নাম্বার" required>
          <button type="submit">উত্তোলন করুন</button>
        </form>
      </div>

      <div class="card">
        <h3>🤝 রেফার করুন</h3>
        <p>আপনার রেফার কোড: <strong><?php echo $ref_code; ?></strong></p>
        <p>বন্ধু ইনকাম করলে আপনি পাবেন তার ১০%</p>
      </div>

    </div>

    <a href="logout.php"><button class="logout">🚪 লগআউট</button></a>
  </div>
</body>
</html>
