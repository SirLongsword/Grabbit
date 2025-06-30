<?php
session_start();
require_once __DIR__ . '/../config.php';
// Check user login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$currentUserId = $_SESSION['user_id'];
$chatUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if (!$chatUserId) {
    die("No user specified.");
}
// Finding user in conversation
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$chatUserId]);
$chatUser = $stmt->fetch();

if (!$chatUser) {
    die("User not found.");
}

$stmt = $pdo->prepare("
    SELECT m.*, u.username AS sender_username
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY m.sent_at ASC
");
$stmt->execute([$currentUserId, $chatUserId, $chatUserId, $currentUserId]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Chat with <?= htmlspecialchars($chatUser['username']) ?></title>
    <link rel="stylesheet" href="../CSS/global.css" />
    <link rel="stylesheet" href="../CSS/messages.css" />
</head>
<body>
<?php include '../header.php'; ?>

<main class="main-container">
    <a href="messages.php" class="back-button">&larr; Back to Inbox</a>

    <h2>Chat with <?= htmlspecialchars($chatUser['username']) ?></h2>

    <div class="chat-box">
        <?php if (count($messages) === 0): ?>
            <p>No messages yet. Say hello!</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message <?= $msg['sender_id'] == $currentUserId ? 'sent' : 'received' ?>">
                    <div class="bubble">
                        <?= nl2br(htmlspecialchars($msg['content'])) ?>
                        <div class="meta">
                            <?= htmlspecialchars($msg['sender_username']) ?> | <?= $msg['sent_at'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form action="send_message.php" method="POST">
        <input type="hidden" name="receiver_id" value="<?= $chatUserId ?>">
        <textarea name="message" rows="3" placeholder="Type your message..." required></textarea>
        <button type="submit">Send</button>
    </form>
</main>

<?php include '../footer.php'; ?>
</body>
</html>
