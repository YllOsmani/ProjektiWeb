<?php
session_start();
require 'requires/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die("Email and password are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // fetch user data once
    $user = $result->fetch_assoc();

    if ($user) {
        $user_id = $user['id'];
        $action = "Successful login attempt";
    } else {
        $user_id = null;
        $action = "Failed login attempt";
    }

    // log attempt
    $action_details = "Login attempt for email: " . $email;
    $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
    $logStmt->bind_param("iss", $user_id, $action, $action_details);
    $logStmt->execute();
    $logStmt->close();

    // check login credentials
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
