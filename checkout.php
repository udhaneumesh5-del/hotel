<?php
include 'db.php';
requireLogin();

// Get bookings that are checked-in
$stmt = $pdo->query("
    SELECT b.*, c.name as customer_name, r.room_number, r.price
    FROM bookings b 
    JOIN customers c ON b.customer_id = c.id 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.status = 'Checked-in'
    ORDER BY b.check_out
");
$bookings = $stmt->fetchAll();

if (isset($_GET['checkout_id'])) {
    $booking_id = $_GET['checkout_id'];
    
    try {
        // Get booking details
        $booking_stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $booking_stmt->execute([$booking_id]);
        $booking = $booking_stmt->fetch();
        
        // Update booking status
        $update_stmt = $pdo->prepare("UPDATE bookings SET status = 'Checked-out' WHERE id = ?");
        $update_stmt->execute([$booking_id]);
        
        // Update room status to available
        $room_stmt = $pdo->prepare("UPDATE rooms SET status = 'Available' WHERE id = ?");
        $room_stmt->execute([$booking['room_id']]);
        
        // Create payment record
        $payment_stmt = $pdo->prepare("INSERT INTO payments (booking_id, amount) VALUES (?, ?)");
        $payment_stmt->execute([$booking_id, $booking['total_amount']]);
        
        $success = "Check-out completed successfully!";
        header("Location: checkout.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Error during check-out: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-out - Hotel Management</title>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Customer Check-out</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Check-out completed successfully!</div>
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
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No pending check-outs</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>#<?php echo $booking['id']; ?></td>
                        <td><?php echo $booking['customer_name']; ?></td>
                        <td>Room <?php echo $booking['room_number']; ?></td>
                        <td><?php echo $booking['check_in']; ?></td>
                        <td><?php echo $booking['check_out']; ?></td>
                        <td>₹<?php echo $booking['total_amount']; ?></td>
                        <td>
                            <a href="checkout.php?checkout_id=<?php echo $booking['id']; ?>" class="btn">
                                Check-out
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