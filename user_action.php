<?php
session_start();
require 'requires/conn.php';

// fshirja e ni useri
if (isset($_GET['delete_user_id'])) {
    $user_id_to_delete = $_GET['delete_user_id'];

    
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id_to_delete);
    if ($stmt->execute()) {

        
        $user_id = $_SESSION['user_id']; 
        $action = "Deleted user";
        $action_details = "User ID: " . $user_id_to_delete;

        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details) VALUES (?, ?, ?)");
        $log_stmt->bind_param("iss", $user_id, $action, $action_details);
        $log_stmt->execute();
        $log_stmt->close();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
    $stmt->close();
}
