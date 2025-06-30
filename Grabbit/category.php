<?php
require_once __DIR__ . '/config.php';

$categoryInput = $_GET['name'] ?? '';

// Normalize categories
$validCategoriesLower = array_map('strtolower', CATEGORIES);
$categoryLower = strtolower($categoryInput);

if (!$categoryInput || !in_array($categoryLower, $validCategoriesLower)) {
    die('Category not specified or invalid.');
}

// Get the correcty category
$categoryKey = array_search($categoryLower, $validCategoriesLower);
$category = CATEGORIES[$categoryKey];

// Fetch listings
$stmt = $pdo->prepare("SELECT id, title, location, price, image_path FROM listings WHERE category = ?");
$stmt->execute([$category]);
$listings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($category) ?> Listings</title>
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
      <?php foreach (CATEGORIES as $cat): ?>
        <a href="category.php?name=<?= urlencode(strtolower($cat)) ?>" class="category-item">
          <?= htmlspecialchars($cat) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Listings -->
  <section class="section listings-section">
    <h2 class="section-title"><?= htmlspecialchars($category) ?> Listings</h2>

    <?php if (count($listings) > 0): ?>
      <div class="grid listings-grid">
        <?php foreach ($listings as $listing): ?>
          <form method="POST" action="listing.php" class="listing-card">
            <input type="hidden" name="id" value="<?= (int)$listing['id'] ?>" />
            <button type="submit">
              <img src="<?= htmlspecialchars($listing['image_path'] ?: 'images/defaults/default.png') ?>" alt="<?= htmlspecialchars($listing['title']) ?>" />
              <div class="listing-info">
                <h3 class="listing-title"><?= htmlspecialchars($listing['title']) ?></h3>
                <p class="listing-location"><?= htmlspecialchars($listing['location']) ?></p>
                <p class="listing-price">R<?= number_format($listing['price'], 2) ?></p>
              </div>
            </button>
          </form>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No listings found in this category.</p>
    <?php endif; ?>
  </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
