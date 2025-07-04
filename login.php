<?php
session_start();
include 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $error = "সব ফিল্ড পূরণ করুন।";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // লগইন সফল, সেশন স্টোর করো
                $_SESSION['user_id'] = $id;
                $_SESSION['user_email'] = $email;

                header("Location: home.php");
                exit();
            } else {
                $error = "পাসওয়ার্ড সঠিক নয়।";
            }
        } else {
            $error = "ইমেইল পাওয়া যায়নি।";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <title>BD Earn - লগইন</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>🔐 লগইন করুন</h2>

    <?php if ($error): ?>
      <p style="color: red; margin-bottom: 15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="ইমেইল" required />
      <input type="password" name="password" placeholder="পাসওয়ার্ড" required />
      <button type="submit">লগইন</button>
    </form>
    <p>নতুন একাউন্ট করতে চান? <a href="register.php">রেজিস্টার করুন</a></p>
  </div>
</body>
</html>
