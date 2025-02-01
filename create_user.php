<?php
require 'requires/conn.php';
require 'requires/header.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $admin_id = $_SESSION['user_id'];  

    // insertimi i userit ne tabelen e users
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
      
        $log_action = "Admin (ID: $admin_id) created a new user (Email: $email, Role: $role)";
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, timestamp) VALUES (?, ?, NOW())");
        $log_stmt->bind_param("is", $admin_id, $log_action);
        $log_stmt->execute();
        $log_stmt->close();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error adding user.";

        
        $log_action = "Admin (ID: $admin_id) failed to create user (Email: $email)";
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, timestamp) VALUES (?, ?, NOW())");
        $log_stmt->bind_param("is", $admin_id, $log_action);
        $log_stmt->execute();
        $log_stmt->close();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="b.css">
    <link rel="stylesheet" href="styles/create_user.css">
</head>
<body>
    <div class="container">
        <h1>Create New User</h1>
        <form action="create_user.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required><br><br>

            <label>Email:</label>
            <input type="email" name="email" required><br><br>

            <label>Password:</label>
            <input type="password" name="password" required><br><br>

            <label>Role:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <button type="submit" class="btn btn-primary">Add User</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
