<?php
session_start();
include 'db.php';

// Admin পাসওয়ার্ড চেক (সাধারণ নিরাপত্তা)
$admin_password = "admin123"; // এখানে পাসওয়ার্ড বদলাও

// যদি লগইন না করে থাকে
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        echo '
        <form method="POST">
            <h2>🔐 Admin Login</h2>
            <input type="password" name="password" placeholder="Admin Password" required />
            <button type="submit">লগইন</button>
        </form>';
        exit();
    }
}

// Approve/Reject Withdraw
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);

    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE withdrawals SET status = 'approved' WHERE id = ?");
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE withdrawals SET status = 'rejected' WHERE id = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin.php");
    exit();
}

// Fetch pending withdrawals
$result = $conn->query("SELECT w.id, u.name, u.email, w.amount, w.method, w.account, w.status, w.requested_at 
                        FROM withdrawals w 
                        JOIN users u ON w.user_id = u.id 
                        ORDER BY w.requested_at DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>🛡️ Admin Panel</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
    th { background-color: #f4f4f4; }
    a.button { padding: 5px 10px; text-decoration: none; margin: 0 5px; }
    .approve { background-color: #4CAF50; color: white; }
    .reject { background-color: #f44336; color: white; }
  </style>
</head>
<body>
  <div class="container">
    <h2>💼 Admin Panel - উত্তোলন অনুরোধ</h2>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <tr>
          <th>নাম</th>
          <th>ইমেইল</th>
          <th>পরিমাণ</th>
          <th>মেথড</th>
          <th>একাউন্ট</th>
          <th>স্টেটাস</th>
          <th>সময়</th>
          <th>অ্যাকশন</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>৳<?= number_format($row['amount'], 2) ?></td>
            <td><?= htmlspecialchars($row['method']) ?></td>
            <td><?= htmlspecialchars($row['account']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= $row['requested_at'] ?></td>
            <td>
              <?php if ($row['status'] === 'pending'): ?>
                <a class="button approve" href="?action=approve&id=<?= $row['id'] ?>">Approve</a>
                <a class="button reject" href="?action=reject&id=<?= $row['id'] ?>">Reject</a>
              <?php else: ?>
                <span>✔️</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p>🙌 কোনো উত্তোলন অনুরোধ নেই।</p>
    <?php endif; ?>
  </div>
</body>
</html>
