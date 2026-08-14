<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireStudent();
$sid = $_SESSION['student_id'];

// Mark all as read
$pdo->prepare("UPDATE notifications SET is_read=1 WHERE user_type='student' AND user_id=?")->execute([$sid]);

$notifs = $pdo->prepare("SELECT * FROM notifications WHERE user_type='student' AND user_id=? ORDER BY created_at DESC");
$notifs->execute([$sid]);
$all = $notifs->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications — SEC Transport Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php" class="brand">🚗 SEC Transport <span>Hub</span></a>
    <nav><a href="dashboard.php">Home</a><a href="../logout.php">Logout</a></nav>
</div>
<div class="container">
    <h2 style="margin-bottom:20px">🔔 Notifications</h2>
    <?php if (empty($all)): ?>
        <div class="empty"><div class="icon">🔔</div><p>No notifications yet.</p></div>
    <?php else: ?>
        <?php foreach ($all as $n): ?>
        <div class="card" style="padding:14px;margin-bottom:10px;border-left:4px solid #1a73e8">
            <div style="font-size:0.9rem"><?= htmlspecialchars($n['message']) ?></div>
            <div style="font-size:0.78rem;color:#aaa;margin-top:6px"><?= date('d M Y, h:i A', strtotime($n['created_at'])) ?></div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
