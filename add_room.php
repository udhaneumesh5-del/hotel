<?php
include 'db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, room_type, price, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$room_number, $room_type, $price, $description]);
        
        $success = "Room added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding room: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room - Hotel Management</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Add New Room</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Room Number:</label>
                <input type="text" name="room_number" required>
            </div>
            
            <div class="form-group">
                <label>Room Type:</label>
                <select name="room_type" required>
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Suite">Suite</option>
                    <option value="Deluxe">Deluxe</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price per Night:</label>
                <input type="number" name="price" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn">Add Room</button>
        </form>
    </div>
</body>
</html>