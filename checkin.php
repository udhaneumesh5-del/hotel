<?php
include 'db.php';
requireLogin();

// Get bookings that are booked for today or future
$stmt = $pdo->query("
    SELECT b.*, c.name as customer_name, r.room_number 
    FROM bookings b 
    JOIN customers c ON b.customer_id = c.id 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.status = 'Booked' AND b.check_in <= CURDATE()
    ORDER BY b.check_in
");
$bookings = $stmt->fetchAll();

if (isset($_GET['checkin_id'])) {
    $booking_id = $_GET['checkin_id'];
    
    try {
        $update_stmt = $pdo->prepare("UPDATE bookings SET status = 'Checked-in' WHERE id = ?");
        $update_stmt->execute([$booking_id]);
        
        $success = "Customer checked-in successfully!";
        header("Location: checkin.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Error during check-in: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in - Hotel Management</title>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Customer Check-in</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Check-in completed successfully!</div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No pending check-ins</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>#<?php echo $booking['id']; ?></td>
                        <td><?php echo $booking['customer_name']; ?></td>
                        <td>Room <?php echo $booking['room_number']; ?></td>
                        <td><?php echo $booking['check_in']; ?></td>
                        <td><?php echo $booking['check_out']; ?></td>
                        <td>
                            <a href="checkin.php?checkin_id=<?php echo $booking['id']; ?>" class="btn">
                                Check-in
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>