<?php
session_start();
require 'requires/conn.php';

// per tu siguruar nese useri esht admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// get user ID to delete
$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // log deletion
        $action = "Deleted user with ID: $id";
        $action_details = "Admin ID: " . $_SESSION['user_id'] . " deleted User ID: $id";
        $adminId = $_SESSION['user_id'];

        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $logStmt->bind_param("iss", $adminId, $action, $action_details);
        $logStmt->execute();
        $logStmt->close();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
    $stmt->close();
} else {
    echo "Invalid user ID.";
}
$conn->close();
?>
