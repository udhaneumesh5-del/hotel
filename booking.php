<?php
include 'db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    
    try {
        // Calculate total amount
        $room_stmt = $pdo->prepare("SELECT price FROM rooms WHERE id = ?");
        $room_stmt->execute([$room_id]);
        $room = $room_stmt->fetch();
        
        $days = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
        $total_amount = $days * $room['price'];
        
        // Create booking
        $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, room_id, check_in, check_out, total_amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$customer_id, $room_id, $check_in, $check_out, $total_amount]);
        
        // Update room status
        $update_room = $pdo->prepare("UPDATE rooms SET status = 'Occupied' WHERE id = ?");
        $update_room->execute([$room_id]);
        
        $success = "Room booked successfully! Total amount: ₹" . $total_amount;
    } catch (PDOException $e) {
        $error = "Error creating booking: " . $e->getMessage();
    }
}

// Get available rooms
$available_rooms = $pdo->query("SELECT * FROM rooms WHERE status = 'Available'")->fetchAll();

// Get customers
$customers = $pdo->query("SELECT * FROM customers ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room - Hotel Management</title>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Book a Room</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Customer:</label>
                <select name="customer_id" required>
                    <option value="">Select Customer</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>">
                            <?php echo $customer['name'] . " (" . $customer['phone'] . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small><a href="add_customer.php">Add New Customer</a></small>
            </div>
            
            <div class="form-group">
                <label>Room:</label>
                <select name="room_id" required>
                    <option value="">Select Room</option>
                    <?php foreach ($available_rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>">
                            Room <?php echo $room['room_number']; ?> - 
                            <?php echo $room['room_type']; ?> - 
                            ₹<?php echo $room['price']; ?>/night
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Check-in Date:</label>
                <input type="date" name="check_in" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group">
                <label>Check-out Date:</label>
                <input type="date" name="check_out" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <button type="submit" class="btn">Book Room</button>
        </form>
    </div>
</body>
</html>