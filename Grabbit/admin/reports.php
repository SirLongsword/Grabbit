<?php
include 'admin_auth.php';
require_once __DIR__ . '/../config.php';

// Fetching data
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalListings = $pdo->query("SELECT COUNT(*) FROM listings")->fetchColumn();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Platform Analytics</title>
    <link rel="stylesheet" href="../CSS/admin.css" />
    <link rel="stylesheet" href="../CSS/global.css" />
</head>
<body>
<?php include '../header.php'; ?>
<div class="container">
    <h2>Platform Analytics</h2>
    <ul>
        <li><strong>Total Users:</strong> <?= $totalUsers ?></li>
        <li><strong>Total Listings:</strong> <?= $totalListings ?></li>
        <li><strong>Total Messages Sent:</strong> <?= $totalMessages ?></li>
    </ul>
</div>
<?php include '../footer.php'; ?>
<script src="../JS/admin.js" defer></script>
</body>
</html>
