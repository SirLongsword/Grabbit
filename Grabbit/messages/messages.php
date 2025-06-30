<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$currentUserId = $_SESSION['user_id'];
// Prepping query
$stmt = $pdo->prepare("
    SELECT u.id, u.username
    FROM users u
    WHERE u.id IN (
        SELECT DISTINCT 
            CASE 
                WHEN sender_id = :currentUser THEN receiver_id
                ELSE sender_id
            END
        FROM messages
        WHERE sender_id = :currentUser OR receiver_id = :currentUser
    )
    AND u.id != :currentUser
    ORDER BY u.username
");
// exectuing query
$stmt->execute(['currentUser' => $currentUserId]);
$conversations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Inbox</title>
    <link rel="stylesheet" href="../CSS/global.css" />
    <link rel="stylesheet" href="../CSS/messages.css" />
</head>
<body>
<?php include '../header.php'; ?>

<main class="main-container">

    <div class="inbox-header">
        <h2>Your Inbox</h2>
        <a class="start-chat-btn" href="send_message.php">Start New Chat</a>
    </div>

    <?php if (empty($conversations)): ?>
        <p>You have no conversations yet.</p>
    <?php else: ?>
        <ul class="inbox-list">
            <?php foreach ($conversations as $user): ?>
                <li>
                    <a href="conversation.php?user_id=<?= htmlspecialchars($user['id']) ?>">
                        <span class="user-name"><?= htmlspecialchars($user['username']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</main>

<?php include '../footer.php'; ?>
</body>
</html>
