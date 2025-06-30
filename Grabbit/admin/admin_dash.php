<?php include 'admin_auth.php';
require_once __DIR__ . '/../config.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/admin.css">
    <link rel="stylesheet" href="../CSS/global.css" />
</head>
<script src="../JS/admin.js"></script>
<body>
<?php include '../header.php'; ?>
<div class="container">
    <h2>Admin Dashboard</h2>
    <ul>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_listings.php">Manage Listings</a></li>
        <li><a href="reports.php">Reports</a></li>
    </ul>
</div>
<?php include '../footer.php'; ?>
</body>
</html>
