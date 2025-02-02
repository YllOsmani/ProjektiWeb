<?php
require 'requires/conn.php';
require 'requires/header.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// fetch detajet e produktit
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "Product not found!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $imagePath = $product['image']; // prej default ne nje imazh qe ekziston

    // imazh i ri nese esht dhene
    if (!empty($_FILES['image']['name'])) {
        $imageDir = "images/";
        $newImagePath = $imageDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)) {
            // fshirja e fotos se vjeter nese vendos nje foto e re
            if (!empty($product['image']) && file_exists($product['image'])) {
                unlink($product['image']);
            }
            $imagePath = $newImagePath; 
        }
    }

    // update produktit ne databaze
    $stmt = $conn->prepare("UPDATE products SET title=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $title, $description, $price, $imagePath, $id);

    if ($stmt->execute()) {
        // log product update
        $adminId = $_SESSION['user_id'];
        $log_action = "Updated product";
        $action_details = "Admin ID: $adminId updated Product ID: $id (Title: $title, Price: $price)";

        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $logStmt->bind_param("iss", $adminId, $log_action, $action_details);
        $logStmt->execute();
        $logStmt->close();

        // redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating product.";

        // log failed update
        $log_action = "Failed to update product";
        $action_details = "Admin ID: $adminId failed to update Product ID: $id. Error: " . $stmt->error;

        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $logStmt->bind_param("iss", $adminId, $log_action, $action_details);
        $logStmt->execute();
        $logStmt->close();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="b.css"> 
    <link rel="stylesheet" href="styles/update_product.css"> 
</head>
<body>
    <div class="container">
        <h1>Update Product</h1>
        <form action="update_product.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required><br><br>
            
            <label>Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>
            
            <label>Price:</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required><br><br>
            
            <label>Current Image:</label><br>
            <img src="<?php echo $product['image']; ?>" width="150" class="product-img"><br><br>
            
            <label>Change Image:</label>
            <input type="file" name="image"><br><br>
            
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
