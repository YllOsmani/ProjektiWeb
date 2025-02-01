<?php
session_start();
require 'requires/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;


$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
   
    $action = "Deleted user with ID: $id";
    $adminId = $_SESSION['user_id']; 
    $timestamp = date("Y-m-d H:i:s");

    $logStmt = $conn->prepare("INSERT INTO log (user_id, action, timestamp) VALUES (?, ?, ?)");
    $logStmt->bind_param("iss", $adminId, $action, $timestamp);
    $logStmt->execute();
    $logStmt->close();

    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting user.";
}

$stmt->close();
?>
