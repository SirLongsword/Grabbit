<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Grabbit</title>
  <link rel="stylesheet" href="CSS/global.css" />
  <link rel="stylesheet" href="CSS/homepage.css" />
  <link rel="stylesheet" href="CSS/listings.css" />

</head>
<body>

<?php include 'header.php'; ?>

<main class="homepage-page container">
  <!-- Categories -->
  <section class="section categories-section">
    <h2 class="section-title">Browse Categories</h2>
    <div class="grid categories-grid">
      <?php foreach (CATEGORIES as $category): ?>
        <a href="category.php?name=<?= urlencode(strtolower($category)) ?>" class="category-item">
          <?= htmlspecialchars($category) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Listings -->
  <section class="section listings-section">
    <h2 class="section-title">Recent Listings</h2>
    <div class="grid listings-grid">
      <?php
        $stmt = $pdo->query("SELECT id, title, location, price, image_path FROM listings ORDER BY created_at DESC LIMIT 6");
        $listings = $stmt->fetchAll();
        foreach ($listings as $listing):
          include 'partials/listing_tile.php';
        endforeach;
      ?>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
