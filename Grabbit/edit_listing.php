<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid listing ID.');
}

$listingId = (int) $_GET['id'];

// Categories enum options
$categories = CATEGORIES;


try {
    $stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->execute([$listingId]);
    $listing = $stmt->fetch();

    if (!$listing) {
        die('Listing not found.');
    }

    if ($role === 'user' && $listing['user_id'] != $userId) {
        die('Unauthorized access.');
    }
} catch (PDOException $e) {
    die("Error fetching listing: " . $e->getMessage());
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $priceRaw = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $stockRaw = $_POST['stock'] ?? '';
    $category = trim($_POST['category'] ?? '');

    if (!is_numeric($priceRaw) || floatval($priceRaw) < 0) {
        $error = "Please enter a valid positive price.";
    } elseif (!is_numeric($stockRaw) || intval($stockRaw) < 0) {
        $error = "Please enter a valid positive stock quantity.";
    } elseif (empty($title) || empty($location) || !in_array($category, $categories)) {
        $error = "Please fill out all fields correctly and select a valid category.";
    }

    if (empty($error)) {
        $price = floatval($priceRaw);
        $stock = intval($stockRaw);

        try {
            $update = $pdo->prepare("
                UPDATE listings SET 
                    title = ?, location = ?, price = ?, description = ?, stock = ?, category = ?
                WHERE id = ?
            ");
            $update->execute([$title, $location, $price, $description, $stock, $category, $listingId]);

            header("Location: " . BASE_URL . "/manage_listings.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error updating listing: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Listing</title>
    <link rel="stylesheet" href="CSS/global.css" />
    <link rel="stylesheet" href="CSS/listing_editing.css" />
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <div class="container">
        <h2>Edit Listing</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message" style="color:red; margin-bottom:1rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" novalidate>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($listing['title']) ?>" required />

            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($listing['location']) ?>" required />

            <label for="price">Price (R)</label>
            <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($listing['price']) ?>" required />

            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($listing['description']) ?></textarea>

            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" min="0" value="<?= htmlspecialchars($listing['stock']) ?>" required />

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="" disabled>Select category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= ($listing['category'] === $cat) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Update Listing</button>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
