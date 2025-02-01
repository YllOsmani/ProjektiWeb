<?php
session_start();
require 'requires/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT logs.*, users.username FROM logs JOIN users ON logs.user_id = users.id ORDER BY logs.timestamp DESC");
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>User Actions Log</h1>";
echo "<table border='1'>
    <tr>
        <th>Timestamp</th>
        <th>User</th>
        <th>Action</th>
        <th>Details</th>
    </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>" . $row['timestamp'] . "</td>
        <td>" . htmlspecialchars($row['username']) . "</td>
        <td>" . htmlspecialchars($row['action']) . "</td>
        <td>" . htmlspecialchars($row['action_details']) . "</td>
    </tr>";
}

echo "</table>";

$stmt->close();
?>
