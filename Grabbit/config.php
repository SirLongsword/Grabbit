<?php
// Base URL
define('BASE_URL', '/Grabbit');

// Database connection settings
$host = 'localhost';
$db   = 'grabbit';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    function getEnumValues(PDO $pdo, string $table, string $column): array {
        $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        $row = $stmt->fetch();

        if (preg_match("/^enum\((.*)\)$/", $row['Type'], $matches)) {
            $vals = explode(",", $matches[1]);
            return array_map(fn($v) => trim($v, "'"), $vals);
        }

        return [];
    }
    // Setting categories
    define('CATEGORIES', getEnumValues($pdo, 'listings', 'category'));
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}