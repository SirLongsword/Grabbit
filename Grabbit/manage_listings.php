<?php
require_once __DIR__ . '/config.php';
session_start();

// Redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';

try {
    // Fetch all listings
    if ($role === 'admin' || $role === 'moderator') {
        $stmt = $pdo->query("
            SELECT listings.*, users.username 
            FROM listings 
            JOIN users ON listings.user_id = users.id
            ORDER BY users.username ASC, listings.created_at DESC
        ");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM listings WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
    }

    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching listings: " . $e->getMessage());
}

// Fallback image
$fallbackImage = 'images/defaults/default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Listings</title>
    <link rel="stylesheet" href="CSS/global.css" />
    <link rel="stylesheet" href="CSS/listing_editing.css" />
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <div class="manage-listings container">
        <a href="create_listing.php" class="btn">Create Listing</a>

        <?php if (empty($listings)): ?>
            <p>No Listings Found</p>
        <?php else: ?>

            <?php if ($role === 'admin' || $role === 'moderator'): ?>
                <?php
                // Group by username
                $groupedListings = [];
                foreach ($listings as $listing) {
                    $groupedListings[$listing['username']][] = $listing;
                }
                ?>
                <?php foreach ($groupedListings as $username => $userListings): ?>
                    <h3><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>'s Listings</h3>
                    <?php foreach ($userListings as $listing): ?>
                        <div class="listing-card">
                            <?php
                            $imageSrc = !empty($listing['image']) ? 'images/listings/' . $listing['image'] : $fallbackImage;
                            ?>
                            <img src="<?= htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>" class="listing-image">
                            <h2><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p><strong>Category:</strong> <?= htmlspecialchars($listing['category'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($listing['location'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Price:</strong> R<?= number_format($listing['price'], 2) ?></p>
                            <div class="actions">
                                <a href="edit_listing.php?id=<?= (int)$listing['id'] ?>">Edit</a>
                                <form action="delete_listing.php" method="POST" style="display:inline;" onsubmit="return confirm('Do you want to delete this listing?');">
                                    <input type="hidden" name="id" value="<?= (int)$listing['id'] ?>" />
                                    <button type="submit" class="delete">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>

            <?php else: ?>
                <?php foreach ($listings as $listing): ?>
                    <div class="listing-card">
                        <?php
                        $imageSrc = !empty($listing['image']) ? 'images/listings/' . $listing['image'] : $fallbackImage;
                        ?>
                        <img src="<?= htmlspecialchars($listing['image_path'] ?: 'images/defaults/default.png', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>" class="listing-image-full" />                        <h2><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <p><strong>Category:</strong> <?= htmlspecialchars($listing['category'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($listing['location'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p><strong>Price:</strong> R<?= number_format($listing['price'], 2) ?></p>
                        <div class="actions">
                            <a href="edit_listing.php?id=<?= (int)$listing['id'] ?>">Edit</a>
                            <form action="delete_listing.php" method="POST" style="display:inline;" onsubmit="return confirm('Do you want to delete this listing?');">
                                <input type="hidden" name="id" value="<?= (int)$listing['id'] ?>" />
                                <button type="submit" class="delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
