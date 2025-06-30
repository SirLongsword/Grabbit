<form method="POST" action="listing.php" class="listing-card">
  <input type="hidden" name="id" value="<?= (int)$listing['id'] ?>" />
  <button type="submit">
    <img src="<?= htmlspecialchars($listing['image_path'] ?: 'images/defaults/default.png') ?>" alt="<?= htmlspecialchars($listing['title']) ?>" />
    <div class="listing-info">
      <h3 class="listing-title"><?= htmlspecialchars($listing['title']) ?></h3>
      <p class="listing-location"><?= htmlspecialchars($listing['location']) ?></p>
      <p class="listing-price">R<?= number_format($listing['price'], 2) ?></p>
      <?php if (!empty($listing['username'])): ?>
        <p class="listing-user">Posted by <?= htmlspecialchars($listing['username']) ?></p>
      <?php endif; ?>
    </div>
  </button>
</form>
