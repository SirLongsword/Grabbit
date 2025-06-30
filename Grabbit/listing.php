<?php
require_once __DIR__ . '/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_POST['id'];

$stmt = $pdo->prepare("
    SELECT listings.*, users.username, users.id AS user_id
    FROM listings 
    JOIN users ON listings.user_id = users.id 
    WHERE listings.id = ?
");
$stmt->execute([$id]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    echo "Listing not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?> - Grabbit</title>
  <link rel="stylesheet" href="CSS/global.css" />
  <link rel="stylesheet" href="CSS/listings.css" />
</head>
<body>

<?php include 'header.php'; ?>

<main class="container listing-detail-page">
  <div class="listing-detail-card">
    
    <img src="<?= htmlspecialchars($listing['image_path'] ?: 'images/defaults/default.png', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>" class="listing-image-full" />
    
    <h1 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p style="margin: 0.3rem 0;"><strong>Category:</strong> <?= htmlspecialchars($listing['category'], ENT_QUOTES, 'UTF-8') ?></p>
    <p style="margin: 0.3rem 0;"><strong>Location:</strong> <?= htmlspecialchars($listing['location'], ENT_QUOTES, 'UTF-8') ?></p>

    <p style="margin: 0.3rem 0;"><strong>Posted by:</strong>
      <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $listing['user_id']): ?>
        <a href="messages/conversation.php?user_id=<?= urlencode($listing['user_id']) ?>">
          <?= htmlspecialchars($listing['username'], ENT_QUOTES, 'UTF-8') ?>
        </a>
      <?php else: ?>
        <?= htmlspecialchars($listing['username'], ENT_QUOTES, 'UTF-8') ?>
      <?php endif; ?>
    </p>

    <p style="margin: 0.5rem 0; color: #213E60;"><strong>Price:</strong> R<?= number_format($listing['price'], 2) ?></p>

    <?php if (!empty($listing['description'])): ?>
      <div>
        <h3>Description</h3>
        <p><?= nl2br(htmlspecialchars($listing['description'], ENT_QUOTES, 'UTF-8')) ?></p>
      </div>
    <?php endif; ?>

    <div>
      <a href="index.php">← Back to Listings</a>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
