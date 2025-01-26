<?php
require 'requires/conn.php';
require 'requires/header.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}


$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;


$userQuery = "SELECT * FROM users LIMIT $limit OFFSET $offset";
$userResult = $conn->query($userQuery);


$productQuery = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$productResult = $conn->query($productQuery);


$userCountQuery = "SELECT COUNT(*) AS total FROM users";
$totalUsers = $conn->query($userCountQuery)->fetch_assoc()['total'];
$totalUserPages = ceil($totalUsers / $limit);

$productCountQuery = "SELECT COUNT(*) AS total FROM products";
$totalProducts = $conn->query($productCountQuery)->fetch_assoc()['total'];
$totalProductPages = ceil($totalProducts / $limit);
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
    </div>

</body>
</html>
