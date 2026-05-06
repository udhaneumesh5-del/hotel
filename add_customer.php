<?php
include 'db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $id_proof = $_POST['id_proof'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone, address, id_proof) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $address, $id_proof]);
        
        $success = "Customer added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding customer: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer - Hotel Management</title>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Add New Customer</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email">
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" required>
            </div>
            
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>ID Proof:</label>
                <input type="text" name="id_proof" placeholder="Aadhar, Passport, etc.">
            </div>
            
            <button type="submit" class="btn">Add Customer</button>
        </form>
    </div>
</body>
</html>