
<?php
// ডাটাবেজ কানেকশন
$conn = new mysqli("localhost", "root", "", "bd_earn");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// রেজিস্ট্রেশন সাবমিট হলে
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $mobile   = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // চেক করা হচ্ছে ইমেইল বা মোবাইল আগে থেকেই আছে কি না
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR mobile = ?");
    $check->bind_param("ss", $email, $mobile);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "ইমেইল বা মোবাইল নাম্বার আগেই ব্যবহার করা হয়েছে।";
    } else {
        // নতুন ইউজার ইনসার্ট
        $stmt = $conn->prepare("INSERT INTO users (name, email, mobile, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $mobile, $password);

        if ($stmt->execute()) {
            echo "রেজিস্ট্রেশন সফল হয়েছে। এখন লগইন করুন।";
            header("refresh:2;url=login.php");
        } else {
            echo "ভুল হয়েছে: " . $stmt->error;
        }

        $stmt->close();
    }

    $check->close();
}

$conn->close();
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Register - BD Earn</title>
</head>
<body>
    <h2>রেজিস্টার করুন</h2>
    <form method="post" action="">
        <label>নাম:</label><br>
        <input type="text" name="name" required><br><br>

        <label>ইমেইল:</label><br>
        <input type="email" name="email" required><br><br>

        <label>মোবাইল নাম্বার:</label><br>
        <input type="text" name="mobile" required><br><br>

        <label>পাসওয়ার্ড:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="রেজিস্টার">
    </form>
    <p>একাউন্ট আছে? <a href="login.php">লগইন করুন</a></p>
</body>
</html>
