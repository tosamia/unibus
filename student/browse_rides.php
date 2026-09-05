<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireStudent();

$sid     = $_SESSION['student_id'];
$sgender = $_SESSION['student_gender'];
$success = ''; $error = '';

// Handle join ride
if (isset($_GET['join'])) {
    $ride_id = intval($_GET['join']);
    $ride = $pdo->prepare("SELECT * FROM rides WHERE id=?");
    $ride->execute([$ride_id]);
    $r = $ride->fetch();

    if (!$r) { $error = "Ride not found."; }
    elseif ($r['girls_only'] && $sgender !== 'female') { $error = "This is a girls only ride."; }
    elseif ($r['filled_seats'] >= $r['total_seats']) { $error = "This ride is already full."; }
    else {
        // Check not already joined
        $chk = $pdo->prepare("SELECT id FROM ride_members WHERE ride_id=? AND student_id=?");
        $chk->execute([$ride_id, $sid]);
        if ($chk->fetch()) { $error = "You already joined this ride."; }
        else {
            $new_filled = $r['filled_seats'] + 1;
            $new_status = $new_filled >= $r['total_seats'] ? 'full' : 'filling';
            $pdo->prepare("UPDATE rides SET filled_seats=?, status=? WHERE id=?")->execute([$new_filled, $new_status, $ride_id]);
            $share = intval($r['fare'] / $new_filled);
            $pdo->prepare("INSERT INTO ride_members (ride_id, student_id, cost_share) VALUES (?,?,?)")->execute([$ride_id, $sid, $share]);
            updateRideCost($pdo, $ride_id);
            notify($pdo, 'student', $r['poster_id'], "Someone joined your ride to {$r['to_area']}! New cost per person: ৳$share");
            $success = "You joined the ride! Your share: ৳$share";
        }
    }
}

// Filter
$filter_dir  = $_GET['dir'] ?? '';
$filter_area = $_GET['area'] ?? '';

$where = "r.status IN ('open','filling') AND r.ride_time > NOW()";
$params = [];
if ($sgender !== 'female') { $where .= " AND r.girls_only=0"; }
if ($filter_dir)  { $where .= " AND r.direction=?";  $params[] = $filter_dir; }
if ($filter_area) { $where .= " AND (r.from_area=? OR r.to_area=?)"; $params[] = $filter_area; $params[] = $filter_area; }

$stmt = $pdo->prepare("SELECT r.*, s.name as poster_name, s.department, s.year FROM rides r JOIN students s ON r.poster_id=s.id WHERE $where ORDER BY r.ride_time ASC");
$stmt->execute($params);
$rides = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Rides — SEC Transport Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php" class="brand">🚗 SEC Transport <span>Hub</span></a>
    <nav>
        <a href="dashboard.php">Home</a>
        <a href="book_driver.php">Book Driver</a>
        <a href="post_ride.php">+ Post Ride</a>
        <a href="my_rides.php">My Rides</a>
        <a href="../logout.php">Logout</a>
    </nav>
</div>

