<?php
session_start();
require 'requires/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// fshirja e ni produkti
if (isset($_GET['delete_product_id'])) {
    $product_id = $_GET['delete_product_id'];

    // fshirja e produktit prej databazes
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {

        // vendosja ne tabelen 'logs'
        $user_id = $_SESSION['user_id'];
        $action = "Deleted product";
        $action_details = "Product ID: " . $product_id;
        
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details) VALUES (?, ?, ?)");
        $log_stmt->bind_param("iss", $user_id, $action, $action_details);
        $log_stmt->execute();
        $log_stmt->close();

        //kthimi ne dashboard 
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting product.";
    }
    $stmt->close();
}
?>
