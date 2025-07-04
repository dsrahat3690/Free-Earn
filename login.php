
<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_email'] = $email;
            header("Location: home.php");
            exit();
        } else {
            $error = "⚠️ ভুল পাসওয়ার্ড।";
        }
    } else {
        $error = "⚠️ এই ইমেইল রেজিস্টার করা হয়নি।";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>🔐 লগইন করুন</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>🔐 লগইন করুন</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="আপনার ইমেইল" required>
        <input type="password" name="password" placeholder="পাসওয়ার্ড" required>
        <button type="submit">লগইন</button>
    </form>
    <p>একাউন্ট নেই? <a href="register.php">রেজিস্টার করুন</a></p>
</div>
</body>
</html>
