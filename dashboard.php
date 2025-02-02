<?php
require 'requires/conn.php';
require 'requires/header.php';

// Check if user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// fetch users
$userQuery = "SELECT * FROM users LIMIT $limit OFFSET $offset";
$userResult = $conn->query($userQuery);

// fetch products
$productQuery = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$productResult = $conn->query($productQuery);

// fetch logs
$logQuery = "SELECT logs.*, users.username FROM logs JOIN users ON logs.user_id = users.id ORDER BY timestamp DESC LIMIT $limit OFFSET $offset";
$logResult = $conn->query($logQuery);

// fetch contact messages
$contactQuery = "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$contactResult = $conn->query($contactQuery);


$userCountQuery = "SELECT COUNT(*) AS total FROM users";
$totalUsers = $conn->query($userCountQuery)->fetch_assoc()['total'];
$totalUserPages = ceil($totalUsers / $limit);

$productCountQuery = "SELECT COUNT(*) AS total FROM products";
$totalProducts = $conn->query($productCountQuery)->fetch_assoc()['total'];
$totalProductPages = ceil($totalProducts / $limit);

$logCountQuery = "SELECT COUNT(*) AS total FROM logs";
$totalLogs = $conn->query($logCountQuery)->fetch_assoc()['total'];
$totalLogPages = ceil($totalLogs / $limit);

$contactCountQuery = "SELECT COUNT(*) AS total FROM contact_messages";
$totalMessages = $conn->query($contactCountQuery)->fetch_assoc()['total'];
$totalMessagePages = ceil($totalMessages / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="b.css">
</head>
<body>

    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Logs Section -->
        <div class="section">
            <h2>Activity Logs</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($log = $logResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $log['id']; ?></td>
                            <td><?php echo htmlspecialchars($log['username']); ?></td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo htmlspecialchars($log['action_details']); ?></td>
                            <td><?php echo $log['timestamp']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalLogPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Users Section -->
        <div class="section">
            <h2>Users</h2>
            <a href="create_user.php" class="btn">Create User</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $userResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo $user['created_at']; ?></td>
                            <td>
                                <a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalUserPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Products Section -->
        <div class="section">
            <h2>Products</h2>
            <a href="create_product.php" class="btn">Create Product</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $productResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['title']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product" class="product-img"></td>
                            <td>
                                <a href="update_product.php?id=<?php echo $product['id']; ?>" class="btn-edit">Edit</a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalProductPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Contact Messages Section -->
        <div class="section">
            <h2>Contact Messages</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($message = $contactResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $message['id']; ?></td>
                            <td><?php echo htmlspecialchars($message['name']); ?></td>
                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                            <td><?php echo htmlspecialchars($message['message']); ?></td>
                            <td><?php echo $message['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalMessagePages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>

    </div>

</body>
</html>
