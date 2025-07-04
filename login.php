<?php
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, password, name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password, $name);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "পাসওয়ার্ড ভুল হয়েছে।";
        }
    } else {
        $error = "এই ইমেইল এর কোন একাউন্ট পাওয়া যায়নি।";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <title>bd earn - লগইন</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>লগইন করুন</h2>

    <?php if (isset($error)) { ?>
      <p style="color: #ff4d4d; font-weight: bold;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="post" action="login.php">
      <input type="email" name="email" placeholder="ইমেইল" required /><br />
      <input type="password" name="password" placeholder="পাসওয়ার্ড" required /><br />
      <button type="submit">লগইন</button>
    </form>
    <p>নতুন একাউন্ট তৈরি করতে <a href="register.php">রেজিস্টার করুন</a></p>
  </div>
</body>
</html>
