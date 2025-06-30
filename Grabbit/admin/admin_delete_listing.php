<?php
require_once __DIR__ . '/../config.php';
session_start();

// Check role
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

// Delete listing
if (isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM listings WHERE id = ?");
        $stmt->execute([$_GET['id']]);
    } catch (PDOException $e) {

    }
}


$baseURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . BASE_URL;
$defaultRedirect = $baseURL . '/index.php';
$redirectTo = isset($_GET['redirect']) && strpos($_GET['redirect'], BASE_URL) === 0
    ? $baseURL . substr($_GET['redirect'], strlen(BASE_URL))
    : $defaultRedirect;

header("Location: $redirectTo");
exit;
