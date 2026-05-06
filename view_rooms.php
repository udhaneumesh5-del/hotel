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
        .rooms-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
        .room-card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-left: 4px solid #3498db; }
        .room-card.available { border-left-color: #2ecc71; }
        .room-card.occupied { border-left-color: #e74c3c; }
        .room-card.maintenance { border-left-color: #f39c12; }
        .room-number { font-size: 1.2em; font-weight: bold; color: #2c3e50; }
        .room-type { color: #7f8c8d; }
        .room-price { font-weight: bold; color: #27ae60; }
        .room-status { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 0.8em; }
        .status-available { background: #d4edda; color: #155724; }
        .status-occupied { background: #f8d7da; color: #721c24; }
        .status-maintenance { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>All Rooms</h2>
        
        <div class="rooms-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_number");
            while ($room = $stmt->fetch()):
                $status_class = strtolower($room['status']);
            ?>
            <div class="room-card <?php echo $status_class; ?>">
                <div class="room-number">Room <?php echo $room['room_number']; ?></div>
                <div class="room-type"><?php echo $room['room_type']; ?></div>
                <div class="room-price">₹<?php echo $room['price']; ?>/night</div>
                <div class="room-status status-<?php echo $status_class; ?>">
                    <?php echo $room['status']; ?>
                </div>
                <?php if ($room['description']): ?>
                    <div style="margin-top: 10px; color: #666; font-size: 0.9em;">
                        <?php echo $room['description']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>