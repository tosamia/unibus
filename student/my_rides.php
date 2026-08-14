<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireStudent();
$sid = $_SESSION['student_id'];

// Complete booking
if (isset($_GET['complete_booking'])) {
    $pdo->prepare("UPDATE bookings SET status='completed' WHERE id=? AND student_id=?")->execute([$_GET['complete_booking'], $sid]);
}
// Complete ride share
if (isset($_GET['complete_ride'])) {
    $pdo->prepare("UPDATE rides SET status='completed' WHERE id=? AND poster_id=?")->execute([$_GET['complete_ride'], $sid]);
}

// My driver bookings
$bookings = $pdo->prepare("SELECT b.*, d.name as dname, d.vehicle_type, d.phone as dphone, d.rating as drating FROM bookings b JOIN drivers d ON b.driver_id=d.id WHERE b.student_id=? ORDER BY b.created_at DESC");
$bookings->execute([$sid]);
$my_bookings = $bookings->fetchAll();

// My ride shares (posted or joined)
$rides = $pdo->prepare("SELECT DISTINCT r.*, s.name as poster_name, rm.cost_share FROM rides r JOIN ride_members rm ON r.id=rm.ride_id JOIN students s ON r.poster_id=s.id WHERE rm.student_id=? ORDER BY r.ride_time DESC");
$rides->execute([$sid]);
$my_rides = $rides->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rides — SEC Transport Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php" class="brand">🚗 SEC Transport <span>Hub</span></a>
    <nav>
        <a href="dashboard.php">Home</a>
        <a href="book_driver.php">Book Driver</a>
        <a href="post_ride.php">Post Ride</a>
        <a href="browse_rides.php">Browse Rides</a>
        <a href="../logout.php">Logout</a>
    </nav>
</div>
<div class="container">
    <h2 style="margin-bottom:20px">📋 My Rides</h2>

    <h3 style="font-size:1rem;margin-bottom:12px;color:#555">Driver Bookings</h3>
    <?php if (empty($my_bookings)): ?>
        <div class="empty"><div class="icon">🚕</div><p>No driver bookings yet. <a href="book_driver.php" style="color:#1a73e8">Book a driver</a></p></div>
    <?php else: ?>
    <div style="overflow-x:auto;margin-bottom:24px">
    <table class="table">
        <thead><tr><th>Route</th><th>Driver</th><th>Time</th><th>Fare</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($my_bookings as $b): ?>
        <tr>
            <td><?= $b['direction']==='to_sec'?'→ SEC':'← Home' ?><br><small style="color:#888"><?= htmlspecialchars($b['pickup_area']) ?></small></td>
            <td><?= htmlspecialchars($b['dname']) ?><br><small><?= strtoupper($b['vehicle_type']) ?></small></td>
            <td><?= date('d M, h:i A', strtotime($b['pickup_time'])) ?></td>
            <td><strong>৳<?= $b['fare'] ?></strong></td>
            <td>
                <span class="badge <?= $b['status']==='accepted'?'badge-success':($b['status']==='completed'?'badge-secondary':($b['status']==='rejected'?'badge-danger':'badge-warning')) ?>">
                    <?= ucfirst($b['status']) ?>
                </span>
                <?php if ($b['status']==='accepted'): ?>
                <br><small style="color:#28a745">📞 <?= $b['dphone'] ?></small>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($b['status']==='accepted'): ?>
                <a href="?complete_booking=<?= $b['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Mark as completed?')">Done</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>

    <h3 style="font-size:1rem;margin-bottom:12px;color:#555">Ride Shares</h3>
    <?php if (empty($my_rides)): ?>
        <div class="empty"><div class="icon">👥</div><p>No ride shares yet. <a href="browse_rides.php" style="color:#1a73e8">Browse rides</a></p></div>
    <?php else: ?>
    <?php foreach ($my_rides as $r): ?>
    <div class="ride-card <?= $r['girls_only']?'girls-only':'' ?>">
        <div class="route">
            <?= $r['direction']==='to_sec'?'🏠 → 🏫':'🏫 → 🏠' ?>
            <?= htmlspecialchars($r['from_area']) ?> → <?= htmlspecialchars($r['to_area']) ?>
            <?php if ($r['girls_only']): ?><span class="badge badge-pink">Girls Only</span><?php endif; ?>
            <?php if ($r['poster_id']==$sid): ?><span class="badge badge-info" style="margin-left:4px">Your Post</span><?php endif; ?>
        </div>
        <div class="meta">
            <span>⏰ <?= date('d M, h:i A', strtotime($r['ride_time'])) ?></span>
            <span>💺 <?= $r['filled_seats'] ?>/<?= $r['total_seats'] ?> seats</span>
            <span>Posted by: <?= htmlspecialchars($r['poster_name']) ?></span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
            <span class="cost">Your share: ৳<?= $r['cost_share'] ?></span>
            <span class="badge <?= $r['status']==='completed'?'badge-secondary':($r['status']==='driver_confirmed'?'badge-success':'badge-info') ?>">
                <?= ucfirst(str_replace('_',' ',$r['status'])) ?>
            </span>
        </div>
        <?php if ($r['status']==='driver_confirmed' && $r['poster_id']==$sid): ?>
        <a href="?complete_ride=<?= $r['id'] ?>" class="btn btn-success btn-sm" style="margin-top:8px" onclick="return confirm('Mark ride as completed?')">Mark Completed</a>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
