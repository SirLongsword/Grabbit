<?php
include 'admin_auth.php';
require_once __DIR__ . '/../config.php';


$listings = $pdo->query("SELECT * FROM listings")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Listings</title>
    <link rel="stylesheet" href="../CSS/admin.css" />
    <link rel="stylesheet" href="../CSS/global.css" />
</head>
<body>
<?php include '../header.php'; ?>
<div class="container">
    <h2>All Listings</h2>
    <table>
        <tr><th>Title</th><th>Posted By (User ID)</th><th>Actions</th></tr>
        <?php foreach ($listings as $listing): ?>
            <tr>
                <td><?= htmlspecialchars($listing['title']) ?></td>
                <td><?= htmlspecialchars($listing['user_id']) ?></td>
                <td>
                    <a href="admin_delete_listing.php?id=<?= $listing['id'] ?>" onclick="return confirm('Delete this listing?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php include '../footer.php'; ?>
<script src="../JS/admin.js" defer></script>
</body>
</html>
