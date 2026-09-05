<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireStudent();

$sid = $_SESSION['student_id'];
$success = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $driver_id     = $_POST['driver_id'];
    $direction     = $_POST['direction'];
    $pickup_area   = trim($_POST['pickup_area']);
    $meeting_point = $_POST['meeting_point'];
    $pickup_time   = $_POST['pickup_time'];
    $is_shared     = isset($_POST['is_shared']) ? 1 : 0;

    // Get fare
    $fare_row = $pdo->prepare("SELECT fare FROM fares WHERE area=? LIMIT 1");
    $fare_row->execute([$pickup_area]);
    $fare = $fare_row->fetch();
    $fare_amt = $fare ? $fare['fare'] : 80;

    $stmt = $pdo->prepare("INSERT INTO bookings (student_id, driver_id, direction, pickup_area, meeting_point, pickup_time, fare, is_shared) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$sid, $driver_id, $direction, $pickup_area, $meeting_point, $pickup_time, $fare_amt, $is_shared]);
    $booking_id = $pdo->lastInsertId();

    // Notify driver
    notify($pdo, 'driver', $driver_id, "New booking request from a student going to $pickup_area at $pickup_time.");

    // If shared, create a ride share entry so others can join
    if ($is_shared) {
        $student = $pdo->prepare("SELECT area FROM students WHERE id=?");
        $student->execute([$sid]);
        $st = $student->fetch();
        $from = $direction === 'to_sec' ? $st['area'] : 'SEC';
        $to   = $direction === 'to_sec' ? 'SEC' : $pickup_area;
        $ride = $pdo->prepare("INSERT INTO rides (poster_id, driver_id, direction, from_area, to_area, ride_time, total_seats, filled_seats, fare, status) VALUES (?,?,?,?,?,?,3,1,?,?)");
        $ride->execute([$sid, $driver_id, $direction, $from, $to, $pickup_time, $fare_amt, 'filling']);
        $ride_id = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO ride_members (ride_id, student_id, cost_share) VALUES (?,?,?)")->execute([$ride_id, $sid, $fare_amt]);
    }

    $success = "Booking sent! Waiting for driver to accept.";
}

// Get online approved drivers
$drivers = $pdo->query("SELECT * FROM drivers WHERE is_online=1 AND status='approved' ORDER BY rating DESC")->fetchAll();
$fares   = $pdo->query("SELECT * FROM fares")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Driver — SEC Transport Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php" class="brand">🚗 SEC Transport <span>Hub</span></a>
    <nav>
        <a href="dashboard.php">Home</a>
        <a href="browse_rides.php">Browse Rides</a>
        <a href="my_rides.php">My Rides</a>
        <a href="../logout.php">Logout</a>
    </nav>
</div>

<div class="container">
    <h2 style="margin-bottom:20px">🚕 Book a Driver</h2>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <?php if (empty($drivers)): ?>
        <div class="empty"><div class="icon">😔</div><p>No drivers are online right now.<br>Please check again later.</p></div>
    <?php else: ?>

    <div class="grid-2">
        <!-- Driver List -->
        <div>
            <h3 style="margin-bottom:12px;font-size:0.95rem;color:#666">Select a Driver</h3>
            <?php foreach ($drivers as $d): ?>
            <div class="driver-card" style="cursor:pointer;border:2px solid transparent" id="dcard_<?= $d['id'] ?>" onclick="selectDriver(<?= $d['id'] ?>, '<?= addslashes($d['name']) ?>', '<?= $d['vehicle_type'] ?>')">
                <div class="avatar"><?= strtoupper(substr($d['name'],0,1)) ?></div>
                <div class="info">
                    <div class="name">
                        <span class="online-dot"></span><?= htmlspecialchars($d['name']) ?>
                        <span class="badge badge-success" style="margin-left:6px">✓ Verified</span>
                    </div>
                    <div class="details">
                        <?= strtoupper($d['vehicle_type']) ?> &bull; <?= htmlspecialchars($d['plate']) ?>
                    </div>
                    <div class="stars">★ <?= number_format($d['rating'],1) ?> &bull; <?= $d['total_rides'] ?> rides</div>
                </div>
                <input type="radio" name="driver_select" value="<?= $d['id'] ?>" style="display:none">
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Booking Form -->
        <div>
            <div class="card">
                <div class="card-title">Booking Details</div>
                <form method="POST" id="bookingForm">
                    <input type="hidden" name="driver_id" id="sel_driver_id" required>
                    <div id="sel_driver_info" style="background:#f0f7ff;border-radius:8px;padding:10px;margin-bottom:14px;font-size:0.85rem;color:#1a73e8;display:none">
                        Selected: <strong id="sel_driver_name"></strong>
                    </div>

                    <div class="form-group">
                        <label>Direction</label>
                        <select name="direction" required>
                            <option value="to_sec">🏠 → 🏫 Home to SEC (Morning)</option>
                            <option value="from_sec">🏫 → 🏠 SEC to Home (Afternoon)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Your Area</label>
                        <select name="pickup_area" id="pickup_area" required onchange="updateFare()">
                            <option value="">Select your area</option>
                            <?php foreach ($fares as $f): ?>
                            <option value="<?= $f['area'] ?>" data-fare="<?= $f['fare'] ?>"><?= $f['area'] ?></option>
                            <?php endforeach; ?>
                            <option value="Other" data-fare="100">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Meeting Point at SEC</label>
                        <select name="meeting_point" required>
                            <option value="Main Gate">Main Gate</option>
                            <option value="Side Gate">Side Gate</option>
                            <option value="Parking Area">Parking Area</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pickup Time</label>
                        <input type="datetime-local" name="pickup_time" required>
                    </div>

                    <div style="background:#f8f9fa;border-radius:8px;padding:12px;margin-bottom:14px">
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:0.85rem;color:#666">Estimated Fare</span>
                            <span style="font-size:1.2rem;font-weight:700;color:#1a73e8" id="fare_display">৳ —</span>
                        </div>
                    </div>

                    <div style="background:#fff3cd;border-radius:8px;padding:12px;margin-bottom:14px">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin:0">
                            <input type="checkbox" name="is_shared" id="is_shared">
                            <span style="font-size:0.88rem"><strong>Open for ride sharing</strong><br>
                            <span style="color:#666;font-size:0.8rem">Others can join and split the cost with you</span></span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Send Booking Request</button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function selectDriver(id, name, type) {
    document.querySelectorAll('.driver-card').forEach(c => c.style.border = '2px solid transparent');
    document.getElementById('dcard_' + id).style.border = '2px solid #1a73e8';
    document.getElementById('sel_driver_id').value = id;
    document.getElementById('sel_driver_name').textContent = name + ' (' + type.toUpperCase() + ')';
    document.getElementById('sel_driver_info').style.display = 'block';
}
function updateFare() {
    const sel = document.getElementById('pickup_area');
    const opt = sel.options[sel.selectedIndex];
    const fare = opt.getAttribute('data-fare');
    document.getElementById('fare_display').textContent = fare ? '৳' + fare : '৳ —';
}
</script>
</body>
</html>
