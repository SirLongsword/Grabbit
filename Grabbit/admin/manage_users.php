<?php
include 'admin_auth.php';
require_once __DIR__ . '/../config.php';

// If user is now moderator
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role'])) {
    $userId = (int) $_POST['user_id'];
    $newRole = $_POST['new_role'];

    if (in_array($newRole, ['user', 'moderator'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ? AND role != 'admin'");
        $stmt->execute([$newRole, $userId]);
    }
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users</title>
    <link rel="stylesheet" href="../CSS/admin.css" />
    <link rel="stylesheet" href="../CSS/global.css" />
</head>
<body>
<?php include '../header.php'; ?>
<div class="container">
    <h2>All Users</h2>
    <table>
        <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <?php if ($user['role'] !== 'admin'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <select name="new_role" onchange="this.form.submit()">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>user</option>
                                <option value="moderator" <?= $user['role'] === 'moderator' ? 'selected' : '' ?>>moderator</option>
                            </select>
                        </form>
                    <?php else: ?>
                        admin
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($user['role'] !== 'admin'): ?>
                        <a href="<?= BASE_URL ?>/partials/delete_user.php?id=<?= $user['id'] ?>"
                           onclick="return confirm('Delete user?')">Delete</a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php include '../footer.php'; ?>
<script src="../JS/admin.js" defer></script>
</body>
</html>
