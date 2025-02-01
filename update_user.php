<?php
require 'requires/conn.php';
require 'requires/header.php';

// nese useri esht logged in dhe esht admin
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
    $action_performed = "update user";  // logged in si "update user"
    
    // nese passwordi esht japur, te behet update
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $email, $password, $role, $id);
    } else {
        // nese passwordi nuk esht jep, te behet update email,username
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }

    if ($stmt->execute()) {
        $user_id = $_SESSION['user_id'];  
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, target_user_id, timestamp) VALUES (?, ?, ?, NOW())");
        $log_stmt->bind_param("isi", $user_id, $action_performed, $id);
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="b.css">
    <link rel="stylesheet" href="styles/create_user.css">
</head>
<body>
    <div class="container">
        <h1>Update User</h1>
        <form action="update_user.php?id=<?php echo $id; ?>" method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

            <label>Password (leave blank to keep current):</label>
            <input type="password" name="password"><br><br>

            <label>Role:</label>
            <select name="role" required>
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select><br><br>

            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
