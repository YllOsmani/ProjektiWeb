<?php
session_start();
require 'requires/conn.php';

// nese useri esht admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// fetch product image path before deletion
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "Product not found.";
    exit();
}

// fshirja e produktit ne databaze
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // fshirja e fotos nese ekziston
    if (!empty($product['image']) && file_exists($product['image'])) {
        unlink($product['image']);
    }

    // log product deletion
    $adminId = $_SESSION['user_id'];
    $log_action = "Deleted product";
    $action_details = "Admin ID: $adminId deleted Product ID: $id";

    $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
    $logStmt->bind_param("iss", $adminId, $log_action, $action_details);
    $logStmt->execute();
    $logStmt->close();

    // ridirektimi ne dashboard
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting product.";

   
    $log_action = "Failed to delete product";
    $action_details = "Admin ID: $adminId failed to delete Product ID: $id. Error: " . $stmt->error;

    $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
    $logStmt->bind_param("iss", $adminId, $log_action, $action_details);
    $logStmt->execute();
    $logStmt->close();
}

$stmt->close();
?>
