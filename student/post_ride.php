<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';
requireStudent();

$sid = $_SESSION['student_id'];
$sgender = $_SESSION['student_gender'];
$success = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direction  = $_POST['direction'];
    $from_area  = trim($_POST['from_area']);
    $to_area    = trim($_POST['to_area']);
    $ride_time  = $_POST['ride_time'];
    $seats      = intval($_POST['total_seats']);
    $fare       = intval($_POST['fare']);
    $girls_only = isset($_POST['girls_only']) ? 1 : 0;

    // Girls only only allowed if poster is female
    if ($girls_only && $sgender !== 'female') $girls_only = 0;

    $stmt = $pdo->prepare("INSERT INTO rides (poster_id, direction, from_area, to_area, ride_time, total_seats, filled_seats, fare, girls_only, status) VALUES (?,?,?,?,?,?,1,?,?,?)");
    $stmt->execute([$sid, $direction, $from_area, $to_area, $ride_time, $seats, $fare, $girls_only, $seats > 1 ? 'open' : 'full']);

    $ride_id = $pdo->lastInsertId();
    // Add poster as first member
    $pdo->prepare("INSERT INTO ride_members (ride_id, student_id, cost_share) VALUES (?,?,?)")->execute([$ride_id, $sid, $fare]);

    $success = "Ride posted successfully! Others can now join your ride.";
}

$fares = $pdo->query("SELECT * FROM fares")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Ride Share — SEC Transport Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php" class="brand">🚗 SEC Transport <span>Hub</span></a>
    <nav>
        <a href="dashboard.php">Home</a>
        <a href="book_driver.php">Book Driver</a>
        <a href="browse_rides.php">Browse Rides</a>
        <a href="my_rides.php">My Rides</a>
        <a href="../logout.php">Logout</a>
    </nav>
</div>

<div class="container">
    <h2 style="margin-bottom:6px">👥 Post a Ride Share</h2>
    <p style="color:#666;margin-bottom:20px;font-size:0.9rem">Find batchmates going your way and split the fare</p>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?> <a href="browse_rides.php">View all rides</a></div><?php endif; ?>

    <div class="card" style="max-width:560px;margin:0 auto">
        <form method="POST">
            <div class="form-group">
                <label>Direction</label>
                <select name="direction" required onchange="updateAreas(this.value)">
                    <option value="to_sec">🏠 → 🏫 Home to SEC (Morning trip)</option>
                    <option value="from_sec">🏫 → 🏠 SEC to Home (Afternoon trip)</option>
                </select>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>From</label>
                    <select name="from_area" id="from_area" required>
                        <option value="">Select</option>
                        <?php foreach ($fares as $f): ?>
                        <option value="<?= $f['area'] ?>"><?= $f['area'] ?></option>
                        <?php endforeach; ?>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>To</label>
                    <select name="to_area" id="to_area" required>
                        <option value="SEC">SEC (Sylhet Engineering College)</option>
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Date & Time</label>
                    <input type="datetime-local" name="ride_time" required>
                </div>
                <div class="form-group">
                    <label>Total Seats (including you)</label>
                    <select name="total_seats" required>
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Estimated Total Fare (৳)</label>
                <select name="fare" id="fare_select" required>
                    <option value="">Select area first</option>
                    <?php foreach ($fares as $f): ?>
                    <option value="<?= $f['fare'] ?>"><?= $f['area'] ?> — ৳<?= $f['fare'] ?></option>
                    <?php endforeach; ?>
                    <option value="100">Other — ৳100</option>
                </select>
                <small style="color:#888;font-size:0.8rem">Cost will be split automatically among all members</small>
            </div>

            <?php if ($sgender === 'female'): ?>
            <div style="background:#fce4ec;border-radius:8px;padding:12px;margin-bottom:14px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin:0">
                    <input type="checkbox" name="girls_only">
                    <span style="font-size:0.88rem;color:#880e4f"><strong>Girls Only Ride</strong><br>
                    <span style="font-size:0.8rem">Only female students can see and join this ride</span></span>
                </label>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary btn-block">Post Ride Share</button>
        </form>
    </div>
</div>

<script>
const areas = [<?php foreach($fares as $f): ?>"<?= $f['area'] ?>",<?php endforeach; ?>"Other"];
const fares  = {<?php foreach($fares as $f): ?>"<?= $f['area'] ?>":<?= $f['fare'] ?>,<?php endforeach; ?>"Other":100};

function updateAreas(dir) {
    const from = document.getElementById('from_area');
    const to   = document.getElementById('to_area');
    from.innerHTML = '<option value="">Select</option>';
    to.innerHTML   = '';

    if (dir === 'to_sec') {
        areas.forEach(a => from.innerHTML += `<option value="${a}">${a}</option>`);
        to.innerHTML = '<option value="SEC">SEC (Sylhet Engineering College)</option>';
    } else {
        from.innerHTML = '<option value="SEC">SEC (Sylhet Engineering College)</option>';
        areas.forEach(a => to.innerHTML += `<option value="${a}">${a}</option>`);
    }
}
</script>
</body>
</html>
