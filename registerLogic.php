<?php
require 'requires/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // jane permbushur kerkesat
    if (empty($username) || empty($email) || empty($password) || empty($password2)) {
        die("All fields are required.");
    }

    // validimi i email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // nese passwordat pershtaten
    if ($password !== $password2) {
        die("Passwords do not match.");
    }

    
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // nese username apo email vetem se ekziston
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();
        $action = "Failed registration attempt for username: $username or email: $email (already taken)";
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, timestamp) VALUES (?, ?, NOW())");
        $logStmt->bind_param("is", $userId = 0, $action);
        $logStmt->execute();
        $logStmt->close();

        die("Username or email already taken.");
    }

    $stmt->close();

    // insertimi i user-it te ri ne databaze
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
     
        $newUserId = $stmt->insert_id;
        $action = "User registered successfully with ID: $newUserId, Username: $username, Email: $email";
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, timestamp) VALUES (?, ?, NOW())");
        $logStmt->bind_param("is", $newUserId, $action);
        $logStmt->execute();
        $logStmt->close();

        header("Location: login.php");
        exit();
    } else {
        
        $action = "Error during registration for username: $username, Email: $email. Error: " . $stmt->error;
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, timestamp) VALUES (?, ?, NOW())");
        $logStmt->bind_param("is", $userId = 0, $action);
        $logStmt->execute();
        $logStmt->close();

        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register.php");
    exit();
}
?>
