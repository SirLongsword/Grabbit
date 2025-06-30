<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/config.php';

$userId = (int)$_SESSION['user_id'];
$errors = [];
$success = '';

// Fetch user
$stmt = $pdo->prepare('SELECT username, email, created_at, role FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Foirm submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');

    // Validate username
    if ($new_username === '') {
        $errors[] = 'Username cannot be empty.';
    } elseif (mb_strlen($new_username) > 16) {
        $errors[] = 'Username too long (max 16 characters).';
    }

    // Validate email
    if ($new_email === '') {
        $errors[] = 'Email cannot be empty.';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    if (empty($errors)) {
        // Check usernmae or email is already used
        $stmt = $pdo->prepare('SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?');
        $stmt->execute([$new_username, $new_email, $userId]);

        if ($stmt->fetch()) {
            $errors[] = 'Username or email already in use.';
        } else {
            // Update profile
            $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
            $stmt->execute([$new_username, $new_email, $userId]);
            $success = 'Profile updated successfully.';

            // Refresh data
            $user['username'] = $new_username;
            $user['email'] = $new_email;
        }
    }
}

// Determine button
$role = strtolower($user['role'] ?? '');
if ($role === 'admin') {
    $buttonLabel = 'Admin Dashboard';
    $buttonLink = 'admin/admin_dash.php';
} elseif ($role === 'moderator') {
    $buttonLabel = 'Moderator Dashboard';
    $buttonLink = 'admin/moderator_dash.php';
} else {
    $buttonLabel = 'Manage Listings';
    $buttonLink = 'manage_listings.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>My Account</title>
<link rel="stylesheet" href="CSS/global.css" />
<link rel="stylesheet" href="CSS/auth.css" />
<script src="JS/auth.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>

        <div>
            <a href="<?= htmlspecialchars($buttonLink) ?>" class="manage-listings-button">
                <strong><?= htmlspecialchars($buttonLabel) ?></strong>
            </a>
        </div>

        <div class="account-info">
            <p><strong>Member Since:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
        </div>

        <?php if ($errors): ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form id="editProfileForm" method="POST" action="account.php" class="auth-form" novalidate>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" 
                value="<?= htmlspecialchars($_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['username'] ?? '') : $user['username']) ?>" 
                required maxlength="16" />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" 
                value="<?= htmlspecialchars($_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['email'] ?? '') : $user['email']) ?>" 
                required />

            <button type="submit">Update Profile</button>
        </form>

        <form method="POST" action="logout.php" style="margin-top: 20px;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
