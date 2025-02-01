<?php
session_start();
?>

<header>
    <div class="logo">
        <h1>Go Sport</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php" class="home-link">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="#" class="news-link">News</a></li>
            <li><a href="contactus.php">Contact Us</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="#"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
