<?php
session_start();
require 'requires/conn.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;


$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();


$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  
    if (file_exists($product['image'])) {
        unlink($product['image']);
    }
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting product.";
}
$stmt->close();
?>
