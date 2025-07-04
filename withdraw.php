<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user_email'];
$error = '';

// ইউজার আইডি ও ব্যালেন্স বের করি
$stmt = $conn->prepare("SELECT id, balance FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id, $balance);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $method = trim($_POST['method']);
    $account = trim($_POST['account']);

    if ($amount < 50) {
        $error = "মিনিমাম উত্তোলন ৫০ টাকা।";
    } elseif ($amount > $balance) {
        $error = "আপনার একাউন্টে পর্যাপ্ত ব্যালেন্স নেই।";
    } elseif (!$method || !$account) {
        $error = "সব তথ্য দিন।";
    } else {
        // উইথড্রাল রিকোয়েস্ট সেভ করি
        $stmt = $conn->prepare("INSERT INTO withdrawals (user_id, amount, method, account, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("idss", $user_id, $amount, $method, $account);

        if ($stmt->execute()) {
            // ইউজারের ব্যালেন্স থেকে কেটে ফেলি
            $new_balance = $balance - $amount;
            $stmt2 = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $stmt2->bind_param("di", $new_balance, $user_id);
            $stmt2->execute();
            $stmt2->close();

            header("Location: home.php?msg=withdraw_success");
            exit();
        } else {
            $error = "উত্তোলন ব্যর্থ হয়েছে। পরে আবার চেষ্টা করুন।";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>উত্তোলন</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>💸 টাকা উত্তোলন করুন</h2>

    <?php if ($error): ?>
      <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="number" name="amount" placeholder="৳ পরিমাণ (Min ৫০)" required />
      <select name="method" required>
        <option value="">পেমেন্ট মেথড নির্বাচন করুন</option>
        <option value="bkash">বিকাশ</option>
        <option value="nagad">নগদ</option>
        <option value="rocket">রকেট</option>
      </select>
      <input type="text" name="account" placeholder="একাউন্ট নম্বর" required />
      <button type="submit">উত্তোলন করুন</button>
    </form>

    <br>
    <a href="home.php">⬅️ হোম পেইজে ফিরে যান</a>
  </div>
</body>
</html>
