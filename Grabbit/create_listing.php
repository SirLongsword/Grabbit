<?php
require_once __DIR__ . '/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$error = '';

// Define categories
$categories = CATEGORIES;


// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token.');
    }

    $title = trim($_POST['title'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $stock = intval($_POST['stock'] ?? 0);
    $category = trim($_POST['category'] ?? '');

    // Check for image errorz
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = "Please upload an image.";
    } else {
        // Validate 
        if (
            empty($title) || empty($location) || $price < 0 || $stock < 0
            || !in_array($category, $categories)
        ) {
            $error = "Please fill out all fields correctly and select a valid category.";
        } else {
            // Image upload
            $image = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB max

            if (!in_array($image['type'], $allowedTypes)) {
                $error = "Only JPG, PNG, and GIF images are allowed.";
            } elseif ($image['size'] > $maxFileSize) {
                $error = "Image size must be less than 2MB.";
            } else {
                // If directory doesnt exist(it should exist)
                $targetDir = __DIR__ . '/images/listings/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                // Generate filename
                $fileExt = pathinfo($image['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('listing_', true) . '.' . $fileExt;
                $targetPath = $targetDir . $fileName;

                // Move uploaded file
                if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                    $imagePath = "images/listings/" . $fileName;

                    try {
                        $stmt = $pdo->prepare("INSERT INTO listings (user_id, title, location, price, description, stock, category, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$userId, $title, $location, $price, $description, $stock, $category, $imagePath]);

                        // Clear CSRF token
                        unset($_SESSION['csrf_token']);

                        header("Location: " . BASE_URL . "/index.php");
exit();
                    } catch (PDOException $e) {
                        $error = "Error creating listing: " . $e->getMessage();
                    }
                } else {
                    $error = "Failed to upload image.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Create Listing</title>
<link rel="stylesheet" href="CSS/global.css" />
<link rel="stylesheet" href="CSS/listing_editing.css"/>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container" style="max-width: 600px; margin: 2rem auto;">
    <h2>Create New Listing</h2>

    <?php if ($error): ?>
        <p style="color: red; margin-bottom: 1rem;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>" />

        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>

        <label for="location">Location</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" required>

        <label for="price">Price (R)</label>
        <input type="number" step="0.01" min="0" id="price" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

        <label for="stock">Stock</label>
        <input type="number" min="0" id="stock" name="stock" value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>" required>

        <label for="category">Category</label>
        <select id="category" name="category" required>
            <option value="" disabled <?= empty($_POST['category']) ? 'selected' : '' ?>>Select category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($_POST['category']) && $_POST['category'] === $cat) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="image">Upload Image</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <input type="submit" value="Create Listing">
    </form>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
