<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .header { background: #2c3e50; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .nav a { color: white; text-decoration: none; margin: 0 10px; padding: 5px 10px; }
        .nav a:hover { background: #34495e; border-radius: 3px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h3 { color: #2c3e50; margin-bottom: 10px; }
        .card .number { font-size: 2em; font-weight: bold; color: #3498db; }
        .btn { background: #3498db; color: white; padding: 10px 15px; border: none; border-radius: 3px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hotel Management System</h1>
        <div class="nav">
            <a href="index.php">Dashboard</a>
            <a href="view_rooms.php">Rooms</a>
            <a href="view_bookings.php">Bookings</a>
            <a href="add_customer.php">Add Customer</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Dashboard</h2>
        
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Rooms</h3>
                <div class="number">
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM rooms");
                    echo $stmt->fetchColumn();
                    ?>
                </div>
            </div>
            
            <div class="card">
                <h3>Available Rooms</h3>
                <div class="number">
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'Available'");
                    echo $stmt->fetchColumn();
                    ?>
                </div>
            </div>
            
            <div class="card">
                <h3>Current Bookings</h3>
                <div class="number">
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status IN ('Booked', 'Checked-in')");
                    echo $stmt->fetchColumn();
                    ?>
                </div>
            </div>
            
            <div class="card">
                <h3>Today's Check-ins</h3>
                <div class="number">
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE check_in = CURDATE()");
                    echo $stmt->fetchColumn();
                    ?>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <a href="booking.php" class="btn">New Booking</a>
            <a href="add_room.php" class="btn">Add Room</a>
            <a href="checkin.php" class="btn">Check-in</a>
            <a href="checkout.php" class="btn">Check-out</a>
        </div>
    </div>
</body>
</html>