<?php
require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $query = '%' . trim($_POST['query']) . '%';

    $stmt = $pdo->prepare("
        SELECT id, title, location, price, image_path 
        FROM listings 
        WHERE title LIKE ? OR category LIKE ? 
        LIMIT 5
    ");
    $stmt->execute([$query, $query]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)) {
        foreach ($results as $row) {
            $imgSrc = htmlspecialchars($row['image_path'] ?: 'images/defaults/default.png', ENT_QUOTES, 'UTF-8');
            $title  = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
            $loc    = htmlspecialchars($row['location'], ENT_QUOTES, 'UTF-8');
            $price  = number_format((float)$row['price'], 2);
            $id     = (int)$row['id'];

            echo "<form method='POST' action='listing.php' class='search-result-form' style='all: unset; display: block; cursor: pointer;'>";
            echo "  <input type='hidden' name='id' value='$id'>";
            echo "  <button type='submit' class='search-result-button' style='all: unset; display: flex; align-items: center; width: 100%; text-align: left;'>";
            echo "    <img src='$imgSrc' alt='Thumbnail' style='width:60px;height:60px;object-fit:cover;border-radius:6px;margin-right:10px;'>";
            echo "    <div>";
            echo "      <strong>$title</strong><br>";
            echo "      <span>$loc</span><br>";
            echo "      <span style='color:#213E60;font-weight:bold;'>R$price</span>";
            echo "    </div>";
            echo "  </button>";
            echo "</form>";
        }
    } else {
        echo "<div class='search-result'>No results found.</div>";
    }
} else {
    echo "<div class='search-result'>No search query provided.</div>";
}
?>
