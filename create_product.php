<?php
require 'requires/conn.php';
require 'requires/header.php';  

// nese useri esht admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $user_id = $_SESSION['user_id']; // admini qe shton produktin

    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $imageDir = "images/";
        $imagePath = $imageDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // insertimi ne tabelen e produkteve
    $stmt = $conn->prepare("INSERT INTO products (title, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $title, $description, $price, $imagePath);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id; // id e produktit qe esht shtuar

        // Log successful product creation
        $log_action = "Created a new product";
        $action_details = "Admin ID: $user_id created Product ID: $product_id (Title: $title, Price: $price)";
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $log_stmt->bind_param("iss", $user_id, $log_action, $action_details);
        $log_stmt->execute();
        $log_stmt->close();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error adding product.";

      
        $log_action = "Failed to create product";
        $action_details = "Admin ID: $user_id failed to create product (Title: $title). Error: " . $stmt->error;
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $log_stmt->bind_param("iss", $user_id, $log_action, $action_details);
        $log_stmt->execute();
        $log_stmt->close();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <link rel="stylesheet" href="b.css"> 
    <link rel="stylesheet" href="styles/create_product.css"> 
</head>
<body>
    <div class="container">
        <h1>Create New Product</h1>
        <form action="create_product.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter product title" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Enter product description" required></textarea>
            
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" placeholder="Enter product price" required>
            
            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image" required>
            
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
