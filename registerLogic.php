<?php
require 'requires/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // nese te gjitha fields permbushen
    if (empty($username) || empty($email) || empty($password) || empty($password2)) {
        die("All fields are required.");
    }

    // validimi i formatit te email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // nese passwordat perputhen
    if ($password !== $password2) {
        die("Passwords do not match.");
    }

    // hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // nese username apo email veq se ekziston
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();

        // log failed registration attempt
        $action = "Failed registration attempt: Username '$username' or Email '$email' already exists.";
        $action_details = "User tried to register with an existing email or username.";
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $logStmt->bind_param("iss", $userId = 0, $action, $action_details);
        $logStmt->execute();
        $logStmt->close();

        die("Username or email already taken.");
    }
    $stmt->close();

    // insertimi i userit te ri ne databaze
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        $newUserId = $stmt->insert_id;

        // log successful registration
        $action = "User registered successfully";
        $action_details = "User ID: $newUserId, Username: $username, Email: $email";
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $logStmt->bind_param("iss", $newUserId, $action, $action_details);
        $logStmt->execute();
        $logStmt->close();

        header("Location: login.php");
        exit();
    } else {
        // log error gjate regjistrimit
        $action = "Error during registration";
        $action_details = "Username: $username, Email: $email. Error: " . $stmt->error;
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
        $logStmt->bind_param("iss", $userId = 0, $action, $action_details);
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