<div class="container">
    <div class="section-header">
        <h2>🔍 Browse Ride Shares</h2>
        <a href="post_ride.php" class="btn btn-primary btn-sm">+ Post a Ride</a>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <!-- Filters -->
    <div class="card" style="padding:14px;margin-bottom:16px">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:1;min-width:140px">
                <label style="font-size:0.8rem;font-weight:500;color:#555;display:block;margin-bottom:4px">Direction</label>
                <select name="dir" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem">
                    <option value="">All Directions</option>
                    <option value="to_sec" <?= $filter_dir==='to_sec'?'selected':'' ?>>Home → SEC</option>
                    <option value="from_sec" <?= $filter_dir==='from_sec'?'selected':'' ?>>SEC → Home</option>
                </select>
            </div>
            <div style="flex:1;min-width:140px">
                <label style="font-size:0.8rem;font-weight:500;color:#555;display:block;margin-bottom:4px">Area</label>
                <select name="area" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;font-size:0.88rem">
                    <option value="">All Areas</option>
                    <option value="Zindabazar" <?= $filter_area==='Zindabazar'?'selected':'' ?>>Zindabazar</option>
                    <option value="Ambarkhana" <?= $filter_area==='Ambarkhana'?'selected':'' ?>>Ambarkhana</option>
                    <option value="Tilagor" <?= $filter_area==='Tilagor'?'selected':'' ?>>Tilagor</option>
                    <option value="Shibganj" <?= $filter_area==='Shibganj'?'selected':'' ?>>Shibganj</option>
                    <option value="Upashahar" <?= $filter_area==='Upashahar'?'selected':'' ?>>Upashahar</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="browse_rides.php" class="btn btn-outline btn-sm">Clear</a>
        </form>
    </div>

    <?php if (empty($rides)): ?>
        <div class="empty"><div class="icon">🚗</div><p>No rides available right now.<br><a href="post_ride.php" style="color:#1a73e8">Post the first ride!</a></p></div>
    <?php else: ?>
        <?php foreach ($rides as $r):
            $seats_left = $r['total_seats'] - $r['filled_seats'];
            $per_person = intval($r['fare'] / $r['filled_seats']);
        ?>
        <div class="ride-card <?= $r['girls_only'] ? 'girls-only' : '' ?>">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:8px">
                <div style="flex:1">
                    <div class="route">
                        <?= $r['direction'] === 'to_sec' ? '🏠 → 🏫' : '🏫 → 🏠' ?>
                        <?= htmlspecialchars($r['from_area']) ?> → <?= htmlspecialchars($r['to_area']) ?>
                        <?php if ($r['girls_only']): ?><span class="badge badge-pink" style="margin-left:6px">👩 Girls Only</span><?php endif; ?>
                    </div>
                    <div class="meta">
                        <span>⏰ <?= date('d M, h:i A', strtotime($r['ride_time'])) ?></span>
                        <span>💺 <?= $seats_left ?> seat<?= $seats_left!=1?'s':'' ?> left</span>
                        <span>👤 <?= htmlspecialchars($r['poster_name']) ?> (<?= $r['year'] ?>, <?= $r['department'] ?>)</span>
                    </div>
                </div>
                <div style="text-align:right">
                    <div class="cost">৳<?= $per_person ?><span style="font-size:0.75rem;font-weight:400;color:#888"> /person</span></div>
                    <div style="font-size:0.75rem;color:#aaa">Total: ৳<?= $r['fare'] ?></div>
                </div>
            </div>
            <div style="margin-top:10px;display:flex;gap:8px;align-items:center">
                <!-- Seat progress -->
                <div style="flex:1;background:#f0f0f0;border-radius:4px;height:6px">
                    <div style="background:#1a73e8;height:6px;border-radius:4px;width:<?= ($r['filled_seats']/$r['total_seats'])*100 ?>%"></div>
                </div>
                <span style="font-size:0.78rem;color:#888"><?= $r['filled_seats'] ?>/<?= $r['total_seats'] ?></span>
                <?php
                $already_joined = false;
                $chk2 = $pdo->prepare("SELECT id FROM ride_members WHERE ride_id=? AND student_id=?");
                $chk2->execute([$r['id'], $sid]);
                if ($chk2->fetch()) $already_joined = true;
                ?>
                <?php if ($already_joined): ?>
                    <span class="badge badge-success">Joined ✓</span>
                <?php elseif ($r['poster_id'] == $sid): ?>
                    <span class="badge badge-info">Your Ride</span>
                <?php else: ?>
                    <a href="?join=<?= $r['id'] ?><?= $filter_dir?"&dir=$filter_dir":'' ?><?= $filter_area?"&area=$filter_area":'' ?>" class="btn btn-success btn-sm" onclick="return confirm('Join this ride for ৳<?= $per_person ?>?')">Join Ride</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
