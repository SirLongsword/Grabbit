<?php
session_start();
require_once __DIR__ . '/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validations
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (strlen($username) > 16) {
        $errors[] = 'Username must be less than 16 characters.';
    }

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // Check email or username is taken
    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $errors[] = 'Email or username already in use.';
        }
    }

    // Insert new user
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hash]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: account.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <link rel="stylesheet" href="CSS/global.css" />
    <link rel="stylesheet" href="CSS/auth.css" />
</head>
<body>
<?php include 'header.php'; ?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Create a New Account</h2>

        <?php if ($errors): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="auth-form" method="POST" action="register.php" novalidate>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required data-password-toggle />

            <label for="password_confirm">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" required data-password-toggle />

            <div class="show-password-container">
                <input type="checkbox" id="show-password" />
                <label for="show-password">Show Password</label>
            </div>

            <button type="submit">Register</button>
        </form>

        <div class="toggle-auth">
            Already have an account?
            <button onclick="window.location.href='login.php'">Login</button>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
<script src="JS/auth.js" defer></script>
</body>
</html>
