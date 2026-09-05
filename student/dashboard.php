<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireStudent();

$sid = $_SESSION['student_id'];
$sname = $_SESSION['student_name'];
$sgender = $_SESSION['student_gender'];

// Get unread notifications
$notif = $pdo->prepare("SELECT COUNT(*) as cnt FROM notifications WHERE user_type='student' AND user_id=? AND is_read=0");
$notif->execute([$sid]);
$notif_count = $notif->fetch()['cnt'];

// My upcoming bookings
$bookings = $pdo->prepare("SELECT b.*, d.name as dname, d.vehicle_type, d.phone as dphone FROM bookings b JOIN drivers d ON b.driver_id=d.id WHERE b.student_id=? AND b.status IN ('pending','accepted') ORDER BY b.pickup_time ASC LIMIT 3");
$bookings->execute([$sid]);
$my_bookings = $bookings->fetchAll();

// My active ride shares
$rides = $pdo->prepare("SELECT r.* FROM rides r JOIN ride_members rm ON r.id=rm.ride_id WHERE rm.student_id=? AND r.status IN ('open','filling','full','driver_confirmed') ORDER BY r.ride_time ASC LIMIT 3");
$rides->execute([$sid]);
$my_rides = $rides->fetchAll();

// Count available drivers
$avail_drivers = $pdo->query("SELECT COUNT(*) as cnt FROM drivers WHERE is_online=1 AND status='approved'")->fetch()['cnt'];

// Count open rides
$open_rides = $pdo->query("SELECT COUNT(*) as cnt FROM rides WHERE status IN ('open','filling')")->fetch()['cnt'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — SEC Transport Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    <a href="dashboard.php" class="brand">🚗 SEC Transport <span>Hub</span></a>
    <nav>
        <a href="book_driver.php">Book Driver</a>
        <a href="post_ride.php">Post Ride</a>
        <a href="browse_rides.php">Browse Rides</a>
        <a href="my_rides.php">My Rides</a>
        <a href="notifications.php" class="bell">🔔
            <?php if ($notif_count > 0): ?>
                <span class="badge"><?= $notif_count ?></span>
            <?php endif; ?>
        </a>
        <a href="../logout.php">Logout</a>
    </nav>
</div>

<div class="container">
    <div style="margin-bottom:20px">
        <h2 style="font-size:1.3rem">Welcome, <?= htmlspecialchars($sname) ?> 👋</h2>
        <p style="color:#666;font-size:0.9rem">Where are you going today?</p>
    </div>

    <!-- Stats -->
    <div class="grid-3" style="margin-bottom:20px">
        <div class="stat-box">
            <div class="num"><?= $avail_drivers ?></div>
            <div class="label">Drivers Online Now</div>
        </div>
        <div class="stat-box">
            <div class="num"><?= $open_rides ?></div>
            <div class="label">Open Ride Shares</div>
        </div>
        <div class="stat-box">
            <div class="num"><?= count($my_bookings) + count($my_rides) ?></div>
            <div class="label">Your Active Rides</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid-2" style="margin-bottom:20px">
        <a href="book_driver.php" class="card" style="text-decoration:none;text-align:center;padding:28px">
            <div style="font-size:2.5rem;margin-bottom:10px">🚕</div>
            <div style="font-weight:600;font-size:1rem;color:#1a73e8">Book a Driver</div>
            <div style="font-size:0.82rem;color:#888;margin-top:6px">Book a verified CNG, Auto, or Bike driver at fixed price</div>
        </a>
        <a href="post_ride.php" class="card" style="text-decoration:none;text-align:center;padding:28px">
            <div style="font-size:2.5rem;margin-bottom:10px">👥</div>
            <div style="font-weight:600;font-size:1rem;color:#1a73e8">Post a Ride Share</div>
            <div style="font-size:0.82rem;color:#888;margin-top:6px">Find batchmates going same way and split the cost</div>
        </a>
    </div>

    <!-- My Upcoming Bookings -->
    <?php if (!empty($my_bookings)): ?>
    <div class="card">
        <div class="section-header">
            <h2 class="card-title" style="margin:0">My Upcoming Driver Bookings</h2>
            <a href="my_rides.php" style="font-size:0.85rem;color:#1a73e8">See all</a>
        </div>
        <?php foreach ($my_bookings as $b): ?>
        <div class="ride-card">
            <div class="route">
                <?= $b['direction'] === 'to_sec' ? '🏠 → 🏫 To SEC' : '🏫 → 🏠 From SEC' ?>
                — <?= htmlspecialchars($b['pickup_area']) ?>
            </div>
            <div class="meta">
                <span>🚕 <?= ucfirst($b['vehicle_type']) ?> — <?= htmlspecialchars($b['dname']) ?></span>
                <span>⏰ <?= date('d M, h:i A', strtotime($b['pickup_time'])) ?></span>
                <span>📍 <?= htmlspecialchars($b['meeting_point']) ?></span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span class="cost">৳<?= $b['fare'] ?></span>
                <span class="badge <?= $b['status']==='accepted' ? 'badge-success' : 'badge-warning' ?>">
                    <?= ucfirst($b['status']) ?>
                </span>
            </div>
            <?php if ($b['status'] === 'accepted'): ?>
                <div style="margin-top:8px;font-size:0.82rem;color:#28a745">📞 Driver: <?= $b['dphone'] ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- My Ride Shares -->
    <?php if (!empty($my_rides)): ?>
    <div class="card">
        <div class="section-header">
            <h2 class="card-title" style="margin:0">My Active Ride Shares</h2>
            <a href="my_rides.php" style="font-size:0.85rem;color:#1a73e8">See all</a>
        </div>
        <?php foreach ($my_rides as $r): ?>
        <div class="ride-card <?= $r['girls_only'] ? 'girls-only' : '' ?>">
            <div class="route">
                <?= $r['direction'] === 'to_sec' ? '🏠 → 🏫' : '🏫 → 🏠' ?>
                <?= htmlspecialchars($r['from_area']) ?> → <?= htmlspecialchars($r['to_area']) ?>
                <?php if ($r['girls_only']): ?><span class="badge badge-pink" style="margin-left:8px">Girls Only</span><?php endif; ?>
            </div>
            <div class="meta">
                <span>⏰ <?= date('d M, h:i A', strtotime($r['ride_time'])) ?></span>
                <span>💺 <?= $r['filled_seats'] ?>/<?= $r['total_seats'] ?> seats</span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between">
                <span class="cost">৳<?= $r['fare'] > 0 ? intval($r['fare'] / $r['filled_seats']) : 0 ?> each</span>
                <span class="badge badge-info"><?= ucfirst(str_replace('_',' ',$r['status'])) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Browse open rides -->
    <div style="text-align:center;margin-top:10px">
        <a href="browse_rides.php" class="btn btn-outline">Browse All Open Ride Shares →</a>
    </div>
</div>
</body>
</html>
