<?php
require 'requires/conn.php';
require 'requires/header.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $user_id = $_SESSION['user_id'];
    $action_performed = "Updated user";

    // nese nje password esht dhene, te behet update
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $email, $password, $role, $id);
    } else {
        // update vetem username dhe email nese passwordi nuk esht dhene
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }

    if ($stmt->execute()) {
        // log the update action
        $action_details = "Admin ID: $user_id updated User ID: $id";
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $log_stmt->bind_param("iss", $user_id, $action_performed, $action_details);
        $log_stmt->execute();
        $log_stmt->close();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating user.";
    }
    $stmt->close();
}
?>
