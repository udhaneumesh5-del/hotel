<?php
include 'db.php';
requireLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umesh Udhane - Hotel Management</title>
    <style>
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #34495e; color: white; }
        tr:hover { background: #f5f5f5; }
        .status-booked { color: #f39c12; font-weight: bold; }
        .status-checked-in { color: #3498db; font-weight: bold; }
        .status-checked-out { color: #27ae60; font-weight: bold; }
        .status-cancelled { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>All Bookings</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("
                    SELECT b.*, c.name as customer_name, r.room_number 
                    FROM bookings b 
                    JOIN customers c ON b.customer_id = c.id 
                    JOIN rooms r ON b.room_id = r.id 
                    ORDER BY b.created_at DESC
                ");
                
                while ($booking = $stmt->fetch()):
                ?>
                <tr>
                    <td>#<?php echo $booking['id']; ?></td>
                    <td><?php echo $booking['customer_name']; ?></td>
                    <td>Room <?php echo $booking['room_number']; ?></td>
                    <td><?php echo $booking['check_in']; ?></td>
                    <td><?php echo $booking['check_out']; ?></td>
                    <td>₹<?php echo $booking['total_amount']; ?></td>
                    <td class="status-<?php echo strtolower($booking['status']); ?>">
                        <?php echo $booking['status']; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>