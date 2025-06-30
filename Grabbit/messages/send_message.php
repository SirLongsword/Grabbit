<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$senderId = $_SESSION['user_id'];
$error = '';
$success = '';

$messageText = trim($_POST['message'] ?? '');
$receiverId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['receiver_id'])) {
        // Sending from conversation.php with receiver_id
        $receiverId = (int)$_POST['receiver_id'];

        if (!$messageText) {
            $error = 'Please enter a message.';
        } elseif ($receiverId === $senderId) {
            $error = "You can't send a message to yourself.";
        } else {
            // Check if receiver exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
            $stmt->execute([$receiverId]);
            if (!$stmt->fetch()) {
                $error = 'Recipient user not found.';
            }
        }
    } else {
        // Sending from send_message.php form - use username lookup
        $recipientUsername = trim($_POST['recipient_username'] ?? '');

        if (!$recipientUsername || !$messageText) {
            $error = 'Please fill in all fields.';
        } else {
            // Lookup user by username
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$recipientUsername]);
            $user = $stmt->fetch();

            if (!$user) {
                $error = 'Recipient username not found.';
            } elseif ($user['id'] == $senderId) {
                $error = "You can't send a message to yourself.";
            } else {
                $receiverId = $user['id'];
            }
        }
    }

    if (!$error && $receiverId && $messageText) {
        // Insert message
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$senderId, $receiverId, $messageText]);

        // Redirect to conversation
        header("Location: conversation.php?user_id=" . urlencode($receiverId));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Send Message</title>
    <link rel="stylesheet" href="../CSS/messages.css" />
    <link rel="stylesheet" href="../CSS/global.css" />
</head>
<body>
<?php include '../header.php'; ?>

<main class="main-container">
    <a href="messages.php" class="back-button">&larr; Back to Inbox</a>
    <h2>Send a Message</h2>

    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <form method="POST" action="send_message.php">

        <?php if (empty($_POST['receiver_id'])): ?>
            <label for="recipient_username">To (username):</label><br />
            <input 
                type="text" 
                id="recipient_username" 
                name="recipient_username" 
                value="<?= htmlspecialchars($_POST['recipient_username'] ?? '') ?>" 
                placeholder="Enter recipient's username" 
                required 
                autocomplete="off" 
            /><br /><br />
        <?php else: ?>

            <input type="hidden" name="receiver_id" value="<?= (int)$_POST['receiver_id'] ?>" />
        <?php endif; ?>

        <label for="message">Message:</label><br />
        <textarea 
            id="message" 
            name="message" 
            rows="5" 
            placeholder="Type your message here..." 
            required
        ><?= htmlspecialchars($messageText) ?></textarea><br /><br />

        <button type="submit">Send</button>
    </form>
</main>

<?php include '../footer.php'; ?>
</body>
</html>
