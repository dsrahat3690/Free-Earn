
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user_email'];

// ইউজারের বর্তমান ব্যালেন্স বের করি
$stmt = $conn->prepare("SELECT id, balance FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id, $balance);
$stmt->fetch();
$stmt->close();

// প্রতিটি এড দেখলে কত টাকা পাবেন
$earn_per_ad = 2.00; // প্রতি এড ২ টাকা

// যদি "এড দেখা হয়েছে" সাবমিট করে
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['watched'])) {
    $new_balance = $balance + $earn_per_ad;

    // ইউজারের ব্যালেন্স আপডেট করি
    $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->bind_param("di", $new_balance, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: watchads.php?earned=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>🎥 এড দেখুন - ইনকাম করুন</title>
    <link rel="stylesheet" href="style.css">
    <style>
        iframe { width: 100%; height: 250px; border: 1px solid #ccc; margin-top: 10px; }
        form { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📺 বিজ্ঞাপন দেখুন</h2>

        <?php if (isset($_GET['earned'])): ?>
            <p style="color:green;">✅ আপনি ৳<?php echo number_format($earn_per_ad, 2); ?> ইনকাম করেছেন!</p>
        <?php endif; ?>

        <!-- এখানে তুমি তোমার Ad provider এর iframe বা কোড বসাতে পারো -->
        <iframe src="https://www.youtube.com/embed/tgbNymZ7vqY" allowfullscreen></iframe>

        <form method="POST">
            <input type="hidden" name="watched" value="1">
            <button type="submit">✅ আমি পুরো এডটি দেখেছি</button>
        </form>

        <br>
        <a href="home.php">⬅️ হোমে ফিরে যান</a>
    </div>
</body>
</html>
