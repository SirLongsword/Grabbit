<?php
session_start();
require_once __DIR__ . '/config.php';

// Role re-direwct
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['user_id'])) {
    switch ($_SESSION['role'] ?? '') {
        case 'admin':
            header('Location: ' . BASE_URL . '/admin/admin_dash.php');
            break;
        case 'moderator':
            header('Location: ' . BASE_URL . ' /admin/moderator_dash.php');
            break;
        default:
            header('Location: ' . BASE_URL . ' /.php');
            break;
    }
    exit();
}

// handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill in both fields.';
        header('Location: ' . BASE_URL . ' login.php');
        exit();
    }

    // Get user
    $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect
        switch ($user['role']) {
            case 'admin':
                header('Location: ' . BASE_URL . '/admin/admin_dash.php');
                break;
            case 'moderator':
                header('Location: ' . BASE_URL . ' /admin/moderator_dash.php');
                break;
            default:
                header('Location: ' . BASE_URL . '/index.php');

                break;
        }
        exit();
    } else {
        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: ' . BASE_URL . ' /login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="CSS/global.css" />
    <link rel="stylesheet" href="CSS/auth.css" />
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="auth-page">
        <div class="auth-container">
            <h2>Login to Your Account</h2>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="auth-form" method="POST" action="login.php" novalidate>
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required autocomplete="email" />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required data-password-toggle />

                <div class="show-password-container">
                    <input type="checkbox" id="show-password" />
                    <label for="show-password">Show Password</label>
                </div>

                <button type="submit">Login</button>
            </form>

            <div class="toggle-auth">
                Don’t have an account?
                <button onclick="window.location.href='register.php'">Register here!</button>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="JS/auth.js" defer></script>
</body>
</html>
